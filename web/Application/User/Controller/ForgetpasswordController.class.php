<?php
namespace User\Controller;
use Home\Controller\HomeController;
class ForgetpasswordController extends HomeController {
	private $table	=	'member';
	/**
	*忘记密码功能页面第一步
	*	需求分析
	*		忘记密码分为几步
	*			1、填写账号信息，并做相应的验证
	*			2、用户选择是邮箱验证还是手机验证
	*			3、发送验证码，并验证验证码
	*			4、修改原有的密码
	*	流程分析
	*		1、判断用户是否已经登录
	*		2、显示第一步的页面
	**/
    public function index(){
		session("forgetPassword_id",null);
		session('forget_register_code',null);
		session('forgetPassword_type',null);
		//判断用户是否登录
		/*if(session('home_member_id')){
			header("location:".U("/User"));
		}else{*/
			$this->display('index');
		//}
    }
	/**
	*忘记密码第二步
	*	需求分析
	*		验证用户的信息,并显示用户的信息
	*	流程分析
	*		1、接收用户传过来的账号信息
	*		2、验证用户的信息，包括用户是否存在和用户的验证码是否正确
	*			首先验证的是用户的信息是否存在，验证验证码是否存在
	*		3、将用户的id保存在密码验证的session中,以便后面进行验证
	**/
	public function secound(){
		if(IS_POST){
			//接收用户传递过来的账号信息
			$username	= I('post.username');//接收用户的账号，缺少一个过滤的
			$code		= I('code');//接收用户传过来的验证码
			
			$type = intval(I('get.type'));
			//验证用户信息是否存在
			if(!empty($username)){
				//查询对应的账号信息
				$map['username|telephone']	= $username;
				$info	= 	get_info($this->table,$map);
				if(!$info or $info["type"]<1 or $info["status"]!=1){
					$this->error('账号未注册或已被禁用删除!');
				}
			}else{
				$this->error('请填写账号！！');
			}
			//验证验证码是否正确
			$verify = new \Think\Verify();
			$res = $verify->check($code, 2);
			if(!$res){
				$this->error('验证码错误！！');
			}
			//将用户的信息保存到session中
			session('forgetPassword_id',$info['id']);
			session('forgetPassword_type',$type);
			//将用户的信息显示到页面
			$data['info']	= $info;
			//如果验证通过就进入第二步
			
			//@赵群@添加验证方式直接为手机验证
			$member_id	=	session('forgetPassword_id');
			$data['type'] = 1;
			$map['id']	= $member_id;
			$member_info = get_info($this->table,$map);
			$data['info'] = $member_info;
			
			$this->assign($data);
// 			$this->display('index2');
			$this->display('index3');
		}else{
			 if(session('forgetPassword_id')){	
				//根据session查询用户的信息
				$member_id	=	session('forgetPassword_id');
				
				$map['id']	= $member_id;
				$member_info = get_info($this->table,$map);
				
				$data['info'] = $member_info;
				
				$this->assign($data);
				$this->display('index2');
			 }else{
			 	$this->redirect('User/Login/index');
			 }	
		}
		
	}
	/**
	*找回密码第三步
	*	需求分析
	*		需要将用户的账号信息查询出来用户的信息,根据用户的选择进行验证
	*	流程分析
	*		1、显示用户的信息等
	**/
	public function third(){
		if(IS_POST){
			$code = I('code');
			if(empty(session('forget_register_code'))){
				$this->error('请获取验证码！');
			}
			if(empty($code)){
				$this->error('验证码不能为空！');
			}
			//验证验证码是否正确
			if($code!=session('forget_register_code')){
				$this->error('验证码不正确！');
			}
			//根据session查询用户的信息
			$member_id	=	session('forgetPassword_id');
			$map['id']	= $member_id;
			$member_info = get_info($this->table,$map);
			$data['info'] = $member_info;
				
			$this->assign($data);
			$this->display('index4');
		}else{
			if(session('forgetPassword_id')){
				//验证的方式
				$type = I('type');
				$data['type'] = $type;
				//根据session查询用户的信息
				$member_id	=	session('forgetPassword_id');
				$map['id']	= $member_id;
				$member_info = get_info($this->table,$map);
				$data['info'] = $member_info;
				
				$this->assign($data);
				$this->display('index3');
			}else{
				$this->redirect('User/Login/index');
			}
		}
	}
	public function fourth(){
		if(session('forgetPassword_id')){
			//接收用户的密码
			$password = trim(I('password'));
			$confirmPassword = trim(I('confirmPassword'));
			//验证两个密码相同
			if($password!=$confirmPassword){
				$this->error('两次密码不一致！');
			}
			//验证不能为空
			$rules = array(
				array('password','require','请输入密码！'), //默认情况下用正则进行验证
			);
			//修改密码
			$member_id = session('forgetPassword_id');
			$_POST['id'] = $member_id;
			if(session("forgetPassword_type")==2){
			  unset($_POST["password"]);	
			  $_POST['deal_password'] = md5(md5($password));
			}else{
			  $_POST['password'] = md5(md5($password));
			}
			$result	= update_data($this->table);
			
			if(is_numeric($result)){
				//将session清空
				$forgetPassword_type=session('forgetPassword_type');
				session('forgetPassword_id',null);
				session('forget_register_code',null);
				session('forgetPassword_type',null);
				if($forgetPassword_type==2){
				   $this->success('交易密码修改成功！',U('User/Index/index'));
				}else{
				   $this->success('登录密码修改成功！',U('User/Login/index'));
				}
			}
		}else{
			$this->redirect('User/Login/index');
		}
	}
	//验证码的获取
	public function verify(){
		$config =    array(    
			'fontSize'    =>    80,    // 验证码字体大小    
			'length'      =>    4,     // 验证码位数    
		);
		$verify = new \Think\Verify($config);	
		$verify->entry(2);
	}
	/*
     * 注册发送验证码
     * */
    function registerCode(){
    	//$account=trim(I('post.account'));
		$member_id = session('forgetPassword_id');
		$info = get_info($this->table,array('id'=>$member_id));
		//根据type查询account
		//if($account==1){
			$account = $info['telephone'];
		//}else if($account==2){
			//$account = $info['email'];
		//}
    	$code=rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    	session('forget_register_code',$code);
    	//如果是手机号码
    	if(preg_match('/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/', $account)){
    		//$content="您在N邦翻译上注册验证码为：".$code."，如非本人操作，无需理会【N邦翻译】";
    		$content=$code;
    		$status=sms($account, $content);
    		if($status<0){
    			$this->error('验证短信发送失败，请联系客服');
    		}else{
    			$this->success('验证短信已发送，请查看');
    		}
        }else if (preg_match( "/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/", $account ) ){
    		//如果是邮箱
    		$subject="来自N邦翻译";
    		$body="尊敬的N邦翻译用户，您好!<hr/>您的验证码:$code<br/>请在页面注册以完成认证";
    		$status=think_send_mail($account,$account,$subject,$body);
			$this->ajaxReturn(array('info'=>$status));
    		if($status==1){
    			$this->success('验证邮件已发送，请登录查看');
    		}else{
    			$this->error('验证邮件发送失败，请联系客服');
    		}
    	}else{
    		session('forget_register_code',null);
    		$this->error('请输入正确的手机号码！'.$account);
    	}
    }
    
