<?php
namespace Common\Model;
use Think\Model\ViewModel;
class FilesViewModel extends ViewModel {
	public $viewFields = array(
		'files'=>array('*','id'=>'files_id','title'=>'files_title','status'=>'files_status','add_time'=>'files_add_time','_type'=>'LEFT'),
		'member'	=>array('username','_on'=>'files.member_id=member.id','_type'=>'LEFT',),
		'shop'	=>array('title','_on'=>'files.shop_id=shop.id','title'=>'shop_title','_type'=>'LEFT',),		
	);
}
