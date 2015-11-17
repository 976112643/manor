<?php
namespace Common\Model;
use Think\Model\ViewModel;
class MemberShopViewModel extends ViewModel {
	public $viewFields = array(
		'shop'=>array('*','id'=>'shop_id','status'=>'shop_status','_type'=>'LEFT'),
		'member'	=>array('*','username'=>'member_username','_on'=>'shop.member_id=member.id','_type'=>'LEFT'),
	);
}
