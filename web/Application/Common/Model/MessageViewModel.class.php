<?php
namespace Common\Model;
use Think\Model\ViewModel;
class MessageViewModel extends ViewModel {
	public $viewFields = array(
		'message'=>array('*','id'=>'message_id','title'=>'message_title','status'=>'message_status','add_time'=>'message_add_time','_type'=>'LEFT'),
		'member'=>array('username','_on'=>'message.from_member_id=member_from.id','_as'=>'member_from','username'=>'from_username','_type'=>'LEFT'),
		'member2'=>array('username','_table'=>'__MEMBER__','_on'=>'message.to_member_id=member_to.id','_as'=>'member_to','username'=>'to_username'),
	);
}
