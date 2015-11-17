<?php
namespace User\Controller;
use User\Controller\BaseController;
use Think\Page;
class MyTrackController extends BaseController {
	private $table = 'orders';
	
	/**
	*我的脚印
	*	显示店铺经营的一些数据统计
	*		1、店铺中交易成功的金额
	*		2、翻译的字数统计
	*		3、口译小时数统计
	*		4、音频翻译分钟数统计
	**/
    public function index(){
        $apptype = (!empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY')) ?true:false;//手机app接口密钥  
		if($apptype){
		  $app_key=trim(I("post.key"));//md5加密的登录时间
	      $member_id=I("post.home_member_id");	
	      $this->isLoginExpire($app_key,$member_id);//判断登录过期
		}else{
    	  $member_id = session('home_member_id');
    	}
		//查询出来属于这个店铺的订单的数据
		//$map['member_id'] = $member_id;
		$map=array(
			'member_id'=> $member_id,
			'status'=>array(in,array(3,6,7)),
		);
		$shop_order_data = get_result($this->table,$map);
		
		//处理数据
		$shop_data = $this->shop_data($shop_order_data);
		if($apptype){
			$this->ajaxReturn($shop_data);
		}
		$this->assign($shop_data);
		$this->display();
    }
    private function shop_data($result){
		//统计店铺的成交金额
		$money_num = 0;
		foreach($result as $row){
			$money_num += $row['total_price'];
		}
		//统计店铺的翻译字数
		$words_num = 0;
		$hours_num = 0;
		$voice_num = 0;
		foreach($result as $row){
			if($row['product_type'] ==36){//表示文档翻译
				$words_num += 1000*$row['qty'];//订单表中的数量是以千字作为单位的
			}else if($row['product_type']==56){//表示音频翻译
				$voice_num += $row['qty'];//暂时以分钟数作为单位
			}else if($row['product_type']==37){//表示口译
				$hours_num += $row['qty'];//口译在订单中译以小时数计数
			}
		}
		$shop_data = array(
			'money_num' => $money_num,
			'words_num' => $words_num,
			'hours_num' => $hours_num,
			'voice_num' => $voice_num
		);
		return $shop_data;
	}
}