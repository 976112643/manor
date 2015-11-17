<?php
namespace Common\Model;
use Think\Model\ViewModel;
class ActionLogViewModel extends ViewModel {
	public $viewFields = array(
		'action_log'		=>array('*','url'=>'action_url','_type'=>'LEFT'),
		'menu'	=>array('log_title','_on'=>'action_log.url=menu.url'),
	);
}
