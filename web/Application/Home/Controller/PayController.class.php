<?php
namespace Home\Controller;
use Home\Controller\HomeController;
use User\Controller\UpdateImgController;
class PayController extends HomeController {
	protected $table = 'orders';						/*主要操作表*/
	protected $history_table = 'order_history';			/*订单状态历史记录表*/
	protected $product_table = 'products';				/*商品表*/
	protected $model = 'OrderView';						/*数据视图模型*/
	protected $member_table = 'member';					/*用户信息表*/
	protected $money_table = 'money_record';			/*资金流动记录表*/
	protected $product_type;							/*商品分类*/

	
	public  function __autoload(){
		parent::__autoload();
		$this->product_type = list_to_tree(get_ability_cache());		/*获取缓存的分类属性转换为树状*/
	}
	
	/**
	 * 在线支付
	 * 先判断是否是在线充值，在线支付订单需要有订单号；
	 * 查询出订单信息之后将必需信息提交到支付宝；
	 * 
	 * 在线充值需在先生成订单信息，然后再将必需信息提交到支付宝；
	 * 
	 */
	public function index(){
		if(IS_POST){
			$member_id = session('home_member_id');							/*获取充值用户ID*/
			if(!$member_id){
				$this->error('请登录后在操作',U('/User/Login/index'));
			}
			/*接收参数*/
			$posts =I('post.');
			$order_id = $posts['order_id']; 	/*获取订单ID*/
			$paytype = $posts['pay_type'];		/*获取支付方式*/
			
			
			if($order_id){ /*判断是订单支付还是在线充值*/
				/* 如果有订单ID则是订单支付 */
				
				/*获取即将被支付的订单*/
				$info = get_info($this->table,array('id'=>$order_id,'status'=>1));
				if(!$info){
					$this->error('订单不存在或已经支付');
				}
				/*获取订单号*/
				$order_no = $info['order_num'];
				$title = $info['title'];
// 				$title = '商品一';
				$total_price = $info['total_price'];
				$param = array(
					'order_id'=>$order_id,
					'shop_id'=>$info['shop_id'],
					'member_id'=>$info['member_id'],
				);
			}else{
				/* 没有订单ID则是在线充值 */
				
				$total_price = $posts['recharge_money']; 						/*获取充值金额*/
				
				if(floatval($total_price)<=0){
					$this->error('充值金额必须大于0');
				}
				$order_no = get_order_num(C('PLATFORM_ID'), $member_id);
				$title = '账户余额充值';
				$_POST = array(
						'title'=>$title,
						'qty'=>'1',
						'shop_id'=>0,
						'order_num'=>$order_no,
						'member_id'=>$member_id,
						'total_price'=>$total_price,
						'status'=>'1',
						'pay_type'=>'2',
						'description'=>'账户余额充值',
						'type'=>'1',						/*设置单独字段，返回时判断是否需要在用户账户中增加余额*/
				);
				
				$res = update_data($this->table);
				if(is_numeric($res)){
					/*@liuqiao  购买商品后增加积分*/
					$get_coin=get_coin_points($total_price,$member_id);
				}
				$param = array(
						'order_id'=>$res,
						'shop_id'=>0,
						'member_id'=>$member_id,
				);
				if(!is_numeric($res)){
					$this->error('订单生成失败');
				}
			}
			
			if($paytype =='alipay' ){
				$callBack="Pay/pay";
				//$total_price=0.01;//测试完后 删除
				//$param['test']='1';
// echo $paytype,' -- ',$order_no,' -- ',$title,' -- ',$callBack,' -- ',$param;exit;
					
				$callBack="Pay/pay";
				$vo = new \Think\Pay\PayVo();
				$vo->setType($paytype)
				->setBody("支付成功后请不要关闭窗口，等待自动跳转")
				->setFee($total_price) //支付金额
				->setOrderNo($order_no)//本平台支付订单号，
				->setTitle($title)//商品标题
				->setCallback($callBack)//支付完成后的后续操作接口
				->setUrl(U('/Pay/success'))//支付完成后的跳转地址
				->setParam($param);//必要的一些信息，用来产生动态时调用相关数据
					
				$payment_conf=C('payment.' . $paytype);
				$pay = new \Think\Pay($paytype, $payment_conf);
				echo $pay->buildRequestForm($vo);
			}
		}else{
			$paytype='alipay';
			$callBack="Pay/pay";
			$title='异步通知支付测试';
			$total_price=0.01;
			$param['test']='1';
			
			$order_no = get_order_num(1, 1);
			
			$callBack="Pay/pay";
			$vo = new \Think\Pay\PayVo();
			$vo->setType($paytype)
			->setBody("支付成功后请不要关闭窗口，等待自动跳转")
			->setFee($total_price) //支付金额
			->setOrderNo($order_no)//本平台支付订单号，
			->setTitle($title)//商品标题
			->setCallback($callBack)//支付完成后的后续操作接口
			->setUrl(U('/Pay/success'))//支付完成后的跳转地址
			->setParam($param);//必要的一些信息，用来产生动态时调用相关数据
			
			$payment_conf=C('payment.' . $paytype);
			$pay = new \Think\Pay($paytype, $payment_conf);
			echo $pay->buildRequestForm($vo);
		}
	}
	
	
	public function pay($payinfo){
		$posts = $payinfo;
		/*将接收到的参数生成文件Start*/
		open_file($posts,'posts');
		/*将接收到的参数生成文件End*/
		if(!empty($payinfo)){
			$Model = M();
			$Model->startTrans();
			/*将订单历史记录更新*/
			$step = ($payinfo['type'] == 1)?'':3;;
			$_POST = array(
					'order_id'=>$payinfo['id'],
					'description'=>'在线支付成功',
					'order_status'=>'2',
					'step'=>$step,
			);
			$res = update_data($this->history_table);
			/*添加用户资金流动记录*/
			$description = ($payinfo['type'] == 1)?'余额充值':'购买商品支付';
			$type = ($payinfo['type'] == 1)?1:2;
			$_POST = array(
					'order_num'=>$payinfo['order_num'],
					'frozen'=>3,
					'member_id'=>C('PLATFORM_ID'),
					'money'=>$payinfo['total_price'],
					'from_member_id'=>$payinfo['member_id'],
					'description'=>$description,
					'type'=>$type,
					'status'=>3,
					'recharge_type'=>'1',	/*保存支付方式为支付宝支付*/
			);
			$res2 = update_data($this->money_table);
			

			
			$status = true;
			if($payinfo['type'] == 1){
				/*如果是充值，用户账户余额增加*/
				$sql = 'update __MEMBER__ SET balance = balance+'.$payinfo['total_price'].' WHERE id = '.$payinfo['member_id'];
				/*将接收到的参数生成文件Start*/
				open_file($sql,'sql');
				/*将接收到的参数生成文件End*/
				$result = M()->execute($sql);
				if($result<=0){
					$status = false;
				}
			}
			/* 在后台资金记录表中更新记录 */
			$res3 = platform_funds($payinfo['member_id'],$payinfo['total_price'],$type,$payinfo['order_num'],1);
			if(is_numeric($res)&&is_numeric($res2)&&$res3&&$status){
				$Model->commit();
				return true;
			}else{
				$Model->rollback();
				return false;
			}
			
		}		
		return false;
	}
	
