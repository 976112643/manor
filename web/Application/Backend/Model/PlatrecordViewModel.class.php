<?php
namespace Backend\Model;
use Think\Model\ViewModel;
class PlatrecordViewModel extends ViewModel {
	public $viewFields = array(
	    'funds_detail'		=>array('*','id'=>'funds_counts','_type'=>'left'),
		'member'	=>array('username','_on'=>'member.id=funds_detail.from_member_id'),
	);
}
