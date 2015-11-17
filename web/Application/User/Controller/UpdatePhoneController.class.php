<?php
namespace User\Controller;
use User\Controller\BaseController;
class UpdatePhoneController extends BaseController{
	public $table	=	'member';
	/**
	*用户修改头像
	*	用户在个人中心可以修改自己的头像
	*流程分析
	*	1、接收用户的号码和验证码
	*	2、验证验证码
	*		验证码要和提交的手机一致
	*	3、修改用户的手机号码
	**/
	public function index(){
		//手机app参数
	    $apptype = (!empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY')) ?true:false;//手机app接口密钥
		
		//手机app与电脑客户端参数区分
		if($apptype){
		  $app_key=trim(I("post.key"));//md5加密的登录时间
	      $home_member_id=I("post.home_member_id");	
	      $this->isLoginExpire($app_key,$member_id);//判断登录过期
		  $ajax=true;
		  $update_phone=trim(I('update_phone'));
		  $update_code=trim(I('update_code'));
		}else{
		  $ajax=false;
		  $home_member_id=session('home_member_id');
		  $update_phone=session('update_phone');
		  $update_code=session('update_code');
		}
		//获取用户信息
		$member_info = get_info($this->table,array('id'=>$home_member_id));
		if(!$member_info){
			$this->error("未获取到您的信息",'',$ajax);
		}
		if(IS_POST){
			
			//接收用户的手机号码
			$member_telephone = trim(I('newPhone'));
			$code = trim(I('code'));
			//验证号码是否做了修改
			
			if($member_info['telephone']==$member_telephone){
				$this->error('您的手机未作修改！！','',$ajax);
			}
			//验证验证码的正确性
			if($member_telephone!=$update_phone || $code!=$update_code){
				$this->error('您的验证码有误！','',$ajax);
			}
			unset($_POST);
			///dump($code);
			$_POST['id'] = intval($home_member_id);
			$_POST['telephone'] = $member_telephone;
			$result = update_data($this->table);
			if(is_numeric($result)){
				$this->success('修改成功！',U('User/UpdatePhone/index'),$ajax);
			}else{
				$this->error('修改失败,请联系客服！',U('User/UpdatePhone/index'),$ajax);
			}
		}else{
			$data['member_info'] = $member_info;
			$this->assign($data);
			$this->display();
		}
	}
	/*
     * 注册发送验证码
     * */
    function registerCode(){
    	$apptype = (!empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY')) ?true:false;//手机app接口密钥
    	if($apptype){
    	  $app_key=trim(I("post.key"));//md5加密的登录时间
	      $member_id=I("post.home_member_id");	
	      $this->isLoginExpire($app_key,$member_id);//判断登录过期
    	}
    	$account=trim(I('post.account'));
    	$code=rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
    	session('update_code',$code);
		session('update_phone',$account);
    	
    	//如果是手机号码
    	if(preg_match('/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/', $account)){
    		//$content="您在N邦翻译上修改手机验证码为：".$code."，如非本人操作，无需理会【N邦翻译】";
    		$content=$code;
    		$status=sms($account, $content);
    		if($status<0){
    			$this->ajaxReturn(array('status'=>0,'info'=>'短信发送失败，请联系客服人员！'));
    		}else{
    			if($apptype){
    				$this->ajaxReturn(array('status'=>1,'info'=>'验证短信已发送，请查看','update_code'=>$code,'update_phone'=>$account));
    			}else{
    			    $this->ajaxReturn(array('status'=>1,'info'=>'验证短信已发送，请查看'));
    			}
    		}
    	}else{
    		$this->ajaxReturn(array('status'=>0,'info'=>'请输入正确的手机号码!'));
    	}
    }
    
    //用Ajax验证用户输入的手机号是否正确
    function checkForm(){
    	if(IS_POST){
    		$posts = I('post.');
    		if ($posts){
//     			$member_id = session('home_member_id');
    			$newPhone = $posts['param'];
    			$map = array(
//     					'id'=>$member_id,
    					'telephone'=>$newPhone,
    					'status'=>array('gt',-1),
    					'type'=>array('gt',-2),
    			);
    			$info = get_info('member',$map);
    			if($info){
    				$this->error('该手机号已存在！');
    			}else{
    				$data['info'] = '该手机号有效！';
    				$data['status'] = 'y';
    				$this->ajaxReturn($data);
    			}
    		}
    	}
    }
    
}