	public function success(){
		$data['jumpUrl'] = U('/User/Myorder/index');
		$data['message'] = '支付成功';
		$data['waitSecond'] = 5;
		$this->assign($data);
		$this->display();
	}
	
	
	public function notify(){
		$apitype = 'alipay';
		$pay = new \Think\Pay($apitype, C('payment.' . $apitype));
		if (IS_POST && !empty($_POST)) {
			$notify = $_POST;				
		} elseif (IS_GET && !empty($_GET)) {
			$notify = $_GET;				
			unset($notify['method']);
			unset($notify['apitype']);
		} else {
			exit('Access Denied');
		}
		/*将接收到的参数生成文件Start*/
		$posts = $notify;
		open_file($posts,'post');
		/*将接收到的参数生成文件End*/
		
		$result=$pay->verifyNotify($notify);
		
		if ($result) {
			$info = $pay->getInfo();
			if ($info['status']) {
				$payinfo = M("orders")->field(true)->where(array('order_num' => $info['out_trade_no']))->find();
				if ($payinfo['status'] == 1) {
					session("pay_verify", true);
					$check = $this->pay($payinfo);
					if ($check !== false) {
						$return_notify='';
						if (IS_POST && !empty($_POST)) {
							$return_notify='notify';
						} elseif (IS_GET && !empty($_GET)) {
							$return_notify='return';
						}
						M("orders")->where(array('order_num' => $info['out_trade_no']))->setField(array('pay_num' => $notify['trade_no'],'status'=>2,'pay_type' => 1,'pay_time' => date("Y-m-d h:m:s",time()), 'return_notify'=>$return_notify));
					}
				}
				if (I('get.method') == "return") {
					redirect(U('success'));
				} else {
					$pay->notifySuccess();
				}
			} else {
				$this->error("支付失败！");
			}
			
		} else {
			$this->error("非法访问",'/');
			//E("Access Denied");
		}
	}
}