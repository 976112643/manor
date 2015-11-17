<?php
namespace Backend\Controller\Base;
use Common\Controller\CommonController;
class AdminController extends CommonController {
	protected $table,$cache_data,$cache_name,$recommend_cache;
	protected $action_filed='id,title';
	protected $session_cache_name='';
	
	/*
	 * 全局加载
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	protected function __autoload(){
		$this->__init();
	}
	protected function __init(){
		if(!session('member_id')){
			header("location:".__ROOT__."/Backend");
		}
	
		if(!session('menu_result')){
			$Menu=M('menu');
		
			/*如果是系统管理员拥有所有权限*/
			if(session('username')=='admin'){
				$menu_result=$Menu->where(array('status'=>1))->order('sort desc')->select();
			}else{
				$rules=session('rules');
				if($rules!=''){
					$menu_result=$Menu->where(array('status'=>1,'id'=>array('in',$rules)))->order('sort desc')->select();
				}
			}
			$menu_arr=array();
			foreach ($menu_result as $row){
				$menu_arr[]=strtolower($row['url']);
			}
			$menu_arr[]='backend/base/file/uploadpicture';
		
			$menu_result=list_to_tree($menu_result);
			
			
			session('menu_result',$menu_result);
			session('menu_arr',$menu_arr);
		}

		if(session('username')!='admin'){
			$url=strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
			/* $action_name = strtolower(ACTION_NAME); */
			if(/* $action_name!='uploadpicture' && $action_name!='uploadfile' &&  */$url!='backend/index/index' && !in_array($url, session('menu_arr'))){
				$this->error('未授权的访问');
			}
		}
		$data['menu_result']=session('menu_result');
		$this->assign($data);
	}
	
	function setStatus($field="status"){
		$ids=I('ids');
		if(!$ids){
			$this->error('请选择要修改的数据');
		}
		$field_val=intval(I('get.'.$field));
		if(is_array($ids)){
			$_POST=array(
				$field=>$field_val,
			);
			$map['id']=array('in',$ids);
			//$result=update_data($this->table,array(),$map);
			$Model = M($this->table); // 实例化User对象
			
			$Model->where($map)->save($_POST); // 根据条件更新记录
			
			$ids_str=implode(',', $ids);
			action_log($this->table,$ids_str,$this->action_filed);
			/*如果该表数据有缓存，那么删除缓存*/
			if($this->cache_data!=''){
				F($this->cache_data,NULL);
			}
			if($this->cache_name!=''){
				F($this->cache_name,NULL);
			}
			if($this->recommend_cache!=''){
				F($this->recommend_cache,NULL);
			}
			if($this->session_cache_name!=''){
				session($this->session_cache_name,NULL);
			}
			if($this->has_parent){
				$this->parent_operate();
			}
			$this->success('操作成功',U('index',I('get.')));
		}else{
			$ids=intval($ids);
			if(!$ids){
				$this->error('请选择要操作的数据');
			}
			$_POST=array(
				'id'=>$ids,
				$field=>$field_val,
			);
			$result=update_data($this->table);
			if(is_numeric($result)){
				action_log($this->table,$ids,$this->action_filed);
				/*如果该表数据有缓存，那么删除缓存*/
				if($this->cache_data!=''){
					F($this->cache_data,NULL);
				}
				if($this->cache_name!=''){
					F($this->cache_name,NULL);
				}
				if($this->recommend_cache!=''){
					F($this->recommend_cache,NULL);
				}
				if($this->session_cache_name!=''){
					session($this->session_cache_name,NULL);
				}
				if($this->has_parent){
					$this->parent_operate();
				}
				$this->success('操作成功',U('index',I('get.')));
			}else{
				$this->error($result);
			}
		}
	}
	
	function setStatus2($field="status"){
		$ids=I('ids');
		if(!$ids){
			$this->error('请选择要修改的数据');
		}
		$field_val=intval(I('get.'.$field));
		if(is_array($ids)){
			$_POST=array(
				$field=>$field_val,
			);
			$map['id']=array('in',$ids);
			//$result=update_data($this->table,array(),$map);
			$Model = M($this->table); // 实例化User对象
			
			$result=$Model->where($map)->save($_POST); // 根据条件更新记录
			if($result){
				$maps['member_id']=array('in',$ids);
				$_POST=array(
					$field=>$field_val,
				);
				$Model = M('Shop');
				$result=$Model->where($maps)->save($_POST); // 根据条件更新记录
			}
			
			$ids_str=implode(',', $ids);
			action_log($this->table,$ids_str,$this->action_filed);
			/*如果该表数据有缓存，那么删除缓存*/
			if($this->cache_data!=''){
				F($this->cache_data,NULL);
			}
			if($this->cache_name!=''){
				F($this->cache_name,NULL);
			}
			if($this->recommend_cache!=''){
				F($this->recommend_cache,NULL);
			}
			if($this->session_cache_name!=''){
				session($this->session_cache_name,NULL);
			}
			if($this->has_parent){
				$this->parent_operate();
			}
			$this->success('操作成功',U('index',I('get.')));
		}else{
			$ids=intval($ids);
			if(!$ids){
				$this->error('请选择要操作的数据');
			}
			$_POST=array(
				'id'=>$ids,
				$field=>$field_val,
			);
			$result=update_data($this->table);
			if(is_numeric($result)){
				$maps['member_id']=$ids;
				$_POST=array(
					$field=>$field_val,
				);
				$Model = M('Shop');
				$result=$Model->where($maps)->save($_POST); // 根据条件更新记录
				action_log($this->table,$ids,$this->action_filed);
				/*如果该表数据有缓存，那么删除缓存*/
				if($this->cache_data!=''){
					F($this->cache_data,NULL);
				}
				if($this->cache_name!=''){
					F($this->cache_name,NULL);
				}
				if($this->recommend_cache!=''){
					F($this->recommend_cache,NULL);
				}
				if($this->session_cache_name!=''){
					session($this->session_cache_name,NULL);
				}
				if($this->has_parent){
					$this->parent_operate();
				}
				$this->success('操作成功',U('index',I('get.')));
			}else{
				$this->error($result);
			}
		}
	}
	
	/*
	 * 启用
	 * */
	function enable(){
		$this->setStatus();
	}
	/*
	 * 禁用
	 * */
	function disable(){
		$this->setStatus();
	}
	/*
	 * 删除
	 * */
	function del(){
		$this->setStatus();
	}
	/**
	*推荐
	**/
	function recommend(){
		$this->setStatus('recommend');
	}
}