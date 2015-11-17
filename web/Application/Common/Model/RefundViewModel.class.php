<?php
namespace Common\Model;
use Think\Model\ViewModel;
class RefundViewModel extends ViewModel {
	public $viewFields = array(
		'refund'=>array('*','id'=>'refund_id','description'=>'refund_description','add_time'=>'refund_add_time','_type'=>'LEFT'),
		'member'=>array('username','_on'=>'refund.member_id=member.id','_type'=>'LEFT',),
		'shop'	=>array('title','_on'=>'refund.shop_id=shop.id','title'=>'shop_title',),		
	);
}
