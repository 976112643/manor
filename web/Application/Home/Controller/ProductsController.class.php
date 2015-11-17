<?php
namespace Home\Controller;
use Common\Controller\CommonController;
class ProductsController extends HomeController{
	protected $table = 'products';
	
	
	
	
	public function index(){
		$this->display();
	}
}