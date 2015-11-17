<?php
namespace Common\Model;
use Think\Model\ViewModel;
class MemberViewModel extends ViewModel {
	public $viewFields = array(
		'member'=>array('*','id'=>'member_id','status'=>'member_status','rules'=>'member_rules','add_time'=>'member_add_time','_type'=>'LEFT'),
		'role'	=>array('*','_on'=>'member.role_id=role.id'),
	);
}
