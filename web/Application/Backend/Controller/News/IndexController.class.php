<?php

namespace Backend\Controller\News;
use Backend\Controller\Base\AdminController;

class IndexController extends AdminController {
	protected $table='article';

	public function index(){
		$title       =   I('title');
		$map['article.status']=array('gt',-1);
		$search_type=I('search_type');
		if($title){
			if($search_type==2){
				$map['c_title']=   array('like', '%'.(string)$title.'%');
			}else{
				$map['title']    =   array('like', '%'.(string)$title.'%');
			}
			$data['title']=$title;
			$data['search_type']=$search_type;
		}
		$map['type']='news';
		$list   = $this->page(D('ArticleView'), $map,'id desc');
		$list=int_to_string($list,array("status"=>array("0"=>"已禁用","1"=>"已启用"),"recommend"=>array("0"=>"推荐","1"=>"取消推荐")));
		$this->assign('_list', $list);
		$this->assign('type',$type);
		$this->assign('meta_title',"文章列表");
		$this->display();	
	}
	
	
	/* 文章操作公用方法
	 * @time 2015-02-24
	 * @author	康利民  <3027788306@qq.com>
	 * */
	protected function update(){
		$id=intval(I("id"));
		$rules = array(
			array('category_id','require','分类必须！',1), //默认情况下用正则进行验证
			array('title','require','标题必须！',1), //默认情况下用正则进行验证
			array('content','require','内容必须！',1), //默认情况下用正则进行
		);
		$category_id=I('category_id');

		//当数据为修改时再查询修改前的分类ID
		if($id!=0){
			$info=get_info($this->table,array('id'=>$id),'category_id,path');
		}
		$category_info=get_info("category",array("id"=>$category_id),'path');
		$_POST['path']=$category_info['path'].$category_id.'-';
		//替换内容中的本地图片路径
		$_POST['content']=replaceStrImg($_POST['content'],"replace");
		$_POST['type']='news';
		$result=update_data($this->table,$rules);
		if(is_numeric($result)){
			if(I('post.cover')!=''){
				unlink($_POST['old_cover']);
				multi_file_upload(I('post.cover'),'Uploads/News','article','id',$result,'cover');
			}
			//统计分类（包括父级分类，修改后的）下的内容数量并保存到分类表
			//通过path获取到父级分类id
			$path=explode('-',$_POST['path']);
			foreach ($path as $key => $val){
				$_POST=null;
				if($val!=0){
					$_POST['id']=$val;
					$_POST['num']=count_data($this->table,array("path"=>array("like",'%-'.$val.'-%')));
					update_data("category");
				}
			}

			if($info['category_id']!=$category_id){
				if($id>0){
					//统计分类（包括父级分类，修改前的）下的内容数量并保存到分类表
					//通过path获取到父级分类id
					$info_path=explode('-',$info['path']);
					foreach ($info_path as $key => $val){
						$_POST=null;
						if($val!=0){
							$_POST['id']=$val;
							$_POST['num']=count_data($this->table,array("path"=>array("like",'%-'.$val.'-%')));
							update_data("category");
						}
					}
				}
			}
			
			if($id==0){
				F('news_category_result',null);
			}
			$this->success('操作成功！',U('index',array('type'=>$type)));
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
			//还原内容中的本地图片路径
			$info['content']=replaceStrImg($info['content']);
			$this->assign(array('info'=>$info));
			$this->assign("meta_title","修改");
			$this->display('operate');
		}
	}

	/*
	 * 删除公告
	 * 1、根据点击删除传递过来的ID获取相关数据
	 * 2、获取内容中的图片并删除
	 * 3、统计数据删除后分类下的新闻数量
	 * 4、删除数据表中的数据
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

			//3、重新统计删除后分类下的新闻数量
			//通过path获取到父级分类id
			$info_path=explode('-',$value['path']);
			foreach ($info_path as $key => $val){
				$_POST=null;
				if($val!=0){
					$_POST['id']=$val;
					$_POST['num']=count_data($this->table,array("path"=>array("like",'%-'.$val.'-%')));
					update_data("category");
				}
			}
		}
		F('news_category_result',null);
		//4、删除数据表中的数据
		delete_data($this->table,$map);
		$this->success("删除成功");
	}
	//推荐/取消推荐方法
	public function setRec(){
		$this->setStatus('recommend');
	}
}
