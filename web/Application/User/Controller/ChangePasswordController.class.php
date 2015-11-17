<?php
namespace User\Controller;
use User\Controller\BaseController;
class ChangePasswordController extends BaseController {
	private $table = 'member';
	/**
	*用户修改密码
	*	用户的密码分为两种密码（最好是考虑两种）
	*流程分析
	*	1、判断用户是否登录
	*	2、判断是修改那种密码
	*	3、判断用户的原密码是否正确
	*	4、判断用户密码与确认密码是否正确
	**/
    public function index(){
    	//手机app接口密钥
//    	$apptype = (!empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY'))?true:false;//手机app接口标识参数
//    	if($apptype){
//           $app_key=trim(I("post.key"));//md5加密的登录时间
//	       $member_id=I("post.home_member_id");	
//	       $this->isLoginExpire($app_key,$member_id);//判断登录过期
//	       $getdp=intval(I("post.getdp"));//获取是否存在交易密码
//           $ajax=true;	
//    	}else{
//    	   $member_id=session('home_member_id');
//    	   $ajax=false;
//    	}
    	$member_id=session('home_member_id');
    	if($this->apptype==1){
    		$getdp=intval(I("post.getdp"));//获取是否存在交易密码
    	}
		$info = get_info($this->table,array('id'=>$member_id));
		$dealPassword = !empty($info['deal_password']);
		if($getdp==1){
			//goto dealpassword;
			if($dealPassword){//表示交易密码存在
				$data['dealPassword'] = "1";
			}else{
				$data['dealPassword'] = "2";//表示交易密码不存在
			}
			$this->ajaxReturn($data);
			exit;
		}
		
		if(IS_POST){
			$oldPassword = md5(md5(I('oldPassword')));//两次md5加密
			$password  = md5(md5(trim(I('password'))));//新密码
			$confirmPassword = md5(md5(I('confirmPassword')));//确认密码
			//判断用户修改的是那种密码
			$type = I('type');
			if($type ==0){
				$field = 'password';
				//验证用户的原始密码是否正确
				if($info[$field] != $oldPassword){
					$tips_msg=array('status'=>0,'info'=>'原密码错误！！');
					$this->appOut($tips_msg,$this->apptype,0);
					//$this->error('原密码错误！！','',$ajax);
				}
			}else if($type==1){
				$field = 'deal_password';
				//验证用户的原始密码是否正确
				if($info[$field] != $oldPassword && $dealPassword){
					$tips_msg=array('status'=>0,'info'=>'原密码错误！！');
					$this->appOut($tips_msg,$this->apptype,0);
					//$this->error('原密码错误！！','',$ajax);
				}
			}
			if(!$dealPassword){
				$text = '设置成功！';
			}else{
				$text = '修改成功！';
			}
			//判断确认密码是否正确
			if($password != $confirmPassword){
				$tips_msg=array('status'=>0,'info'=>'两次密码不一致！！');
				$this->appOut($tips_msg,$this->apptype,0);
				//$this->error('两次密码不一致！！','',$ajax);
			}
			$vpassword=trim(I('password'));
			if($oldPassword==$password){
				$tips_msg=array('status'=>0,'info'=>'新密码和旧密码一致，请重新输入！');
				$this->appOut($tips_msg,$this->apptype,0);
			}
			unset($_POST);
			$_POST["vpassword"]=$vpassword;//传递过来的密码
			$rules=array(
					array("vpassword","require","密码不能为空",1),
					array("vpassword","6,15","密码必须为6-15位字符",1,'length'),
			);
			//修改用户的密码
			$_POST['id'] = $member_id;
			$_POST[$field] = $password;
			
			$result = update_data($this->table,$rules);
			
			if(is_numeric($result)){
				$tips_msg=array('status'=>0,'info'=>$text,'url'=>U('User/ChangePassword/index'));
				$this->appOut($tips_msg,$this->apptype,0);
				//$this->success($text,U('User/ChangePassword/index'),$ajax);
			}else{
				$tips_msg=array('status'=>0,'info'=>$result,'url'=>U('User/ChangePassword/index'));
				$this->appOut($tips_msg,$this->apptype,0);
				//$this->error($result,U('User/ChangePassword/index'),$ajax);
			}
		}else{
			//查询用户是否设置过交易密码
			if($dealPassword){//表示交易密码存在
				$data['dealPassword'] = 1;
			}else{
				$data['dealPassword'] = 2;//表示交易密码不存在
			}
			$this->assign($data);
			$this->display();
		}
		
//		dealpassword:
//		if($getdp==1){
//			//查询用户是否设置过交易密码
//			if($dealPassword){//表示交易密码存在
//				$data['dealPassword'] = "1";
//			}else{
//				$data['dealPassword'] = "2";//表示交易密码不存在
//			}
//			$this->ajaxReturn($data);
//		}
    }
    
    //用Ajax验证用户输入的旧密码是否正确
    function checkForm(){
    	if(IS_POST){
    		$posts = I('post.');
    		if ($posts){
    			$member_id = session('home_member_id');
    			$oldPassword = md5(md5($posts['param']));
				$map = array(
					'id'=>$member_id,
					'password'=>$oldPassword,
					'status'=>array('gt',-1),
					'type'=>array('gt',-2),
				);
    			$info = get_info('member',$map);
    			if($info){
    				$data['info'] = '旧密码输入正确';
    				$data['status'] = 'y';
    				$this->ajaxReturn($data);
    			}else{
    				$this->error('旧密码输入错误！');
    			}
    		}
    	}
    }

    
}