<?php
namespace Backend\Controller\Base;
use Think\Controller;
class PublicController extends Controller {

	public function login(){
		if(IS_POST){
			$code=I('post.code');
			$code_check=$this->check_verify($code);
			if($code_check!=1){
				$this->error('验证码不正确');
			}
			$username=I('post.username');
			$password=I('post.password');
			$password=md5(md5($password));
			$info=get_info(D('Common/MemberView'),array('username'=>$username,'password'=>$password));
			if($info['is_admin']!=1){
				$this->error("管理员帐号不存在");
			}
			if($info['member_status']!=1){
				$this->error("管理员帐号已被禁用或删除");
			}
			if($info){
				if($info['member_rules']){
					$info['rules'].=','.$info['member_rules'];
				}
				$info['rules']=implode(',',array_unique(explode(',',$info['rules'])));
				
				session('rules',$info['rules']);
				session('username',$info['username']);
				session('member_id',$info['member_id']);
				action_log('member',$info['member_id'],'id,username as title');
				$this->success('登录成功',__ROOT__."/Backend");
			}else{
				$this->error('用户名或密码错误');
			}
		}else{
			header("location:".__ROOT__."/Backend");
		}
	}
	
	/**
	 * 验证码
	 */
	public function verify(){
		$config=array(
			'imageW'	=>'130',
			'imageH'	=>'35',
			'fontSize'	=>'18',
			'length'	=>4
		);
		$verify = new \Think\Verify($config);
		$verify->entry(1);
	}
	
	// 检测输入的验证码是否正确，$code为用户输入的验证码字符串
	function check_verify($code, $id = '1'){
		$verify = new \Think\Verify();
		return $verify->check($code, $id);
	}
	/*
	 * 退出登录
	 * @time 2014-12-25
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	function logout(){
		session(null); // 清空当前的session
		$this->success('退出成功', U('backend/base/public/login'));
	}
}