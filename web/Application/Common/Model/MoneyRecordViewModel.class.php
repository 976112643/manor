<?php
namespace Common\Model;
use Think\Model\ViewModel;
class MoneyRecordViewModel extends ViewModel {
	public $viewFields = array(
		'money_record'=>array('*','description'=>'money_record_description','_type'=>'LEFT'),
		'member'	=>array('*','_on'=>'money_record.member_id=member.id'),
	);
}
