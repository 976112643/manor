<?php
namespace User\Controller;
use User\Controller\ShopBaseController;
use Think\Page;
class SuccessController extends ShopBaseController {
	private $table = 'shop_undergo';
	/**
	*成功分享显示与添加
	*	需要将店铺的成功分享显示添加
	*流程分析
	*	1、判断用户是否登录
	*	2、判断是否开店成功
	*	3、显示店铺的过往经历
	*	4、接收用户添加的店铺经历
	*	5、添加店铺的成功分享
	**/
    public function index(){
		if(session('home_shop_id')){
			if(IS_POST){
				//接收用户传来的值
				$start_time_year = intval(I('start_time_year'));
				$start_time_month = intval(I('start_time_month'));
				$end_time_year = intval(I('end_time_year'));
				$end_time_month = intval(I('end_time_month'));
				$content = htmlspecialchars(I('content'));
				
				$start_time = mktime(0,0,0,$start_time_month,1,$start_time_year);
				$end_time = mktime(0,0,0,$end_time_month,1,$end_time_year);
				$now_time = time();
				//判断用户的结束时间是否超过当前时间
				if ($end_time > $now_time){
					$tips_msg=array('status'=>0,'info'=>'结束时间不能大于当前时间！！');
					$this->appOut($tips_msg,$this->apptype,0);
				}
				//判断时间格式是否正确
				if(!$end_time){
					$this->error('时间格式错误！！');
				}
				if($start_time >= $end_time){
					$this->error('开始时间不能超过结束时间！！');
				}
				
				if(empty($content)){
					$tips_msg=array('status'=>0,'info'=>'请填写分享');
					$this->appOut($tips_msg,$this->apptype,0);
				}else if(mb_strlen($content,'utf-8')>200||mb_strlen($content,'utf-8')<50){
					$tips_msg=array('status'=>0,'info'=>'填写的分享字数应满足50-200字');
					$this->appOut($tips_msg,$this->apptype,0);
				}
				
				//将记录添加到表中
				$_POST = array(
					'start_time'=>$start_time,
					'end_time'=>$end_time,
					'shop_id'=>session('home_shop_id'),
					'content'=>$content,
					'type'=>2,
				);
				$result = update_data($this->table);
				if(is_numeric($result)){
					$this->success('添加成功！！',U('User/Success/index'));
				}else{
					$this->error('$result',U('User/Success/index'));
				}
			}else{
				 //查询显示店铺的过往经历
				 $shop_id = session('home_shop_id');
				 
				 $result = get_experience($shop_id, 2);
				 if($result['status'] == 1){
				 	$shop_info = $result['shop_info'];
				 }
				 foreach($shop_info as $k=>$v){
					$shop_info[$k]['content']=$v['content'];
					/*@刘巧：转换Br*/
					$shop_info[$k]['content']=nl2br($shop_info[$k]['content']);
					 
				}
				 $data['shop_info'] = $shop_info;
				 $this->assign($data);
				 $this->display();
			}
		}else{
			$this->error('您的店铺尚未开通！！',U('Home/Index/index'));
		}
		
    }
    /**
	*成功分享修改
	*	显示并修改成功分享
	*流程分析
	*	接收本条数据的id
	*	查询并显示数据
	*	接收修改的数据
	*	验证数据
	*	修改数据表中的数据
	**/
    public function operate(){
		if(session('home_shop_id')){
			if(IS_POST){
				//接收用户传来的值
				$start_time_year = intval(I('start_time_year'));
				$start_time_month = intval(I('start_time_month'));
				$end_time_year = intval(I('end_time_year'));
				$end_time_month = intval(I('end_time_month'));
				$content = htmlspecialchars(I('content'));
				$id = intval(I('shop_under_go'));
				
				$start_time = mktime(0,0,0,$start_time_month,1,$start_time_year);
				$end_time = mktime(0,0,0,$end_time_month,1,$end_time_year);
				//判断时间格式是否正确
				if(!$end_time){
					$this->error('时间格式错误！！');
				}
				if($start_time >= $end_time){
					$this->error('开始时间不能超过结束时间！！');
				}
				
				if(empty($content)){
					$tips_msg=array('status'=>0,'info'=>'请填写分享');
					$this->appOut($tips_msg,$this->apptype,0);
				}else if(mb_strlen($content,'utf-8')>200||mb_strlen($content,'utf-8')<50){
					$tips_msg=array('status'=>0,'info'=>'填写的分享字数应满足50-200字');
					$this->appOut($tips_msg,$this->apptype,0);
				}
				//将记录添加到表中
				$_POST = array(
					'id'=>$id,
					'start_time'=>$start_time,
					'end_time'=>$end_time,
					'shop_id'=>session('home_shop_id'),
					'content'=>$content,
				);
				
				$rules=array(
						array('start_time','require','请输入时间'),
						array('end_time','require','请输入时间'),
						array('content','require','请输入内容'),
				);
				$result = update_data($this->table,$rules);
				if(is_numeric($result)){
					$this->success('添加成功！！',U('User/Success/index'));
				}else{
					$this->error('$result',U('User/Success/index'));
				}
			}else{
				//接收id
				$UnderGo_id  = intval(I('under_go'));
				//查询数据
				$UnderGo_info = get_info($this->table,array('id'=>$UnderGo_id));
				if(!$UnderGo_info){
					$this->error('非法操作！！');
				}
				//时间转换
				$start_time_year = date('Y',$UnderGo_info['start_time']);
				$start_time_month = date('m',$UnderGo_info['start_time']);
				$end_time_year = date('Y',$UnderGo_info['end_time']);
				$end_time_month = date('m',$UnderGo_info['end_time']);
				$UnderGo_info = array(
					'id'=>$UnderGo_info['id'],
					'start_time_year'=>$start_time_year,
					'start_time_month'=>$start_time_month,
					'end_time_year'=>$end_time_year,
					'end_time_month'=>$end_time_month,
					'content'=>$UnderGo_info['content']
				);
				$data['UnderGo_info'] = $UnderGo_info;
				$this->assign($data);
				$this->display();
			}
			
		}else{
			$this->error('您的店铺尚未开通！！',U('Home/Index/index'));
		}
	}
    /**
	*过往经历删除
	*	用户点击删除按钮之后就会删除页面中对应的过往经历
	*流程分析
	*	1、接收用户的传值
	*	2、删除对应的信息
	**/
	public function del(){
		$under_go_id = intval(I('under_go'));
		//删除对应的信息
		$result = delete_data($this->table,array('id'=>$under_go_id));
		if($result){
			$this->success('成功删除！',U('User/Success/index'));
		}else{
			$this->error('删除失败！',U('User/Success/index'));
		}
	}
	
	
    
}