<?php
namespace Common\Model;
use Think\Model\ViewModel;
class IntegrationViewModel extends ViewModel {
	public $viewFields = array(
		'integration'=>array('*','description'=>'integration_description','type'=>'integration_type','_type'=>'LEFT'),
		'member'	=>array('*','_on'=>'integration.member_id=member.id'),
	);
}
