<?php
namespace Backend\Controller\Demo;
use Backend\Controller\Base\AdminController;
class IndexController extends AdminController {
	public function index(){
		$this->display();
	}
	public function down(){
		$http=new \Org\Net\Http();
		$http->download('./Public/Static/fonts/fontawesome-webfont.ttf');
	}
	public function del(){
		$this->success("删除成功");
	}
}