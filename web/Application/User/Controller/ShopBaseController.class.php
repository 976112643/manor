<?php
namespace User\Controller;
use User\Controller\BaseController;
class ShopBaseController extends BaseController{
	/*
	 * 店铺中心基础控制器
	 * 与店铺相关的控制器都需要继承该控制器
	 * @author	龚双喜
	 * @date    2015-08-01
	 * */
	protected function __init(){
	   parent::__init();	
	   $app=$this->appParam();
	   if($app==-1){
	   	        $member_id=session("home_member_id");
   	       	    $info = get_info(D('ShopMemberView'),array("member.id"=>$member_id));
   	       	    if($info["type"]>1){
	   	       	    if($info["shop_status"]!=1){
	   	       	    	$this->error("您的店铺暂未开通或已被禁用，请开通/启用后再试! ",U("User/StoreSetting/index"));
	   	       	    }
   	       	    }else{
   	       	    	$this->redirect('User/Index/index');
   	       	    }
			   /*if(session("shop_status")!=1 and session("type")>1){	  
			   	  $this->error("您的店铺暂未开通或已被禁用，若已开通请重新登录再试! ",U("User/StoreSetting/index"));
			   }else{
			   	  if(session("type")<2){
			   	  		$this->redirect('User/Index/index');
			   	  }	
			   }*/
	   }	  
	}
}