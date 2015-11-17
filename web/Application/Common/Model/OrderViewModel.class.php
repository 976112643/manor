<?php
namespace Common\Model;
use Think\Model\ViewModel;
class OrderViewModel extends ViewModel {
	public $viewFields = array(
		'orders'=>array('*','id'=>'order_id','status'=>'order_status','add_time'=>'order_add_time','type'=>'order_type','_type'=>'LEFT'),
		'member'	=>array('username','telephone','_on'=>'orders.member_id=member.id','_type'=>'LEFT',),
		'shop'	=>array('title','_on'=>'orders.shop_id=shop.id','title'=>'shop_title','_type'=>'LEFT',),
		'products'	=>array('title','_on'=>'orders.product_id=products.id','type'=>'product_type','title'=>'product_title','price'=>'product_price','language_id'=>'product_language_id','to_language_id'=>'product_to_language_id','industry_id'=>'product_industry_id','ability_id'=>'product_ability_id'),
		
	);
}
