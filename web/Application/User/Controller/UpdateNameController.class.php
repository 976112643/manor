<?php
namespace User\Controller;
use User\Controller\BaseController;
class UpdateNameController extends BaseController{
	public $table	=	'member';
	/**
	*用户修改昵称
	*	用户在个人中心可以修改自己的昵称
	*流程分析
	*	判断用户新昵称和旧昵称是否一致
	*	将新昵称写入数据库
	**/
	public function index(){
		$home_member_id=session('home_member_id');
		$member_info = get_info($this->table,array('id'=>$home_member_id));
		if(IS_POST){
			$nickname=I('nickname');
			if ($member_info['nickname']==$nickname){
				$this->error('您的昵称未作修改！');
			}
			unset($_POST);
			$_POST['id'] = session('home_member_id');
			$_POST['nickname']=$nickname;
			$result = update_data($this->table);
			if(is_numeric($result)){
				session('nickname',$_POST['nickname']);//更新缓存
				$this->success('修改成功！',U('User/UpdateName/index'),$ajax);
			}else{
				$this->error('修改失败,请联系客服！',U('User/UpdateName/index'),$ajax);
			}
			
		}else{
			$data['member_info']=$member_info;
			$this->assign($data);
			$this->display();
		}	
	}
}