<?php
namespace User\Controller;
use Home\Controller\HomeController;
class LoginController extends HomeController{
	public $table	=	'member';
	/**
	*用户登录
	*	需求分析
	*		用户通过登录框登录并验证用户信息,用户登录分为三种登录方式的登录
	*	流程分析
	*		1、三种用户登录的方式大致相同
	*		2、使用type关键字区分用户登录的区别，以便于做不同情况的验证
	*		3、将验证码显示出来
	**/
	public function index(){
		if(IS_POST){
			//app接口访问密钥
			$apptype = (!empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY')) ?true:false;
			if($apptype){
				$this->appLogin();
			}else{
				$this->webLogin();
			}
			
		}else{
			$this->display();
		}
		
	}
	/*
	 * 电脑客户端登录
	 * @author  龚双喜
	 * @date    2015-07-22
	 * */
	public function webLogin(){
	
		//接收用户传值
		$type	=	intval(I('type'));//接收是那种形式的登录
		$code	=	I('code');//接收验证码
			
		$username = addslashes(I('username'));//接收用户名
		$password = md5(md5(I('password')));//接收用户的密码
			
		//验证三种登录都需要验证的信息
		//验证验证码是否正确
		$verify = new \Think\Verify();
		$res = $verify->check($code, 1);
		if(!$res){
			$this->error('验证码错误！！');
		}
			
		//验证三种登录的公共信息是否正确
		$map['username|telephone|realname'] 	= 	$username;
		$map['password']	=	$password;
		$map['status'] = array("gt",-1);
		//$map['type']        = $type;//表示普通用户登录
		$info	=	get_info(D('ShopMemberView'),$map);
		if($info['shop_id']){//如果开店就将店铺的id存入到session中去
			session('home_shop_id',$info['shop_id']);
		}
		if($info and $info["status"]==1){
			//验证三种登录的独特信息
			//不允许不同类型的用户之间相互乱登录
			if($type==1){//表示普通的用户登录
				if($info['role_id']!=7){
					$this->error('您的身份不是普通用户！');
				}
			}else if($type==2){
				if($info['role_id']!=8){
					$this->error('您的身份不是个人译者！');
				}
			}else if($type==3){
				if($info['role_id']!=9){
					$this->error('您的身份不是翻译公司！');
				}
			}
	
			if(intval($info['member_id'])>0){
				/*如果是用户登录，更新用户有关的订单状态*/
				check_complete($info['member_id']);
			}
			if(intval($info['shop_id'])>0){
				/*如果是店铺登录，更新店铺所有的订单状态*/
				check_complete(0,$info['shop_id']);
			}
			/*@刘巧将用户手机号作为昵称并隐藏4位*/
			$username = substr($info['username'], 0, 5).'****'.substr($info['username'], 9);
			$new =get_news_recode($info['member_id']);
			session('news_num',$new);
			//将用户的id存入到session中,需要的后面再加
			session('home_member_id',$info['member_id']);//将用户的id存入session值
			session('home_member_tel',$info['username']);
			session('username',$username);//将用户名存入session值
			session('nickname',$info['nickname']);//用户的昵称存入session值
			session('points',$info['integration']);//用户的积分存入session值
			session('type',$type);//表示用户登录的类型
			session('shop_status',$info['shop_status']);//加入店铺的开通状态
			session('translate_type',$info['translate_type']);//加入店铺的服务类型
			
			$this->success('登录成功！！',U('Home/Index/index'));
		}elseif($info and $info["status"]==0){
			$this->error('您的账户已被禁用，请联系客服！！',U('User/Login/index'));
		}elseif($info and $info["status"]==-1){
			$this->error('用户不存在！！',U('User/Login/index'));
		}else{
			$this->error('账户名或密码错误！！',U('User/Login/index'));
		}
	
	}
	
	/*
	 * 手机客户端普通用户登录
	 * @author  龚双喜
	 * @date    2015-07-22
	 * */
	public function appLogin(){
		//接收用户传值
		$type	=	intval(I('type'));//接收是那种形式的登录
	
		$username = addslashes(I('username'));//接收用户名
		$password = md5(md5(I('password')));//接收用户的密码
	
		//判断用户名或密码是否为空
		if(empty(I('username'))){
	
			$this->error("用户名不能为空","",true);
		}
	
		if(empty(I('password'))){
	
			$this->error("密码不能为空","",true);
		}
		//验证三种登录的公共信息是否正确
		$map['telephone'] 	= 	$username;
		$map['password']	=	$password;
		$map['status'] = array("gt",-1);
		//$map['type']        = $type;//表示普通用户登录
		$info	=	get_info(D('ShopMemberView'),$map);
	
		//将用户的信息存入数组userinfo中
		$userinfo['home_member_id']=$info['member_id'];//将用户的id存入userinfo中
		$userinfo['nickname']=$info['username'];//用户的昵称存入userinfo中
		$userinfo['points']=$info['points'];//用户的积分存入userinfo中
		$userinfo['login_time']=md5($info["login_time"]);//登录时间存入userinfo中
		$userinfo['balance']=$info['balance'];//账户余额
		$userinfo['withdrawals']=$info['withdrawals'];//可提现金额
		$userinfo['shop_status']=$info['shop_status'];//加入店铺的开通状态
	
		/*统计全部订单数*/
		$all_count = M("orders")->where(array('member_id'=>$info['member_id']))->count();
		
		$userinfo['order_count']=$all_count;//订单数
		
		//用户头像
		$userinfo['header_src']='http://'.$_SERVER['SERVER_NAME'].'/'.getAvatar($info['member_id']);
	
		if($info['shop_id']){//如果开店就将店铺的id存入到userinfo中去
			$userinfo['home_shop_id']=$info['shop_id'];
		}
	
		if($info and $info["status"]==1){
			//验证登录信息
			//不允许不同类型的用户之间相互乱登录
	
			if($type==1){//表示普通的用户登录
				if($info['role_id']!=7){
	
					$this->error("您的身份不是普通用户！","",true);
				}
			}else if($type==2){
				if($info['role_id']!=8){
	
					$this->error("您的身份不是个人译者！","",true);
				}
			}else if($type==3){
				if($info['role_id']!=9){
	
					$this->error("您的身份不是翻译公司！","",true);
				}
			}
				
	
			if(intval($info['member_id'])>0){
				/*如果是用户登录，更新用户有关的订单状态*/
				check_complete($info['member_id']);
			}
			if(intval($info['shop_id'])>0){
				/*如果是店铺登录，更新店铺所有的订单状态*/
				check_complete(0,$info['shop_id']);
			}
			$data["status"]="1";
			$data["info"]="登录成功！";
			$data["userinfo"]=$userinfo;
			$this->ajaxReturn($data);
		}else if($info and $info["status"]==0){
			$this->error('您的账户已被禁用，请联系客服！！',U('User/Login/index'),true);
		}else{
			$this->error("账户名或密码错误！！","",true);
		}
	}
	//验证码的获取
	public function verify(){
		$config =    array(    
			'fontSize'    =>    200,    // 验证码字体大小    
			'length'      =>    4,     // 验证码位数    
		);
		$verify = new \Think\Verify($config);	
		$verify->entry(1);
	}
	
	//忘记密码
	public function forget(){
		//判断是否登录，如果已登录直接跳转到个人中心
		if(session('home_member_id')){
			header("location:".U('/User'));
		}else{
			if(IS_POST){
				$posts=I("post.");
				$_POST=null;
    			$account=$_POST['account'];
				//验证帐号是否是验证码接收帐号，避免获取验证码后将帐号改为其它
				if(session('get_account')!=$account){
					$this->error("帐号已更换，请重新发送验证码");
				}
				//验证验证码是否正确
				if(session('get_code')!=$_POST['code']){
					$this->error("验证码错误");
				}

				//判断两次输入密码是否一致
				if($posts['repassword']!=$posts['password']){
					$this->error('两次输入密码不一致，请确认');
				}else if(strlen($posts['password'])<6){
					$this->error('密码长度请设置在6位以上');
				}

				//查询提交过来的邮箱是否存在
				$user_info=get_info($this->table,array('email'=>$account));
				if($user_info){
					$_POST['id']=$user_info['id'];
					//判断帐号类型，手机/邮箱，session("account_type")在点击发送验证码时产生
					if(session("account_type")=='phone'){
						$_POST['telephone']=$account;
					}else if(session("account_type")=='email'){
						$_POST['email']=$account;
					}
					session("account_type",null);
					$_POST['password']=md5(md5($posts['password']));
					$result=update_data($this->table,$rules);
					if(is_numeric($result)){
						//密码找回成功后自动切换到登录状态

						//将用户ID、登录帐号、登录信息存入session
						$nowTime = date('Y-m-d H:i:s');
						$nowIp = get_client_ip();
						session("member_id",$user_info['id']);
						session("account",$account);
						session("last_login_time",$user_info['login_time']);
						session("last_login_ip",$user_info['login_ip']);
						session("login_time",$nowTime);
						session("login_ip",$nowIp);

						$this->success("新密码设置成功");
					}else{
						$this->error($result);
					}
				}else{
					$this->error("邮箱不存在，请检查后重新输入",'',3);
				}
			}else{
				$this->display();
			}
		}
	}
	//退出登录
	public function loginout(){
		//将退出登录前的URL重新赋值给变量
		$login_url=session("login_url");
		//清空所有session
		session(null);
		//跳转会退出前的所在页面
		header("location:".$login_url);
	}
}