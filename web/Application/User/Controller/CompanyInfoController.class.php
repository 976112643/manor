<?php
namespace User\Controller;
use User\Controller\ShopBaseController;
use Think\Page;
class CompanyInfoController extends ShopBaseController {
	private $table = 'shop';
	
	/**
	*公司信息设置
	*	设置店铺的相关信息
	*流程分析
	*	1、判断用户是否开店
	*	2、查询显示用户的信息
	**/
    public function index(){
		if(session('home_shop_id')){
			$shop_id=session('home_shop_id');
			if(IS_POST){
// 				dump(I('post.'));die;
				$slogan = I('slogan');
				$slogan_count = mb_strlen($slogan,'UTF8');
				$good_at = json_encode(I('good_at'));
				//$good_at = implode(',',$good_at);
				$description_now = addslashes(I('content'));
				//验证信息,一句话概述不能超过20字
				if($slogan_count>20){
					$tips_msg=array('status'=>0,'info'=>'一句话概述不能超过20字');
					$this->appOut($tips_msg,$this->apptype,0);
					//$this->error('一句话概述不能超过20字','',$ajax);
				}else{
					$logo=intval(I("post.logo"));
					if(strtotime(I('regtime'))>=time()){
						$tips_msg=array('status'=>0,'info'=>'成立时间不能比当前时间晚');
						$this->appOut($tips_msg,$this->apptype,0);
					}
					//获取用户输入的营业时间
					$time_s_1 = I('time_s_1');
					$time_s_2 = I('time_s_2');
					$time_s_3 = I('time_s_3');
					$time_s_4 = I('time_s_4');
					if($time_s_1 > $time_s_3){
						$this->error('周一至周五的营业时间设置错误');
					}else if($time_s_1 == $time_s_3 && $time_s_2 > $time_s_4){
						$this->error('周一至周五的营业时间设置错误');
					}
					$time_e_1 = I('time_e_1');
					$time_e_2 = I('time_e_2');
					$time_e_3 = I('time_e_3');
					$time_e_4 = I('time_e_4');
					if($time_e_1 > $time_e_3){
						$this->error('周六至周日的营业时间设置错误');
					}else if($time_e_1 == $time_e_3 && $time_e_2 > $time_e_4){
						$this->error('周六至周日的营业时间设置错误');
					}
					$time_s = array(
							"$time_s_1",
							"$time_s_2",
							"$time_s_3",
							"$time_s_4",
					);
					$time_e = array(
							"$time_e_1",
							"$time_e_2",
							"$time_e_3",
							"$time_e_4",
					);
					//营业时间
					$work_time_s = json_encode($time_s);
					$work_time_e = json_encode($time_e);
// 					$work_time_s = I('time_s_1')."~".I('time_s_2')."~".I('time_s_3')."~".I("time_s_4");
// 					$work_time_e = I('time_e_1')."~".I('time_e_2')."~".I("time_e_3")."~".I("time_e_4");
// 					dump($work_time_e);die;
					//组织将信息修改到数据表中
					$_POST = array(
						'id' => $shop_id,
						'title'=>I('title'),
						'logo'=>I('logo'),
						'area_id'=>I('area'),
						'address'=>I('detailAddress'),
						'regtime'=>I('regtime'),
						'good_at'=>$good_at,
						'orgood_at'=>I('post.good_at'),	
// 						'work_time_s'=>I('work_time_s'),
// 						'work_time_e'=>I('work_time_e'),
						'work_time_s'=>$work_time_s,
						'work_time_e'=>$work_time_e,
						'slogan'=>$slogan,
						'description_now'=>$description_now
					);
					$rules=array(
						array("title","require","名称不能为空"),
						array("logo","require","logo不能为空",1),	
						array("area_id","require","联系地址不能为空"),
						array("address","require","详细地址不能为空"),
						array("regtime","require","成立时间不能为空"),
						array("orgood_at","checkArray","擅长语言不能为空",1,'function'),
						array("work_time_s","require","营业时间不能为空"),
						array("work_time_e","require","营业时间不能为空"),
						array("slogan","require","概述不能为空"),
						array("description_now","require","介绍不能为空"),
// 						array("work_time_s","/^(((([01]\d)|(2[0-3]))\:[0-5]\d)|(24\:00))\~(((([01]\d)|(2[0-3]))\:[0-5]\d)|(24\:00))$/","请填写正确的营业时间格式"),
// 						array("work_time_e","/^(((([01]\d)|(2[0-3]))\:[0-5]\d)|(24\:00))\~(((([01]\d)|(2[0-3]))\:[0-5]\d)|(24\:00))$/","请填写正确的营业时间格式"),
					);
					//判断店铺名称是否重名
					if(!empty($_POST['title'])){
						$map=array(
								"title"=>array("eq",$_POST['title']),
								"id"=>array("neq",$shop_id),
						);
						$info = get_info($this->table,$map);
						if($info){
							$tips_msg=array('status'=>0,'info'=>'店铺名称已经被占用，请重新取名！！');
							$this->appOut($tips_msg,$this->apptype,0);
						}
					}
					//数据更新
					$result = update_data($this->table,$rules);
					if(is_numeric($result)){
						if($logo) multi_file_upload($logo,'Uploads/Shop/license',$this->table,'id',$result,'logo');
						$tips_msg=array('status'=>1,'info'=>'修改成功！','url'=>U('User/CompanyInfo/index'));
						$this->appOut($tips_msg,$this->apptype,0);
						//$this->success('修改成功！',U('User/CompanyInfo/index'),$ajax);
					}else{
						$tips_msg=array('status'=>0,'info'=>$result,'url'=>U('User/CompanyInfo/index'));
						$this->appOut($tips_msg,$this->apptype,0);
						//$this->error('修改失败！',U('User/CompanyInfo/index'),$ajax);
					}
				}
			}else{
				$this->companyinfo();
			}
		}else{
			//$this->error('您的店铺尚未开通！',U('Home/Index/index'),$this->apptype);
			$tips_msg=array('status'=>1,'info'=>'您的店铺尚未开通！','url'=>U('Home/Index/index'));
				
			$this->appOut($tips_msg,$this->apptype,0);
		}
    }
    
