<?php
namespace Backend\Model;
use Think\Model\ViewModel;
class MoneyViewModel extends ViewModel {
	public $viewFields = array(
	    'money_record'=>array('*','id'=>'record_id','status'=>'record_status','description'=>'money_record_description','_type'=>'LEFT'),
		'member'	=>array('*','_on'=>'money_record.member_id=member.id','_type'=>'LEFT'),
		'bank_cards'	=>array('*','_on'=>'bank_cards.id=money_record.cards_id','_type'=>'LEFT'),
		'bank'	=>array('*','_on'=>'bank.id=bank_cards.bank_id'),
	);
}
