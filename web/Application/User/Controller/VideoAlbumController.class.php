<?php
namespace User\Controller;
use User\Controller\ShopBaseController;
use Think\Page;
class VideoAlbumController extends ShopBaseController {
	private $table = 'resources';
	/**
	*相册/视频管理
	*	显示店铺的视频和照片
	*@author 刘浩  <371980503@qq.com>
	*@time 2015-07-07
	**/
    public function index(){
		if(session('home_shop_id')){
			if(IS_POST){
				//接收用户传值
				$title = I('post.title');
				$img_path = I('post.img_path');
				//验证用户名不能大于20个字
				if(strlen($img_path)>20){
					$this->error('字数不能大于20！');
				}
				//验证标题不能为空
				/*if(empty($title)){
					$this->error('标题不能为空！');
				}*/
				if(empty($img_path)){
					$this->error('请上传图片或视频！');
				}
				//将视频和图片分类
				//$map["id"]=$img_path;
				if(is_array($img_path)){
					$map=array("id"=>array("in",implode(",",$img_path)));
				}
				$result=get_result("file",$map);
				if(empty($result)){
					$this->error('图片或视频上传失败！');
				}
				$img_path=array();
				foreach($result as $key => $row){
					$ext=array("flv","mp4","f4v");
					if(in_array($row["ext"],$ext)){
						$type=4;
					}else{
						$type=1;
					}
					//生成视频缩略图
					if($type==4){
						$makeImage="Uploads/shop/VideoAlbum/".uniqid().".jpg";
						//视频路径
						$input=$_SERVER["DOCUMENT_ROOT"].__ROOT__."/".$row["save_path"];
						//视频缩略图路径
						$output=$_SERVER["DOCUMENT_ROOT"].__ROOT__."/".$makeImage;
						makeVideoImage($input,$output);
					}
					/*$_POST = array(
						'title'=>$title,
						'pid'=>session('home_shop_id'),
						'type'=>$type,
						'cover'=>$makeImage,
					);*/
					$data_array[$row["id"]]=array(
						'title'=>$_POST["filename_".$row["id"]],
						'pid'=>session('home_shop_id'),
						'type'=>$type,
						'cover'=>$makeImage,
					);
					$img_path[]=$row["id"];
					//$result = update_data($this->table,$rules);
				}
				$result=multi_file_uploads($img_path,'Uploads/shop/VideoAlbum',$this->table,$data_array,'img_path');
				if($result['success']==count($img_path)){
					//multi_file_upload($img_path,'Uploads/shop/VideoAlbum',$this->table,'id',$result,'img_path');
					$this->success('提交成功！！',U('User/VideoAlbum/index'));
				}else{
					$this->error('提交失败！！',U('User/VideoAlbum/index'));
				}
			}else{
				$shop_id = session('home_shop_id');
				$map['pid'] = $shop_id;
				$map['type'] = array('in',array(1,4));//表示表中的视频和店铺图片
				
				//将店铺的图片和视频缩略图显示出来
				$shop_resource = $this->page($this->table,$map);
				$data['shop_resource'] = $shop_resource;
				$data['p']=$_GET['p'];
				$this->assign($data);
				$this->display();
			}
			
		}else{
			$this->error('您的店铺尚未开通！！',U('Home/Index/index'));
		}
		
    }
    /**
	*照片或视频ajax删除
	*@author 刘浩  <371980503@qq.com>
	*@time 2015-07-07
	**/
	public function del(){
		if(session('home_shop_id')){
			$resource_id = I('resources');

			//查询记录
			$resource_info = get_info($this->table,array('id'=>$resource_id));
			
			//删除记录
			$result = delete_data($this->table,array('id'=>$resource_id));
			if($result){
				//删除真正的文件
				unlink($resource_info['img_path']);//删除真的文件
				$this->ajaxReturn(array('status'=>1,'info'=>'删除成功！！'));
			}else{
				$this->ajaxReturn(array('status'=>0,'info'=>'删除失败！！'));
			}
		}else{
			$this->ajaxReturn(array('status'=>0,'info'=>'非法操作！'));
		}
		
	}
	public function ajax_update(){
		$resource_id = I('id');
		$title = I('title');
		//修改对应的id的title
		unset($_POST);
		$_POST['id'] = $resource_id;
		$_POST['title'] = $title;
		print_r($_POST);
		$result = update_data($this->table);
		//$this->ajaxReturn(session('sql'));
		if(is_numeric($result)){
			$this->ajaxReturn(array('status'=>1,'msg'=>'修改成功！！'));
		}else{
			$this->ajaxReturn(array('status'=>0,'mag'=>'修改失败！！'));
		}
	}
	
    
}