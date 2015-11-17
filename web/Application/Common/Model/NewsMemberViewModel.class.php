<?php
namespace Common\Model;
use Think\Model\ViewModel;
class NewsMemberViewModel extends ViewModel {
	public $viewFields = array(
		'article'=>array('*','id'=>'article_id','status'=>'article_status','add_time'=>'article_add_time','_type'=>'LEFT'),
		'member'=>array('username','_on'=>'article.member_id=member.id'),
	);
}
