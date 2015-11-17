<?php
namespace User\Controller;
use User\Controller\ShopBaseController;
class ScheduleController extends ShopBaseController{
	private $shop_time = 'shop_time';
	
	/**
	*店铺日程管理
	*	显示店铺的时间设置,店家在自己的后台能够设置自己的时间日程
	*流程分析
	*	1、分解任务
	**/
	public function index(){
		$map['shop_id'] = session('home_shop_id');
		$shop_time = get_result($this->shop_time,$map,'','time,week asc');
		//dump($shop_time);
		if(IS_POST){
			//生成第一条数据
			$shop_time = I('brand');
			
			//将日程信息从数据库中查询出来
			$shop_time_id = get_result($this->shop_time,array('shop_id'=>session('home_shop_id')),'','time,week asc');
			$shop_sql_array = array();
			foreach($shop_time as $k=>$v){
				$shop_sql_array[$k]['id'] = $shop_time_id[$k]['id'];
				$shop_sql_array[$k]['time'] = $v;
			}
			//根据数组组合sql语句最好是一句
			$shop_time_sql = '';
			foreach($shop_sql_array as $k=>$v){
				$shop_time_sql .= "update `sr_shop_time` set `type`=".$v['time']." where `id`=".$v['id'].";"; 
			}
			//执行sql语句
			$Model = M();
			$result = $Model->execute($shop_time_sql);
			if(is_numeric($result)){
				$this->success('修改成功！',U('User/Schedule/index'));
			}else{
				$this->error('修改失败！',U('User/Schedule/index'));
			}
		}else{
			//将店铺的日程按照一天中的三个时间段组织起来
			$shop_date_programme = array();
			if(empty($shop_time)){
				createSchedule(session('home_shop_id'));
				$shop_time = get_result($this->shop_time,$map,'','time,week asc');
			}
			foreach($shop_time as $k=>$v){
				if($v['time']==1){
					$shop_date_programme[1][]=$v;
				}else if($v['time']==2){
					$shop_date_programme[2][]=$v;
				}else if($v['time']==3){
					$shop_date_programme[3][]=$v;
				}
			}
			$data['shop_date_programme'] = $shop_date_programme;
			$this->assign($data);
			$this->display();
		}
		
	}
}