<?php
namespace User\Controller;
use User\Controller\BaseController;
class UpdateImgController extends BaseController{
	public $table	=	'member';
	protected $uid;
	/**
	*用户修改头像
	*	用户在个人中心可以修改自己的头像
	*流程分析
	*	
	**/
	public function index(){
		if(IS_POST){
			
		}else{
			$this->display('UpdateImg');
		}
		
	}
	
	/*
	 * app保存图像
	 * */
	public function save_face(){
		$this->uid=session('home_member_id');
		if(IS_POST){
			if($_FILES){
               	$file=$_FILES['img'];
                $filename = $file["tmp_name"];
                if ($file['error']>0) {//是否存在文件
                	echo json_encode(array('status'=>0,'info'=>'上传图片不存在'));
                	exit;
                }
                
              	$img_folder='icon/';
                if (!file_exists($img_folder)) {
                    mkdir($img_folder,'0777');
                }
                
				$img = $img_folder . $this->uid.'_avatar_big' . "." . 'jpg';//big，small middle
	                
                if (!move_uploaded_file($filename, $img)) {
                    echo json_encode(array('status'=>0,'info'=>'上传图片不存在'));
                	exit;
                }else{
                	$middle=thumb($img,120,120,'M');
                	$small=thumb($img,48,48,'S');
                	rename($img_folder .'M'.$this->uid.'_avatar_big' . "." . 'jpg',$img_folder . $this->uid.'_avatar_middle' . "." . 'jpg');
                	rename($img_folder .'S'.$this->uid.'_avatar_big' . "." . 'jpg',$img_folder . $this->uid.'_avatar_small' . "." . 'jpg');
                }
                $img='http://'.$_SERVER['SERVER_NAME'].'/'.__ROOT__.'/'.$img_folder . $this->uid.'_avatar_big' . "." . 'jpg';
				echo json_encode(array('status'=>1,'info'=>'上传成功','img'=>$img));
				exit;
			}else{
				echo json_encode(array('status'=>0,'info'=>'上传失败'));
				exit;
			}
		}else{
			echo json_encode(array('status'=>0,'info'=>'提交错误'));
			exit;
//$this->display('Index/save_face');
		}
	
	}
	
}