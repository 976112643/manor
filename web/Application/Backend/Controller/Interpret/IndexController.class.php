<?php
namespace Backend\Controller\Interpret;
use Backend\Controller\Base\AdminController;
class IndexController extends AdminController {
	protected $table='interpret';
	protected $model='InterpretView';
	protected $limit=15;		
	protected $action_filed='id,telephone as title';
	public function index(){
		$title=I('title');
		if(!empty($title)){
		
			$map['telephone']=array('like','%'.$title.'%');
			$data['title']=$title;
		}
		$map['status']=array('gt',-1);
		
		$list=$this->page($this->table,$map,'id desc,status desc');
    	/*获取语言信息*/
    	$language = get_language_cache();
    	foreach ($language as $key=>$val){
    		$language_arr[$val['id']]=$val['title'];
    	}
    	$list=int_to_string($list,array('status'=>array(0=>'未处理',1=>'已处理'),'language_id'=>$language_arr,'to_language_id'=>$language_arr,'type'=>array(1=>'个人译者',2=>'商家译者',3=>'商家或个人')));
		$data['list']=$list;
    	$this->assign($data);
		$this->display();
	}
	
}
