<?php
namespace User\Controller;
use User\Controller\BaseController;
class StoreSettingController extends BaseController {
	private $table = 'shop';
	private $shop_image = 'shop_image';
	private $resources = 'resources';
	
	/**
	*店铺管理
	*	个人译者和翻译公司的店铺管理有所不同,需要做一定的判断处理
	*流程分析
	*	1、店铺的申请
	*		按照用户登录的类型店铺的申请分为个人译者店铺的申请和翻译公司店铺的申请
	*			个人译者的店铺申请所上传的资料是身份证、学历证书、资质证书
	*			公司翻译的店铺申请所上传的资料是营业执照、税务登记、资质证书
	*	2、店铺的审核
	*		店铺上传资料之后就会由后台审核，审核的过程中商铺可以修改上传的资质
	*	3、店铺申请成功
	*		店铺申请成功之后店铺的资料只能修改店铺介绍
	*@author 刘浩 <qq:372980503>
	*@time   2015-07-20
	**/
    public function index(){
		if(IS_POST){//不考虑app端的
			$posts=I("post.");
			//$type = session('type');
			//$shop_id = session('home_shop_id');
			//重新获取店铺信息
			$member_id = session("home_member_id");
			$info = get_info(D('ShopMemberView'),array("member.id"=>$member_id));
			$type = $info["type"];
			$shop_id = $info["shop_id"];
		    if($type==3 or $type==2){
				$_POST['id'] = $shop_id;
				if($type==3){
				  $_POST['type'] = 2;//翻译公司
				  $title_msg="公司名称不能为空";
				  $content_msg="公司介绍不能为空！";
				  $license_msg="营业执照正面不能为空!";
				  $license_msg_1="营业执照反面不能为空!";
				  $tax_msg="税务登记证不能为空！";
				}else if($type==2){
				  $_POST['type'] = 1;//个人译者
				  $title_msg="店铺名称不能为空";
				  $content_msg="店铺介绍不能为空！";
				  $license_msg="身份证正面不能为空!";
				  $license_msg_1="身份证反面不能为空!";
				  $tax_msg="学历证书不能为空！";
				}
				if(empty($shop_id)){
					$_POST["member_id"]=session("home_member_id");
				}
				$_POST['status'] = 0;//表示正在审核
				$_POST['operate_type1'] = $_POST['operate_type'];
				$_POST['operate_type'] = json_encode($_POST['operate_type']);
				$_POST['translate_type'] = json_encode($_POST['servece_type']);
				$_POST['description_now'] = htmlspecialchars($_POST['content']);
				//判断店铺名称是否重名
				if(!empty($_POST['title'])){
					if($shop_id){
						$map=array(
						  "title"=>array("eq",$_POST['title']),
						  "id"=>array("neq",$shop_id),			
						);
					}else{
						$map=array(
						  "title"=>array("eq",$_POST['title']),
						);
					}
					$match_result = get_info($this->table,$map);
					if($match_result){
						$this->error('店铺名称已经被占用，请重新取名！！');
					}
				}
				//判断店铺是否已审核通过
				//$info = get_info($this->table,array("id"=>$shop_id));
				if($info['shop_status']==1){
					$this->error('您的店铺已经审核通过，不可再次修改！');
				}else if($info['shop_status']==2){
					$this->error('您的店铺已被冻结，无法进行修改！');
				}
				//验证资质证书
				if(empty($_POST["image1"]) and empty($_POST["image"])){
					$_POST["images"]="";
				}else{
					$_POST["images"]=1;
				}
				//验证信息的正确性
				$rules = array(
					array('title','require',$title_msg,1),
					array('operate_type1','checkArray','业务范围不能为空',1,'function'),
					array('servece_type','checkArray','服务类别不能为空',1,'function'),
					array('translate_num','require','日翻译量不能为空',1),
					array('translate_year','require','翻译年限不能为空！',1),
					array('translate_year','number','翻译年限必须为数字！'),
					array('shop_license','require',$license_msg,1),
					array('shop_license_1','require',$license_msg_1,1),
					array('shop_tax','require',$tax_msg,1),
					array('images','require','翻译资质证书不能为空',1),	
					array('content','require',$content_msg),
				);
				/*@刘巧 判断用户店铺名是否符合规范*/
				$_POST['title']=get_word_search($_POST['title']);
				//组织上传信息
				/* unset($_POST);
				$_POST = array(
					'id'=>$shop_id,
					'title'=>$parameters['title'],
					'type'=>$type,
					'short_description'=>$parameters['content'],
					'status'=>3,
					'operate_type'=>json_encode($parameters['operate_type']),
					'translate_type'=>json_encode($parameters['servece_type']),
					'translate_year'=>$parameters['translate_year'],
					'traslate_num'=>$parameters['translate_num'],
				); */
				$result = update_data($this->table,$rules);
				if(is_numeric($result)){
					//将店铺的营业执照存入对应的文件夹，对应的字段
					if(is_numeric($posts['shop_license'])){
						multi_file_upload($posts['shop_license'],'Uploads/Shop/license',$this->table,'id',$result,'shop_license');
					}
					if(is_numeric($posts['shop_license_1'])){
						multi_file_upload($posts['shop_license_1'],'Uploads/Shop/license',$this->table,'id',$result,'shop_license_1');
					}
					//将店铺的税务登记证或者是个人译者的学历证书图片存入之地呢的文件夹和对应的字段
					if(is_numeric($posts['shop_tax'])){
						 multi_file_upload($posts['shop_tax'],'Uploads/Shop/license',$this->table,'id',$result,'shop_tax');
					}
					//将店铺的翻译资质存入到shop_image
					if($posts['image']){
						multi_file_upload(array_unique($posts['image']),'Uploads/Shop/license',$this->shop_image,'shop_id',$result,'image');
					}
					$content=C('SHOPZILIAO');
					$account=session ( 'home_member_tel' );
					$status=send_code($content,$account);
					$this->success('提交成功');
				}else{
					$this->error($result);
				}
		    }else{
		    	$this->error("您还未开店或者不是公司账号");
		    }
		}else{
			$member_id = session('home_member_id');
			//获取业务范围缓存
			$operate_type = get_operate_cache();//指的是翻译、审稿、校对、母语审稿、同声传译、陪同翻译
			//定义服务类别
			$servece_type = array('中译外','外译外','外译中');//这个是固定的，后台不能做出修改的
			//判断用户是否开店
			//使用member_id查找店铺列表中是否存在status是1或3的店铺,如果没有就显示让其上传资料，上传资料的时候分为两种一种是翻译公司一种是个人译者
				//如果是开店的就显示其上传的认证信息
				//如果是没有开店的就显示店铺申请的信息
			$info = get_info(D('MemberShopView'),array('shop.member_id'=>$member_id));
			$data['operate_type'] = $operate_type;
			$data['servece_type'] = $servece_type;
			if($info){//开了店的或者是正在审核中的店铺
				$info['operate_type'] = json_decode($info['operate_type']);
				$info['translate_type'] = json_decode($info['translate_type']);
				$data['info'] = $info;
				//获取资质证书
				$shop_image=get_result("shop_image",array("shop_id"=>$info["shop_id"]));
				$data['shop_image']=$shop_image;
				$this->assign($data);
				if($info['shop_status']==1 or $info['shop_status']==2){//表示店铺审核通过或冻结
					$this->display('index2');
				}else{//表示店铺正在审核的
					$this->display('index');
				}
			}else{
			   if(session("type")<2){
			   	 $this->redirect('User/Index/index');
			   }else{	
				 $this->assign($data);
				 $this->display('index');
			   }	
			}
			/* $ParameterArray['data']['operate_type'] = $operate_type;
			$ParameterArray['data']['servece_type'] = $servece_type;
			$this->ReturnParameter($ParameterArray); */
		}
    }
	/**
	*接收页面的参数
	*	接收参数的时候不管是pc还是其他的数据都返回相同的数组数据
	*@author 刘浩  <qq:3792980503>
	*@time 2015-06-30 
	**/
	public function ReceiveParameter($ParameterName){
		if(C('IS_MOBILE')==1){//表示手机访问
			$parameters = inputjson(); 
		}else if(C('IS_MOBILE')==0){//表示pc访问
			$parameters = I();
			$parameters['member_id'] = session('home_member_id');
		}
		//将数据赋值给一个数组
		$ParameterArray = array();
		foreach($ParameterName as $v){
			$ParameterArray[$v] = $parameters[$v];
		}
		//返回参数数组
		return $ParameterArray;
	}
	/**
	*返回数据
	*	返回数据不管是pc还是其他都返回页面处理过的数据数组
	*@author 刘浩  <qq:3792980503>
	*@time 2015-06-30 
	**/
	public function ReturnParameter($ParameterArray){
		if(C('IS_MOBILE')==1){
			$this->ajaxReturn($ParameterArray);
		}else if(C('IS_MOBILE')==0){
			//判断是否需要抛出错误或者成功信息
			if($ParameterArray['checkStatus']===1){
				$this->success($ParameterArray['massage'],U($ParameterArray['url']));
			}else if($ParameterArray['checkStatus']===0){
				$this->error($ParameterArray['massage'],U($ParameterArray['url']));
			}
			//判断是否有要在页面显示的数据
			if(!empty($ParameterArray['data'])){
				$this->assign($ParameterArray['data']);
			}
			$this->display($ParameterArray['template']);
		}
	}
	/**
	 * TODO:店铺皮肤设置，
	 */
	public function theme_setting(){
		if(IS_POST){
			
		}else{
			$map['id'] = session('home_shop_id');
			$shop_theme = get_info($this->table,$map,$field=array('theme_id','have_theme'));
			echo $map['id'];
			print_r($shop_theme);
		}
	}
	
	/**
	 * 图片删除
	 * 上传图片时  删除图片在清除数据库图片信息后删除图片文件
	 */
	function ajaxDeleteImage(){
		if(IS_POST){
			$table=I('post.table');
			$imgurl=I('post.imgurl');
			$shop_id = I('shop_id');
			$image_id=intval(I('post.image_id'));
			
			$result = delete_data($table,array('id'=>$image_id,'shop_id'=>$shop_id));//删除数据库图片地址
			unlink($imgurl);//删除图片文件
			if($result){
				$this->ajaxReturn(array('status'=>1,'info'=>session('sql')));
			}else{
				$this->ajaxReturn(array('status'=>0,'info'=>'删除失败！！'));
			}
		}		
	}	
}