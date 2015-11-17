<?php
namespace Common\Model;
use Think\Model\ViewModel;
class CommentProductViewModel extends ViewModel {
	public $viewFields = array(
		'comment'=>array('*','_type'=>'LEFT'),
		'products'=>array('language_id','to_language_id','type'=>'product_type','_on'=>'products.id=comment.product_id'),
	);
}
