<?php
namespace Common\Model;
use Think\Model\ViewModel;
class ShopMemberViewModel extends ViewModel {
	public $viewFields = array(
		'member'=>array('*','id'=>'member_id','_type'=>'LEFT'),
		'shop'	=>array('id','id'=>'shop_id','status'=>'shop_status','translate_type','type'=>'shop_type','_on'=>'member.id=shop.member_id')
	);
}
