<?php

namespace Backend\Controller\Notice;
use Backend\Controller\Base\AdminController;

class IndexController extends AdminController {
	protected $table='article';
	protected $type='notice';
	public function index(){
		$title       =   I('title');
	
		$map['status']=array('gt',-1);
		if($title){
			$map['title']    =   array('like', '%'.$title.'%');
		}
		$map['type']=$this->type;
		$list   = $this->page(D('ArticleView'), $map,'id desc');
		$list=int_to_string($list,array("status"=>array("0"=>"已禁用","1"=>"已启用"),"recommend"=>array("0"=>"推荐","1"=>"取消推荐")));
		$this->assign('_list', $list);
		$this->assign('type',$type);
		$this->assign('meta_title',"公告");
		$this->display();	
	}
	
	/* 公告操作公用方法
	 * @time 2015-04-29
	 * @author	康利民  <3027788306@qq.com>
	 * */
	protected function update(){
		$rules = array(
			array('title','require','标题必须！',1), //默认情况下用正则进行验证
			array('content','require','内容必须！',1), //默认情况下用正则进行
		);
		$_POST['content']=replaceStrImg($_POST['content'],"replace");
		$_POST['type']=$this->type;
		$result=update_data($this->table,$rules);
		if(is_numeric($result)){
			$this->success('操作成功！',U('index'));
		}else{
			$this->error($result);
		}
	}

	/*
	 * 添加公告
	 * @time 2015-04-29
	 * @author	康利民  <3027788306@qq.com>
	 * */
	function add($id=0){
		if(IS_POST){
			$_POST['member_id']=session("member_id");
			$this->update();
		}else{
			$this->assign("meta_title","添加");
			$this->display('operate');
		}
	}
	
	/*
	 * 编辑公告
	 * @time 2015-04-29
	 * @author	康利民  <3027788306@qq.com>
	 * */
	public function edit(){
		if(IS_POST){
			$_POST['update_member_id']=session("member_id");
			$this->update();
		}else{
			$id=intval(I('id'));
			$info=get_info($this->table,array('id'=>$id));
			$info['content']=replaceStrImg($info['content']);
			$this->assign('info',$info);
			$this->assign("meta_title","修改");
			$this->display('operate');
		}
	}

	/*
	 * 删除公告
	 * 1、根据点击删除传递过来的ID获取相关数据
	 * 2、获取内容中的图片并删除
	 * 3、删除数据表中的数据
	 * @time 2015-04-29
	 * @author	康利民  <3027788306@qq.com>
	 * */
	public function del(){
		//1、根据点击删除传递过来的ID获取相关数据
		$ids = I('ids');
		$map['id']=array("in",$ids);
		$result=get_result($this->table,$map);
		//2、获取内容中的图片并删除
		foreach ($result as $key => $value) {
			//删除封面
			if(file_exists($value['cover'])){
				@unlink($value['cover']);
			}
			//删除内容中的图片
			delStrImgs($value['content']);
		}
		//3、删除数据表中的数据
		delete_data($this->table,$map);
		$this->success("删除成功");
	}
	public function setRec(){
		$this->setStatus('recommend');
	}
}
