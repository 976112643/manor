<?php
namespace Backend\Controller\Order;
use Backend\Controller\Base\AdminController;
use Think\Page;
class OrderController extends AdminController {
	protected $table='orders';
	protected $model ='OrderView';
	protected $files_table = 'files'; 
	protected $limit=15;
	
	/**
	 * 店铺列表
	 * 查询出所有店铺信息
	 * 可以按标题进行搜索
	 * @author						李东
	 * @date						2015-06-12
	 */
	public function index(){
		/*自动确认*/
		check_complete();
		
		if(I('title')){
			$map['order_num']=array('like','%'.I('title').'%');
		}
		$map['order_status'] = array('gt','-1');

		$order = ' add_time desc,order_status asc,order_id desc';
		$result = $this->page(D($this->model),$map,$order,$field=array(),$this->limit);/* ($model,$map=array(),$order='',$field=array(),$limit='') */
		$result=int_to_string($result,array("order_status"=>array("0"=>"已取消","1"=>"未付款","2"=>"已付款","3"=>"已完成","4"=>"待退款","5"=>"已退款",'6'=>'翻译完成','7'=>'已确认',),'product_type'=>array('1'=>'笔译','2'=>'音频翻译','3'=>'口译')));
		/* 0已取消，1未付款，2已付款，3已完成，4待退款，5已退款 ，6翻译完成(等待客户确认)，7用户已确认*/
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
			$result = order_refund($id,$refund_price,$description);
			if($result['status'] == 1){
				$this->success($result['msg'],U('index'));
			}else{
				$this->error($result['msg']);
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
// 		$files = get_result($this->files_table,array('order_id'=>$order_id));
// 		print_r($files);exit;
// 		$order_info['files'] = $files;
		$map=array();
		$map['order_id'] = $order_id;
		$order_result = get_result('order_history',$map);
		
		$data['info']=$order_info;
		$data['result']=$order_result;
		
		$this->assign($data);
		$this->display();
	}
	
	/**
	 * 修改订单状态
	 * @author						李东
	 * @date						2015-08-08
	 */
	public function change_status(){
		if(IS_POST){
			if(in_array(I('status'), array('0','1','2','3','4','5','6','7'))){
				$_POST =array(
					'id'=>I('id'),
					'status'=>I('status')
				);
				$res = update_data($this->table);
				if(is_numeric($res)){
					$this->success('操作成功');
				}else{
					$this->error('操作失败');
				}
			}else{
				$this->error('请选择订单状态');
			}
		}else{
			$this->error('请求错误');
		}
	}
	
	
	public function downloads(){
		if(!I('get.id')){
			$this->error('参数错误',U('index'));
		}
		$id = I('get.id');
		$info = get_info($this->table,array('id' =>$id));
		$info['title'] = str_replace(' ','_',trim($info['title']));
		$filename = 'http://'.I('server.HTTP_HOST').__ROOT__.'/'.$info['download'];
		//文件的类型
	
		$filetype = substr($info['download'],strrpos($info['download'],'.')+1);
		$header ="Content-type: application/".$filetype;		//获取文件类型
	
		header($header);
		//下载显示的名字
		$showname = "Content-Disposition: attachment; filename={$info['title']}.{$filetype}";
		header($showname);
		readfile($filename);
		exit();
	}
	
	
}