    public function companyinfo(){
    	if(session('home_shop_id')){
			$shop_id=session('home_shop_id');
			//查询用户店铺的信息
			$shop_info = get_info(D('MemberShopView'),array('id'=>$shop_id));
			//print_r($shop_info);exit;
			//查询地理信息
			$area_data = get_area_cache();
			$area_list=list_to_tree($area_data);
			//查询店铺的地理信息
			$shop_area_id = $shop_info['area_id'];
			foreach ($area_list as $val){
				foreach ($val['_child'] as $v){
					foreach ($v['_child'] as $vv){
						if($vv['id']==$shop_area_id){
							$shop_info['city']=$v['id'];
							$shop_info['province']=$val['id'];
							$city_data=$val['_child'];
							$area_k=$v['_child'];
						}
					}
				}
			}
			
			//查询语种分类
			$language_data = list_to_tree(get_language_cache());
			
			//查询擅长的领域
			$good_at = json_decode($shop_info['good_at']);
			
			$data['shop_info'] = $shop_info;
			$data['area_data'] = $area_data;
			
			$data['city_data']=$city_data;
			$data['area_k']=$area_k;
			
			$data['good_at'] = $good_at;
			
			$data['language_data'] = $language_data;
// 			$time_data_s = explode("~", $shop_info['work_time_s']);
// 			$time_data_e = explode("~", $shop_info['work_time_e']);
			$time_data_s = json_decode($shop_info['work_time_s']);
			$time_data_e = json_decode($shop_info['work_time_e']);
			$this->assign('time_data_s',$time_data_s)->assign('time_data_e',$time_data_e);
				
			$tips_msg=array('status'=>1,'info'=>$data);
			
			$this->appOut($tips_msg,$this->apptype,1);
//			if ($apptype){
//				$appinfo['shop_info'] = $shop_info;//用户店铺信息
//				$appinfo['area_data'] = $area_data;//地理信息
//				$appinfo['good_at'] = $good_at;//擅长领域
//				$appinfo['language_data'] = $language_data;//语种分类
//				$this->ajaxReturn($data['address']);
//			}
//			$this->assign($data);
//			$this->display();
    	}else{
    		$tips_msg=array('status'=>1,'info'=>'您的店铺尚未开通！','url'=>U('Home/Index/index'));
				
			$this->appOut($tips_msg,$this->apptype,0);
    	
    	}
    
    
    }
	
}