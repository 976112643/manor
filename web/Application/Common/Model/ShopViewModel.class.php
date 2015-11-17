<?php
namespace Common\Model;
use Think\Model\ViewModel;
class ShopViewModel extends ViewModel {
	public $viewFields = array(
		'shop'=>array('*','id'=>'shop_id','status'=>'shop_status','add_time'=>'shop_add_time','_type'=>'LEFT'),
		'member'=>array('username','_on'=>'shop.member_id=member.id','id'=>'seller_id'),
	);
}
