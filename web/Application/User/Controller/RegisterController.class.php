<?php
namespace User\Controller;
use Home\Controller\HomeController;
class RegisterController extends HomeController{
	public	$table	=	'member';
	public  $shop = 'shop';
	/*
	 * 注册
	 *		需求分析
	 *			注册分为三种分别是普通用户注册,公司注册,个人译者注册三种,需要验证手机是否已经被注册了
	 *		流程分析
	 *			1、接收用户传的值等
	 *			2、验证值的合法性
	 *			3、验证
	 * */
	public function index(){
		if(session('home_member_id')){//判断用户是否已经登录,如果登录就不能再注册
			header("location:".U("Home/Index/index"));
		}else{
			if(IS_POST){
				//接收用户的传值
				
				$apptype = (!empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY'))?true:false;//手机app接口密钥
				
				//手机返回json数据
				if($apptype){
					$ajax=true;
				}else{
					$ajax=false;
				}
				$type	=	I('get.type');
				$telephone	= I('telephone');

				$password	=	md5(md5(I('password')));//密码
				//$email = I('email');
				$confirmPassword	=	md5(md5(I('confirmPassword')));//	确认密码
				$code	=	I('code');//验证码
				$province = I('province');//省id
				$city = I('city');//市id
				
				if($apptype){
					$member_id = intval(I('registerId'));
				}else{
					$member_id = session('registerId');
				}
				
				//验证信息的合法性
				$rules = array (
					array('telephone','require','请输入手机号'), //默认情况下用正则进行验证
					array('password','require','请输入密码！'), //默认情况下用正则进行验证
					//array('email','require','请输入邮箱！'), //默认情况下用正则进行验证
				);
				$is_telephone	=	0;
				$is_email = 0;
				//验证手机号码合法性
				if(preg_match('/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/', $telephone)){
					$_POST['telephone']=$telephone;
					$is_telephone=1;
					
					$info=get_info('member',array('telephone'=>$telephone,'status'=>1,'type'=>array('GT',-2)));
					if($info){
						//如果手机号码被使用就删除这条记录
						delete_data($this->table,array('id'=>$member_id));

						$this->error('手机号已被使用','',$ajax);
					}
				}
				/* if(preg_match("/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $email)){
					$_POST['telephone']=$telephone;
					$is_email=1;
					
					$info=get_info('member',array('telephone'=>$telephone,'status'=>1,'type'=>array('GT',-2)));
					if($info){
						$this->error('邮箱已被使用');
					}
				} */
				if($is_telephone==0){
					
						$this->error('请输入正确的手机号','',$ajax);
				}
				/* if($is_email==0){
					$this->error('请输入邮箱！');
				} */
				//验证验证码的正确性
					//查询验证码
				$member_info = get_info($this->table,array('id'=>$member_id,'telephone'=>$telephone));
				$member_code = $member_info['code'];
				if($code!=$member_code){
					$this->error('验证码不正确,请重新输入！','',$ajax);
				}
				//组织填写member表中需要系统自动定义的字段????????????????????????????????
				
					//生成用户登录的code数字的形式存入到数据库中
				//$username = uniqid();//以微秒计数生成唯一的id
				if($type==1){
					$_POST['type'] = 1;//表示普通用户
					$_POST['role_id'] = 7;
				}else if($type==2){
					$_POST['type'] = 2;//表示个人译者
					$_POST['role_id'] = 8;
				}else if($type==3){
					$_POST['type'] = 3;//表示公司
					$_POST['role_id'] = 9;
				}
				
				//将用户的信息存入到member表中
				$_POST['id'] = $member_id;
				//$_POST['type'] = 0;
				$_POST['password'] = $password;
				$_POST['confirmPassword'] = $confirmPassword;
				$_POST['username'] = $telephone;
				$_POST['integration']=100;
				$result	=	update_data($this->table,$rules);
				
				/*@刘巧将用户手机号作为昵称并隐藏4位*/
				$username = substr($telephone, 0, 5).'****'.substr($telephone, 9);
				//将需要的信息存入到session中直接表示用户登录了
				if(is_numeric($result)){
					session('home_member_id',$result);//将用户的id存入session值
					session('nickname',$username);//用户的昵称存入session值
					session('points',100);//注册用户积分
					session ( 'home_member_tel',$telephone );
					if($apptype){
						$userinfo["home_member_id"]=$result;//用户的id
						$userinfo["nickname"]=$username;//用户的昵称
						$userinfo['balance']="0.00";//账户余额
						$userinfo['withdrawals']="0.00";//可提现金额
					}
					//生成店铺信息
					if($type==2){
						unset($_POST);
						$_POST['type'] = 2;//个人译者
						$_POST['member_id'] = $result;
						$_POST['status'] = 3;
						$result = update_data($this->shop);
						session('home_shop_id',$result);
						session('nickname',$username);//用户的昵称存入session值
						session ( 'home_member_tel',$telephone );
						session('type',$type);
						if($apptype){
							$userinfo["home_shop_id"]=$result;
							$userinfo["type"]=$type;
						}
					}else if($type==3){
						unset($_POST);
						$_POST['type'] = 3;//公司注册
						$_POST['member_id'] = $result;
						$_POST['status'] = 3;//表示没有审核通过
						$_POST['area_id'] = $city;
						$result = update_data($this->shop);
						session('nickname',$username);//用户的昵称存入session值
						session ( 'home_member_tel',$telephone );
						session('home_shop_id',$result);
						session('type',$type);
						if($apptype){
							$userinfo["home_shop_id"]=$result;
							$userinfo["type"]=$type;
						}
					}
					//生成店铺日程信息,这个日程信息是初始化的日程信息
					$sql = '';
					for($i=1;$i<=3;$i++){
						for($s=1;$s<=7;$s++){
							$sql .= "insert into `sr_shop_time`(`shop_id`,`time`,`week`,`type`) values($result,$i,$s,3);";
						}
					}
					$Model = M();
					$result2 = $Model->execute($sql);
					
					if(is_numeric($result2)){
						if($apptype){
							$data["status"]="1";
							$data["info"]="注册成功！";
							$data["userinfo"]=$userinfo;
							$this->ajaxReturn($data);
						}else{
							if($type == 1){
								$content=C('REGSUCCEESS');
								$account=$I('telephone');
								send_code($content,$account);
								$this->success('注册成功！',U('Home/Index/index'));
							}else{
								$this->success('注册成功！',U('/User/StoreSetting/index'));
							}

						}   
						
					}else{
						if($apptype){
							$this->error($result2,'',$ajax);
						}else{
							$this->error($result2,U('Home/Index/index'));
						}
					}
				}else{
				       if($apptype){
							$this->error($result,'',$ajax);
						}else{
							$this->error($result,U('Home/Index/index'));
						}
				}
			}else{
				//查询地理信息
				$area_data = get_area_cache();
				//接收判断是哪一种注册
				$type = intval(I('type'));
				
				$data['area_data'] = $area_data;
				$data['type'] = $type;
				$this->assign($data);
				if(!empty($type)){
					$this->display('index_1_1');
				}else{
					$this->display('index_1');
				}
			}
		}
	}
	/*
     * 注册发送验证码
     * */
    function registerCode(){
    	//接收传入的参数
    	$apptype = !empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY') ?true:false;//手机app接口密钥
    	$account=trim(I('post.account'));
    	//手机返回json数据
    	if($apptype){
    		$ajax=true;
    	}else{
    		$ajax=false;
    	}
		//$account_email = trim(I('post.account_email'));
		
    	$code=rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    	//session('register_code',$code);

		
    	$info=get_info('member',array('telephone'=>$account,'status'=>array('gt',-1),'type'=>array('GT',-2)));
		if($info){
			    $this->error('验证短信发送失败，手机号码已经被注册！！','',$ajax);
		}
    	//如果是手机号码
    	if(preg_match('/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/', $account)){
    		//$content="您在N邦翻译上注册验证码为：".$code."，如非本人操作，无需理会【N邦翻译】";
			//判断手机号码是否注册了如果注册了就返回一个用户手机号码已经注册
			$content=$code;
    		$status=sms($account, $content);
    		if($status<0){
    				$this->error('验证短信发送失败，请联系客服','',$ajax);
    		}else{
    			session("user_registercode",$code);
				//判断用户是否注册过,如果注册过就将将验证码写入到其中如果没有注册过就生成一个新的注册用户
				$info = get_info($this->table,array('telephone'=>$account,'type'=>-2));
				if(!$info){
					//生成一个注册用户
					$_POST['telephone'] = $account;
					$_POST['code']	= $code;
					$_POST['type'] = -2;//表示注册用户
					$result = update_data($this->table);
					session('registerId',$result);//生成的用户的id
					$data["registerId"]=$result;//手机
				}else{
					$_POST['code'] = $code;
					$_POST['id'] = $info['id'];
					update_data($this->table);
					session('registerId',$info['id']);//原有的id
					$data["registerId"]=$info['id'];//手机
				}
				if($apptype){
				   $data["status"]=1;
				   $data["info"]="验证短信已发送，请查看";
				   $data["code"]=$code;
				   $this->ajaxReturn($data);
				}else{
    			   $this->success('验证短信已发送，请查看');
				}  
    		}
    	}else{
    		session('register_code',null);
    		$this->error('请输入正确的手机号码！','',$ajax);
    	}
    }
    /* ajax验证注册表单
     * @author 龚双喜
     * @date 2015-08-06
     * */
    function checkForm(){
      if(IS_POST){
        if($_POST["name"]=="telephone"){	
	    	$telephone = $_POST["param"];
	    	if(preg_match('/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/', $telephone)){
		    	$info=get_info('member',array('telephone'=>$telephone,'status'=>array('gt',-1),'type'=>array('GT',-2)));
		    	if($info){
		    		$this->error('手机号码已经被注册！！');
		    	}else{
		    		$data["info"]="手机号码输入正确！";
		    		$data["status"]="y";
		    		$this->ajaxReturn($data);
		    	}
	    	}else{
	    		$this->error('手机号码格式不正确！！');
	    	}
        }
        if($_POST["name"]=="code"){
        	$regitercode = $_POST["param"];
        	if(empty($regitercode)){
        		$this->error('请输入验证码！');
        	}
        	$code = session("user_registercode");
        	if($code!=$regitercode){
        		$this->error('验证码不正确,请重新输入！');
        	}else{
        		$data["info"]="验证码输入正确！";
        		$data["status"]="y";
        		$this->ajaxReturn($data);
        	}
        }
      }else{
      	$this->error('非法访问！！');
      }
    }
}