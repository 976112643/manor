<?php
namespace Backend\Model;
use Think\Model\ViewModel;
class ArticleViewModel extends ViewModel {
	public $viewFields = array(
	    'article'	=>array('*','_type'=>'left'),
		'category'	=>array('title'=>'category','_on'=>'category.id=article.category_id','_type'=>'left'),
		'member'=>array('username','_on'=>'member.id=article.member_id','_type'=>'left'),
		'umember'=>array('_table'=>'sr_member','username'=>'update_username','_on'=>'umember.id=article.update_member_id'),
	);
}
