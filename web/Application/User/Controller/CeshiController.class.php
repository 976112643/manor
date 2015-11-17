<?php
namespace User\Controller;
use Home\Controller\HomeController;
use Think\Page;
class CeshiController extends HomeController {
	private $table = 'resources';
	/**
	*鐩稿唽/瑙嗛绠＄悊
	*	鏄剧ず搴楅摵鐨勮棰戝拰鐓х墖
	*@author 鍒樻旦  <371980503@qq.com>
	*@time 2015-07-07
	**/
    public function index(){
    	$laguage = get_language_cache();
    	$laguage2 = array_id_key($laguage);
    	
    
		$this->display();
    }
    /**
	*鐓х墖鎴栬棰慳jax鍒犻櫎
	*@author 鍒樻旦  <371980503@qq.com>
	*@time 2015-07-07
	**/
	public function del(){
		if(session('home_shop_id')){
			$resource_id = I('resources');

			//鏌ヨ璁板綍
			$resource_info = get_info($this->table,array('id'=>$resource_id));
			
			//鍒犻櫎璁板綍
			$result = delete_data($this->table,array('id'=>$resource_id));
			if($result){
				//鍒犻櫎鐪熸鐨勬枃浠�
				unlink($resource_info['img_path']);//鍒犻櫎鐪熺殑鏂囦欢
				$this->ajaxReturn(array('status'=>1,'info'=>'鍒犻櫎鎴愬姛锛侊紒'));
			}else{
				$this->ajaxReturn(array('status'=>0,'info'=>'鍒犻櫎澶辫触锛侊紒'));
			}
		}else{
			$this->ajaxReturn(array('status'=>0,'info'=>'闈炴硶鎿嶄綔锛�'));
		}
		
	}
	public function ajax_update(){
		$resource_id = I('id');
		$title = I('title');
		//淇敼瀵瑰簲鐨刬d鐨則itle
		unset($_POST);
		$_POST['id'] = $resource_id;
		$_POST['title'] = $title;
		$result = update_data($this->table);
		$this->ajaxReturn(session('sql'));
		if(is_numeric($result)){
			$this->ajaxReturn(array('status'=>1,'msg'=>'淇敼鎴愬姛锛侊紒'));
		}else{
			$this->ajaxReturn(array('status'=>0,'mag'=>'淇敼澶辫触锛侊紒'));
		}
	}
	
    
}