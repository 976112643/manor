<?php
namespace User\Controller;
use Home\Controller\HomeController;
class BaseController extends HomeController{
	protected $apptype;
	/*
	 * 用户中心基础控制器
	 * 除了登录注册找回密码等控制器不需要继承该控制器外，
	 * 其它User模块下的控制器都需要继承该控制器
	 * @time 2015-05-13
	 * @author	康利民  <3027788306@qq.com>
	 * */
	protected function __init(){
		$app=$this->appParam();
		if($app==0){
			echo json_encode(array('info'=>'请登录','status'=>0));
			exit;
		}else if($app==1){
			$this->apptype=1;
		}
		if(!session('home_member_id')){
			if($app==-1){
				$this->apptype=0;
				$this->redirect('User/Login/index');
			}else{
				echo json_encode(array('info'=>'请登录','status'=>0));
				exit;
			}
			
		}
	}



	/*
	 * 发送验证码
	 * 根据提交过来的内容进行正则验证判断是邮箱还是手机
	 * 然后发送验证码
	 * $account 	string 	邮箱/手机号码
	 * @time 2015-05-13
	 * @author	康利民  <3027788306@qq.com>
	 * */
	public function getCode(){
    	$account=trim(I('post.account'));
    	if($account==''){
    		$this->error('请输入手机号码/邮箱！');
    	}
		$code=rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		
		
		//如果是手机号码
		if(preg_match('/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/', $account)){
			$content=C('SITE_TITLE')."验证码：".$code."，如非本人操作，无需理会";
			$status=sms($account, $content);
			if($status<0){
				$this->error('验证短信发送失败，请联系客服');
			}else{
				session('account_type','phone');
				session('get_code',$code);
				session('get_account',$account);
				$this->success('验证短信已发送，请注意查收');
			}
		}else if ( preg_match( '/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $account ) ){
			//如果是邮箱
			$subject=C('SITE_TITLE')."验证码邮件";
			$body="尊敬的".C('SITE_TITLE')."用户，您好：<br />您的验证码为:".$code."，如非本人操作，无需理会";
			$status=think_send_mail($account,$account,$subject,$body);
			if($status==1){
				session('account_type','email');
				session('get_code',$code);
				session('get_account',$account);
				$this->success('验证邮件已发送，请登录查看');
			}else{
				$this->error('验证邮件发送失败，请联系客服');
			}
		}else{
			session('get_account',null);
			session('get_code',null);
			$this->error('请输入正确的手机号码/邮箱！');
		}
	}
}