<?php
namespace Backend\Controller\Finance;
use Backend\Controller\Base\AdminController;
class BalanceController extends AdminController {
	protected $table='money_record';
	protected $action_filed='id,title';
	protected $model = 'MoneyView';
	protected $detail_table = 'funds_detail';
	
	public function index(){
		
		$map['type'] = '3';
		$map['status'] =array('gt','-1');
		$result = $this->page(D($this->model),$map,' add_time desc');
// 		print_r($result);
// 		exit;
		
		$result = int_to_string($result,array('type'=>array('1'=>'充值','2'=>'在线购买','3'=>'提现',),'recharge_type'=>array('1'=>'支付宝支付','2'=>'微信支付')));
		
		$data['result']=$result;
		$this->assign($data);
		$this->display();
	}
	
	/**
	 * 完成提现
	 * 1.更改记录状态为已成功
	 * 2.添加平台资金记录
	 * @author						李东
	 * @date						2015-08-08
	 */
	public  function drawals(){
		if(IS_GET){
			$id = I('get.record_id');
			$record_info = get_info($this->table,array('id'=>$id));
			if($record_info['status']==0||!empty($record_info)){
				/* 开启事务 */
				$Model = M();
				$Model->startTrans();
				/* 更改记录状态 */
				$_POST = array(
						'id'=>$id,
						'status'=>'1',
				);
				$res = update_data($this->table);
				/* 添加平台自己记录 */
				$res2 = platform_funds($record_info['from_member_id'], -$record_info['money'], 3, $record_info['order_num'], 0);
				if(is_numeric($res)&&$res2){
					/* 事务提交 */
					$Model->commit();
					$this->success('操作成功');
				}else{
					/*事务回滚*/
					$Model->rollback();
					$this->error('操作失败');
				}				
			}elseif($record_info['status']==1){
				$this->error('已支付');	
			}elseif($record_info['status']==2){
				$this->error('此订单已退款');
			}else{
				$this->error('订单不存在');
			}
		}else{
			$this->error('请求错误');
		}
	}
	
	
	/**
	 * 提现退款
	 * 给用户账户退款
	 * 更新记录状态
	 * 更新用户资金记录
	 * @author				李东
	 * @date				2015-08-08
	 */
	public  function refund_drawals(){
		if(IS_GET){
			$id = I('get.record_id');
			$record_info = get_info($this->table,array('id'=>$id));
			if($record_info['status']==0||!empty($record_info)){
				$user_info = get_info('member',array('id'=>$record_info['from_member_id']));

				/* 开启事务 */
				$Model = M();
				$Model->startTrans();
				/* 给提现用户退款 */
				$_POST = array(
					'id'=>$record_info['from_member_id'],
					'withdrawals'=>$user_info['withdrawals']-$record_info['money'],
				);
				$res = update_data('member');

				/* 更改记录状态 */
// 				$_POST = array(
// 						'id'=>$id,
// 						'status'=>'2',
// 				);
// 				$res2 = update_data($this->table);
				
				/*更新用户资金记录*/
				$_POST = array(
						'type'=>'5',
						'frozen'=>'2',
						'status'=>'4',
						'member_id'=>$record_info['from_member_id'],
						'money'=>-$record_info['money'],
						'description'=>'提现退款',
						'order_num'=>$record_info['order_num'],
				);
				$res3 = update_data($this->table);

				if(is_numeric($res)&&is_numeric($res3)){
					$Model->commit();
					$this->success('操作成功');
				}else{
					$Model->rollback();
					$this->error('操作失败');
				}				
			}elseif($record_info['status']==1){
				$this->error('已支付');	
			}elseif($record_info['status']==2){
				$this->error('此订单已退款');
			}else{
				$this->error('订单不存在');
			}			
		}else{
			$this->error('请求错误');
		}		
	}
	
}
