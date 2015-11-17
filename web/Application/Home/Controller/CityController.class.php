<?php
namespace Home\Controller;
use Home\Controller\HomeController;
class CityController extends HomeController {
	
	/*
	 *思路分析：读取数据库省份里面的信息，然后二级联动查询对应省份中的各个州县
	 *指定所需市，提取下拉框的州县值
	 *选定目标城市，js跳转首页
	 *@author	刘巧  7月6
	 **/
	 
	 
	 /*
	 *首页展示
	 **/
    public function index(){ 
    	$results = get_area_cache();
    	$gets = I('get.');
		$cur_url = str_ireplace('|','/',$gets['cur_url']);
		//session('cur_url',$cur_url);
		$alphabets = array('A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','S','T','W','X','Y','Z');
		/*查询level小于1的数据，用于筛选省市*/
		foreach($results as $k=>$v){
			if($v['level']<2){
				$data[]=$v;
			}
		}
		/*按字母顺序组合城市列表数组*/
		for ($i=A;$i<=Z;$i++){
			foreach($results as $k=>$v){
				if ($i == $v['first_char']&&$v['level']==1){
					$data_city[$i][$k] = $v;
				}
			}
		}
		$this->assign('data',$data);
		//dump($data);die;
		$this->assign('alphabets',$alphabets);
		$this->assign('data_city',$data_city);
		$this->display();
    }
	
	/*@功能分析：
	 *@用户可以搜索，自定义城市，可以按照字母城市表，自定义城市
	 *@城市锁定，用户自定义城市位置，点击首字母，滚动条回自动定位省市对应首字母
	 *7月6
	 **/
	public function lockCity(){
		if(IS_POST){
			$posts = I('post.');
			if(!empty($posts)){
				session('cur_city_id',$posts['id']);
				session('cur_city_path',$posts['path']);
				session('cur_city_pid',$posts['pid']);
				if($posts['id']==0){
					session('cur_city_id',null);
					session('cur_city_path',null);
					session('cur_city_pid',null);
				}
				session('cur_city_title',$posts['title']);
			}
			$gets=explode('/',session('cur_url'));
			foreach($gets as $key=>$val){
				if('city'==$val){
					$gets[$key+1]=$posts['id'];
					break;
				}
			}
			session('cur_url',implode('/', $gets));
			$this->ajaxReturn(array('status'=>'1','msg'=>'成功','url'=>U('Home/Classes/index')));//session('cur_url')
		}
	}
	
}