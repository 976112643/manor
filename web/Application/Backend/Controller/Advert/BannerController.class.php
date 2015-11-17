<?php
namespace Backend\Controller\Advert;
use Backend\Controller\Base\AdminController;
class BannerController extends AdminController {
	protected $table='banner';
	protected $cache_name='home_banner';
	/**
	 * 广告列表页
	 * @author						李东
	 * @date						2015-06-16
	 */
	public function index(){
		if(I('title')){
			$map['title']=array('like','%'.I('title').'%');
		}
		$map['status']=array('gt',-1);
		$map['page']=$this->cache_name;
		$list=$this->page($this->table,$map,'sort desc,id desc');
		getBanner('home');
		$data['_list']=$list;
// 		dump($list);die;
		$this->assign($data);
		$this->display();
	}
	/**
	 * 广告添加
	 * @author						李东
	 * @date						2015-06-16
	 */
	public function add(){
		if(IS_POST){
			$this->update();
		}else{
			$this->display('operate');
		}
	}
	/**
	 * 广告修改
	 * @author						李东
	 * @date						2015-06-16
	 */
	public function edit(){
		if(IS_POST){
			$this->update();
		}else{
			$id=intval(I('id'));
			$map['id']=$id;
			$info=get_info($this->table,$map);
			$info['start_time']=reset(explode(" ",$info['start_time']));
			$info['end_time']=reset(explode(" ",$info['end_time']));
			$data['info']=$info;
			$this->assign($data);
			$this->display('operate');
		}
	}
	/**
	 * 添加、修改数据库数据
	 * @author						李东
	 * @date						2015-06-16
	 */
	public function update(){
		if(IS_POST){
			if($_POST['type']=='image' && $_POST['old_image']=='' && I('post.image')==""){
				$this->error("图片不能为空！");
			}
			if($_POST['type']=='code' && I('post.content')==""){
				$this->error("HTML代码不能为空！");
			}
			$rules = array ( 
				//array('title','require','请填写广告标题！'), //默认情况下用正则进行验证
			);
			$_POST['page']=$this->cache_name;
			$result=update_data($this->table,$rules);
			if(is_numeric($result)){
				if(I('post.image')!=''){
					unlink($_POST['old_image']);
				}
				multi_file_upload(I('post.image'),'Uploads/Banner','banner','id',$result,'image');

				F($this->cache_name,null);
				$this->success('操作成功！',U('index'));
			}else{
				$this->error($result);
			}
		}else{
			$this->success('违法操作',U('index'));
		}
	}
	
	/*
	 * 删除广告数据，同时删除广告图片
	 * @time 2015-01-13
	 * @author	康利民  <3027788306@qq.com>
	 * */
// 	function del($ids){
// 		if(is_array($ids)){
// 			foreach ($ids as $id){
// 				$this->doDel($id);
// 			}
// 		}else{
// 			$id=$ids;
// 			$this->doDel($id);
// 		}
// 		F($this->cache_name,null);
// 		$this->success('删除成功');
// 	}
	function doDel($id){
		$map['id'] = $id;
		$info=get_info($this->table,$map);
		if($info && $info['type']=="save_path"){
			@unlink($info['save_path']);
		}
		$de=delete_data($this->table,$map);
	}
	
	public function ajaxDelete_banner(){
		$posts=I("post.");
		$info=get_info($this->table,array("id"=>$posts['id']));
		$path=$info['save_path'];
		$_POST=null;
		F($this->cache_name,null);
		if(file_exists($path)){
			if(@unlink($path)){
				$_POST['id']=$posts['id'];
				$_POST['save_path']='';
				update_data($this->table,array("id"=>$posts['id']));
				$this->success("删除成功");
			}else{
				$this->error("删除失败");
			}
		}else{
			$_POST['id']=$posts['id'];
			$_POST['save_path']='';
			update_data($this->table,array("id"=>$posts['id']));
			$this->success("文件不存在，删除失败，数据被清空");
		}
	}

}