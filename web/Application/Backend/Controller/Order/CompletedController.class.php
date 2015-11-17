<?php
namespace Backend\Controller\Order;
use Backend\Controller\Base\AdminController;
use Think\Page;
class CompletedController extends AdminController {
	protected $table='orders';
	protected $model ='OrderView';
	protected $limit=15;
	
	/**
	 * 店铺列表
	 * 查询出所有店铺信息
	 * 可以按标题进行搜索
	 * @author						李东
	 * @date						2015-06-12
	 */
	public function index(){
		if(I('title')){
			$map['title']=array('like','%'.I('title').'%');
		}
		$map['order_status'] = 3;
		$map['expired_time'] =array('gt',date('Y-m-d H:i:s'));
		$order = ' order_status asc,order_id desc';
		$result = $this->page(D($this->model),$map,$order,$field=array(),$this->limit);/* ($model,$map=array(),$order='',$field=array(),$limit='') */
		$result=int_to_string($result,array("order_status"=>array("0"=>"已取消","1"=>"未付款","2"=>"已付款","3"=>"已完成","4"=>"待退款","5"=>"已退款")));
		/* 0已取消，1未付款，2已付款，3已完成，4待退款，5已退款 */
		$data['result']=$result;
		$this->assign($data);
		$this->display('index');
	}
	
	
	/**
	 * 退款
	 * 在查看订单详情页面进行退款处理
	 * 需提交参数，
	 * 		退款订单ID(order_id)
	 * 		退款金额(refund_price)
	 * 		操作用户ID(member_id)
	 * @author						李东
	 * @date						2015-06-23
	 */
	public function refund(){
		if(IS_POST){
			$id = I('post.id');
			$refund_price = I('post.refund_price');
			if(!$id){
				$this->error('操作失败');
			}
			if(!$refund_price){
				$this->error('请填写退款金额');
			}
			$username = session('username');
			$description = "管理员({$username})强制退款";
			$status = order_refund($id,$refund_price,$description);
			if($status){
				$this->success('操作成功！',U('index'));
			}else{
				$this->error('操作失败');
			}
		}else{
			$this->error('提交错误');
		}
	}
	
	/**
	 * 查看订单详情
	 * @author						李东
	 * @date						2015-06-23
	 */
	public function detail(){
		$order_id = I('get.id');
		if(!$order_id){
			$this->error('错误操作');
		}
		$map['order_id']=$order_id;
		$order_info = get_info(D($this->model),$map);
		
		$map=array();
		$map['order_id'] = $order_id;
		$order_result = get_result('order_history',$map);
// 		print_r($order_info);
// 		exit;
		$data['info']=$order_info;
		$data['result']=$order_result;
		
		$this->assign($data);
		$this->display();
	}
	
	
}
