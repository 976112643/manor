<?php
namespace Backend\Controller\Base;
use Backend\Controller\Base\AdminController;
class ActionLogController extends AdminController {
	protected $table='action_log';
	
	/*
	 * 菜单列表页
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	public function index(){
		$map['status']=1;
		$keywords=I('keywords');
		if($keywords){
			$map['log_title|username']=array('like', "%$keywords%");
		}
		$result=$this->page(D('Common/ActionLogView'), $map,'id desc');
		
		$result=int_to_string($result);
		$data['result']=$result;
		
		$this->assign($data);
		$this->display();
	}
	
	/*
	 * 删除数据库中的数据，如果是删除数据到回收站，不需要此函数
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	function del(){
		$ids=I('ids');
		if(is_array($ids)){
			$map['id']=array('in',$ids);
			$result=delete_data($this->table,$map);
			$ids=implode(',', $ids);
			if($result){
				//action_log($this->table,$ids);
				$this->success('操作成功',U('index'));
			}else{
				$this->error($result);
			}
		}else{
			$ids=intval($ids);
			$map['id']=$ids;
			$result=delete_data($this->table,$map);
			if($result){
				//action_log($this->table,$ids);
				$this->success('操作成功',U('index'));
			}else{
				$this->error($result);
			}
		}
	}
	
}