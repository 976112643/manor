<?php
namespace Common\Model;
use Think\Model\ViewModel;
class ShoplistViewModel extends ViewModel {
	public $viewFields = array(
			'shop'=>array('*','id'=>'shop_id','status'=>'shop_status','add_time'=>'shop_add_time','_type'=>'INNER'),
			'products'=>array('shop_id','_on'=>'shop.id=products.shop_id','shop_id'=>'product_shop_id',),
	);
}
