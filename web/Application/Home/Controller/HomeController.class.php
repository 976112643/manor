<?php
namespace Home\Controller;
use Common\Controller\CommonController;
class HomeController extends CommonController{
	/*
	 * 全局加载
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	protected function __autoload(){
		parent::__autoload();
		if(MODULE_NAME=="Home" || MODULE_NAME=="User"){
			session('loginout_url',U('/'));
		}else{
			session('loginout_url',U(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,param()));
		}

		if(MODULE_NAME=="Home" || MODULE_NAME=="User" && (CONTROLLER_NAME=="Register" || CONTROLLER_NAME=="Login")){
			session('login_url',U('/'));
		}else{
			session('login_url',U(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,param()));
		}
		if(C("SITE_STATUS")!=1 && !$_SESSION["admin_member_id"]){
			$this->error("站点已关闭");
		}
		$this->__init();
		
		
		
		$img_type = array('jpg','gif','png','jpeg','bmp');		/*设定图片格式，退款凭证显示图标用*/
		$language = get_language_recommend_cache();				/*查询推荐的源语言*/
		$all_themes =  array_id_key(get_themes_cache());		/*查询平台拥有的所有皮肤,并将ID转换成KEY*/
		
		
		$data['all_theme'] = $all_themes;
		$data['recommend_language'] = $language;
		$data['img_type'] = $img_type;
		$this->assign($data);
		/*@刘巧刷新消息*/
		session('news_num',get_news_recode(session('home_member_id')));
	}
	protected function __init(){
    	
	}

	
	
	function setStatus($field="status"){
		$ids=I('ids');
		if(!$ids){
			return  array('status'=>'0','msg'=>'请选择要修改的数据' );
// 			$this->error('请选择要修改的数据');
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
			/*如果该表数据有缓存，那么删除缓存*/
			if($this->cache_data!=''){
				F($this->cache_data,NULL);
			}
			if($this->session_cache_name!=''){
				session($this->session_cache_name,NULL);
			}
			if($this->has_parent){
				$this->parent_operate();
			}
			
			return  array('status'=>'1','msg'=>'操作成功' );
// 			$this->success('操作成功');
		}else{
			$ids=intval($ids);
			if(!$ids){
				return  array('status'=>'0','msg'=>'请选择要修改的数据' );
// 				$this->error('请选择要操作的数据');
			}
			$_POST=array(
					'id'=>$ids,
					$field=>$field_val,
			);
			$result=update_data($this->table);
			if(is_numeric($result)){
				
				/*如果该表数据有缓存，那么删除缓存*/
				if($this->cache_data!=''){
					F($this->cache_data,NULL);
				}
				if($this->session_cache_name!=''){
					session($this->session_cache_name,NULL);
				}
				if($this->has_parent){
					$this->parent_operate();
				}
				return  array('status'=>'1','msg'=>'操作成功' );
// 				$this->success('操作成功');
			}else{
				return  array('status'=>'0','msg'=>$result );
// 				$this->error($result);
			}
		}
	}
	
	/*
    * 获取地址分类数据
    * @author  龚双喜
    * @date    2015-07-25
    * */
   public function getArea($pid=0){ 	
//   	 $apptype = !empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY') ?true:false;//手机app接口密钥
   	 
   	 $apptype=$this->appParam();
   	 if($apptype==-1){
   	 	$area_info["area"]=getAreaInfo($pid);//获取地区子类
   	 	return $area_info["area"];
   	 }else{
   	 	$pid = intval(I("post.id"));//地区父类id
   	 	$area_info["area"]=getAreaInfo($pid);//获取地区子类
   	 	$this->ajaxReturn($area_info);//返回json
   	 }
//   	 if($apptype){
//   	 	$area_info["area"]=getAreaInfo($pid);//获取地区子类
//   	 	$this->ajaxReturn($area_info);//返回json
//   	 }else{
//   	 	$this->error("非法访问",'',true);
//   	 }
   }
}