    /*
     * 手机找回密码验证并修改密码
     * @author 龚双喜
     * @date   2015-07-23
     * */
    public function appForgetPassword(){
    	 
    	if(IS_POST){
    		//参数接收
    		$apptype = (!empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY')) ?true:false;//手机app接口密钥
    		$telephone = I('account');
    		if($apptype){
    			$ajax = true;
    			$_POST["id"] = intval(I('registerid'));
    			$_POST["code"] = I('code');
    		}else{
    			$ajax = false;
    			$_POST["id"] = session("registerid");
    			$_POST["code"] = session("code");
    		}
    		$password = trim(I('password'));
    		$repassword = trim(I('repassword'));
    
    		$_POST["vpassword"]=$password;//传递过来的密码
    		$_POST["password"]=md5(md5($password));//存入数据库的密码
    
    		$rules=array(
    				array("vpassword","require","密码不能为空",1),
    				array("vpassword","6,15","密码必须为6-15位字符",1,'length'),
    				array("repassword","require","确认密码不能为空",1),
    				array("repassword",'vpassword','密码与确认密码不一致',0,'confirm'),
    				array("code","require","验证码不能为空",1),
    				//array("telephone","/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/",'手机号码格式不正确',1,'regex'),
    		);
    
    		$info=get_info($this->table,array('id'=>$_POST["id"],'status'=>1,'type'=>array('GT',-2)));
    		 
    		if(!$info){
    			$this->error("您的手机号码未注册！",'',$ajax);
    		}else{
    			if($info["telephone"]!=$telephone){
    				$this->error("手机号码输入错误！",'',$ajax);
    			}
    			if($info["code"]!=$_POST["code"]){
    				$this->error("验证码输入错误！",'',$ajax);
    			}
    		}
    
    		$result = update_data($this->table,$rules);
    		if(is_numeric($result)){
    				
    			$this->success("密码修改成功！",'',$ajax);
    			 
    		}else{
    			 
    			$this->error($result,'',$ajax);
    		}
    
    
    	}else{
    
    		$this->error("非法操作");
    	}
    }
    /*
     * 手机找回密码发送验证码
     * @author 龚双喜
     * @date   2015-07-23
     * */
    public function forgetCode(){
    
    	//接收传入的参数
    	$apptype = (!empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY')) ?true:false;//手机app接口密钥
    	$account=trim(I('post.account'));//手机号码/邮箱
    
    	//手机返回json数据
    	if($apptype){
    		$ajax=true;
    	}else{
    		$ajax=false;
    	}
    
    	$code=rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);//验证码
    	// session('register_code',$code);
    	 
    	//如果是手机号码
    	if(preg_match('/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/', $account)){
    
    		$info=get_info('member',array('telephone'=>$account,'status'=>1,'type'=>array('GT',-2)));
    
    		if(!info){
    
    			$this->error("您的手机还未注册",'',$ajax);
    		}
    
    		$content="您在N邦翻译上注册验证码为：".$code."，如非本人操作，无需理会【N邦翻译】";
    		//判断手机号码是否注册了如果注册了就返回一个用户手机号码已经注册
    		 
    		$status=sms($account, $content);
    
    		if($status<0){
    			$this->error('验证短信发送失败，请联系客服','',$ajax);
    		}else{
    			$_POST['code'] = $code;
    			$_POST['id'] = $info['id'];
    			update_data($this->table);
    			session('registerId',$info['id']);//原有的id
    			$data["registerId"]=$info['id'];//手机
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
    		$this->error('请输入正确的手机号码!','',$ajax);
    	}
    }
}