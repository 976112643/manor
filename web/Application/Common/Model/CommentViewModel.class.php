<?php
namespace Common\Model;
use Think\Model\ViewModel;
class CommentViewModel extends ViewModel {
	public $viewFields = array(
		'comment'=>array('*','id'=>'comment_id','status'=>'comment_status','type'=>'comment_type','add_time'=>'comment_add_time','_type'=>'LEFT'),
		'member'=>array('telephone','_on'=>'comment.member_id=member.id','_type'=>'LEFT'),
		'orders'=>array('*','id'=>'order_id','status'=>'order_status','_on'=>'comment.order_id=orders.id'),
		
	);
}
