<?php
// +----------------------------------------------------------------------
// | Blues
// +----------------------------------------------------------------------
// | Copyright (c) 2013 Blues All rights reserved.
// +----------------------------------------------------------------------
// | Author: Blues <blues.deng@hotmail.com>
// +----------------------------------------------------------------------

namespace Backend\Controller\News;
use Backend\Controller\Base\AdminController;

class CategoryController extends AdminController {
	protected $table='category';
	protected $type='news';
	protected $action_filed='id,title';
	protected $cache_data='news_category_result';
	function index($pid=0){
	    $type=$this->type;
		$map['type']=$type;
		$title       =   I('title');
		$search_type=I('search_type');
		if($title){
			if($search_type==2){
				$map['id']=   intval($title);
			}else{
				$map['title']    =   array('like', '%'.$title.'%');
			}
			$data['title']=$title;
			$data['search_type']=$search_type;
		}
	    $map['pid']=$pid;
		$list=$this->page($this->table,$map,"sort desc");
		
		$data['_list']=int_to_string($list,$map=array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用'),'is_fixed'=>array(0=>'否',1=>'是')));
		$data['type']=$type;
		$this->assign($data);
		$this->assign('meta_title',"分类");
		getCategory($type);
		$this->display();
	}
	/* 分类操作公用方法
	 * @time 2015-02-24
	 * @author	康利民  <3027788306@qq.com>
	 * */
	protected function operate(){
		$cover=$_POST['new_cover'];
		unset($_POST['new_cover']);
	    $pid=I('pid');
		$_POST['uid']=session("member_id");
		$rules = array(
			array('title','require','请输入分类名称！',1)
		);
		$result=update_data($this->table,$rules);
		if(is_numeric($result)){
			if($cover){
				multi_file_upload($cover,'Uploads/Images','category','id',$result,'cover');
			}
			F($this->cache_data,null);
			$this->success('操作成功',U('index',array('pid'=>$pid)));
		}else{
			$this->error($result);
		}
	}

	/*
	 * 添加新闻分类
	 * @time 2015-02-24
	 * @author	康利民  <3027788306@qq.com>
	 * */
	function add($id=0){
		if(IS_POST){
			$_POST['member_id']=session("member_id");
			if($_POST['pid']>0){
				$info=get_info($this->table,array('id'=>$_POST['pid']));
				$_POST['path']=$info['path'].$_POST['pid'].'-';
			}else{
				$_POST['path']='-0-';
			}
			$_POST['level']=count(explode("-",$_POST['path']))-2;
			$_POST['type']=$this->type;
			$this->operate();
		}else{
			$this->assign('meta_title',"添加");
			$this->display('operate');
		}
	}
	
	/*
	 * 修改新闻分类
	 * @time 2015-02-24
	 * @author	康利民  <3027788306@qq.com>
	 * */
	public function edit(){
		if(IS_POST){
			$posts=$_POST;
			if($posts['cover']){
				$info=get_info($this->table,array("id"=>array("eq",$posts['id'])),'cover');
				if(file_exists($path)){
					@unlink($path);
				}
				$_POST['cover']='';
			}
			unset($_POST['cover']);
			$_POST['new_cover']=$posts['cover'];
			$this->operate();
		}else{
			$id=intval(I('id'));
			$data['info']=get_info('category',array('id'=>$id));
			$this->assign($data);
			$this->assign('meta_title',"修改");
			$this->display('operate');
		}
	}
	
	
	public function del(){
	    $ids = I('ids');
		if(is_array($ids)){
			foreach ($ids as $id){
				$this->doDel($id);
			}
		}else{
			$id=$ids;
			$this->doDel($id);
		}
		F($this->cache_data,null);
		$this->success("删除成功");
	}
	function doDel($id){
		$info=get_info($this->table,array("id"=>$id));
		if($info){
			if($info['is_fixed']==1){
				$this->error("分类【".$info['title']."】为内置分类，禁止删除");
			}
			if($info['num']>=1){
				$this->error("分类【".$info['title']."】下还有内容");
			}
			$count=count_data($this->table,array("pid"=>$id));
			if($count>=1){
				$this->error("删除失败<br />该分类下还有下级分类，请先删除下级分类后再删除");
			}else{
				delete_data($this->table,array("id"=>$info['id']));
			}
		}
	}

	public function ajaxDelete_category(){
		$posts=I("post.");
		$info=get_info($this->table,array("id"=>$posts['id']));
		$path=$info['cover'];
		$_POST=null;
		F($this->cache_data,null);
		if(file_exists($path)){
			if(@unlink($path)){
				$_POST['id']=$posts['id'];
				$_POST['cover']='';
				update_data($this->table);
				$this->success("删除成功");
			}else{
				$this->error("删除失败");
			}
		}else{
			$_POST['id']=$posts['id'];
			$_POST['cover']='';
			update_data($this->table);
			$this->success("文件不存在，删除失败，数据被清空");
		}
	}
}
