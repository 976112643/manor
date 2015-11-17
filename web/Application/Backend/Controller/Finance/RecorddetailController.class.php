<?php
namespace Backend\Controller\Finance;
use Backend\Controller\Base\AdminController;
class RecorddetailController extends AdminController {
	protected $table='funds_counts';
	protected $action_filed='id,title';
	protected $model = 'PlatrecordView';
// 	protected $table = 'funds_counts';
	protected $detail_table = 'funds_detail';
	
	
// 	public function index(){
// 		$map['status'] =array('gt','-1');
// 		$result = $this->page($this->table,$map,'add_time');
// 		print_r($result);
// 	}

	
// 	/*
// 	*需求分析
// 	*		需要将用户的资金流动信息显示出来，还要显示一写备注信息，具体看页面
// 	*流程分析
// 	*		1、接收筛选信息
// 	*		2、组织筛选条件
// 	*		3、分页查询
// 	*		4、转换字符串
// 	*/

	
	
	public function index(){
		
		
		$map['status'] =array('gt','-1');
		$result = $this->page(D($this->model),$map,' add_time desc');
// 		print_r($result);
		
		$result = int_to_string($result,array('type'=>array('1'=>'充值','2'=>'在线购买','3'=>'提现',),'recharge_type'=>array('1'=>'支付宝支付','2'=>'微信支付')));
		
		$data['result']=$result;
		$this->assign($data);
		$this->display();
	} 
	
	
// /*@liuqiao  改。将原来的设计进行修改*/
// 	public function index(){
// 		//接收筛选的条件信息
// 		$keywords = I('keywords');
// 		if($keywords){
// 			$keywords = trim($keywords);
// 			$map['today_date'] = array('like',"%$keywords%");
// 			$data['keywords'] = $keywords;
// 		}
// 		//组织筛选条件
// 		$map['status'] = array("eq",1);
// 		$result = $this->page($this->table,$map,'add_time');
// 		foreach($result as $k=>$v){
// 			$num1=substr($v['today_date'],0,4);
// 			$num2=substr($v['today_date'],4,5);
// 			$num2=substr($num2,0,2);
// 			$num3=substr($v['today_date'],6,7);
// 			$result[$k]['today_date']=$num1.'-'.$num2.'-'.$num3;
			
// 		}
// 		$data['result'] = $result;
// 		$this->assign($data);
// 		$this->display();
// 	}
	
}
