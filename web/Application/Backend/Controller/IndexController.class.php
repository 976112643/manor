<?php
namespace Backend\Controller;
use Think\Controller;
class IndexController extends Controller {
	public function index(){
		if(session('member_id')){
			$_init=A("Backend/Base/Admin");
			$_init->_initialize;
			$this->meta_title = '首页';
			$map=array('level'=>1,'status'=>1);
			//$map['id']=array('in','权限');//出错了,可能就需要把权限放进去
			$info=get_info('menu',$map,'url');
			$this->redirect($info['url']);
		} else {
			$this->display();
		}
	}
}