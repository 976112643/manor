<?php
namespace Common\Model;
use Think\Model\ViewModel;
class ProductsViewModel extends ViewModel {
	public $viewFields = array(
		'products'=>array('*','id'=>'product_id','title'=>'product_title','status'=>'product_status','add_time'=>'product_add_time','short_description'=>'product_short_description','description'=>'product_description','_type'=>'LEFT'),
		'shop'	=>array('title','_on'=>'products.shop_id=shop.id','title'=>'shop_title'),
	);
}
