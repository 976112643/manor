<?php
namespace Common\Model;
use Think\Model\ViewModel;
class HelpCategoryViewModel extends ViewModel {
	public $viewFields = array(
		'article'=>array('*','title'=>'article_title','type'=>'articl_type','_type'=>'LEFT'),
		'category'	=>array('*','title'=>'category_title','_on'=>'article.category_id=category.id','_type'=>'LEFT')
	);
}
