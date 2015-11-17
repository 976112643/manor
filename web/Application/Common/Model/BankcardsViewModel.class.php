<?php
namespace Common\Model;
use Think\Model\ViewModel;
class BankcardsViewModel extends ViewModel {
	public $viewFields = array(
		'bank_cards'	=>array('*','_type'=>'LEFT',),
		'bank'	=>array('title','_on'=>'bank.id=bank_cards.bank_id','_type'=>'LEFT',),		
	);
}
