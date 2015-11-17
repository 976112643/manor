<?php
namespace Common\Model;
use Think\Model\ViewModel;
class ShopLevelViewModel extends ViewModel {
	public $viewFields = array(
		'shop'=>array('*','id'=>'shop_id','status'=>'shop_status','add_time'=>'shop_add_time','_type'=>'LEFT'),
		'level'=>array('title'=>'level_title','_on'=>'shop.level=level.id'),
	);
}
