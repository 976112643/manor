<?php
namespace Backend\Model;
use Think\Model\ViewModel;
class CommentViewModel extends ViewModel {
	public $viewFields = array(
	    'comment'		=>array('*','_type'=>'left'),
	    'article'	=>array('title','_on'=>'article.id=comment.product_id','_type'=>'left'),
		'member'=>array('username','_on'=>'member.id=comment.member_id'),
	);
}
