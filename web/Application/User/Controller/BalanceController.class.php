<?php
namespace User\Controller;
use User\Controller\BaseController;
class BalanceController extends BaseController {
	protected $table = 'money_record';				/*资金记录表*/
	protected $cards_table = 'bank_cards';			/*用户卡包表*/
	protected $model = 'BankcardsView';				/*查询银行卡所属银行*/
	protected $member_table = 'member';				/*用户表*/
    
    /**
	*银行卡列表
	*	显示用户的银行卡信息,并允许添加
	*流程分析
	*	1、将用户的银行卡信息显示成列表
	*	2、调整前端页面隐藏提交表单
	*	3、调整前端页面的验证规则
	*	4、书写逻辑
	*			判断是修改还是添加还是修改
	**/
	public function index(){
		$member_id = session('home_member_id');
		if(IS_POST){
			$posts = I('post.');
			//验证开户人等信息
			$rules = array(
				array('account_user','require','开户人不能为空！'),
				array('account','require','账户不能为空！'),
				array('account_bank','require','开户行不能为空！'),
			);
			//组织添加
			$_POST = array(
				'member_id'=>$member_id,
				'account'=>$posts['account'],
				'account_bank'=>$posts['account_bank'],
				'bank_id'=>$posts['bank_id'],
				'account_user'=>$posts['account_user']
			);
			$result = update_data($this->cards_table,$rules);
			if(is_numeric($result)){
				$this->success('添加成功！',U('User/Balance/index'));
			}else{
				$this->error($result,U('User/Balance/index'),1);
				exit;
			}
		}else{
			//查询显示银行信息
			$bank_data = get_bank_cache();
			//查询用户绑定的银行卡信息
			
			$member_info = get_result(D($this->model),array('member_id'=>$member_id));
			
			$data['bank_data'] = $bank_data;
			$data['member_info'] = $member_info;
			$this->assign($data);
			$this->display();
		}
	}
	/**
	*修改控制器
	*	修改
	**/
	public function update(){
		if(IS_POST){
			$posts = I('post.');
			//验证开户人等信息
			$rules = array(
				array('account_user','require','开户人不能为空！'),
				array('account','require','账户不能为空！'),
				array('account_bank','require','开户行不能为空！'),
			);
			//组织添加
			$_POST = array(
				'id'=>$posts['id'],
				'member_id'=>$member_id,
				'account'=>$posts['account'],
				'account_bank'=>$posts['account_bank'],
				'bank_id'=>$posts['bank_id'],
				'account_user'=>$posts['account_user']
			);
			$result = update_data($this->cards_table,$rules);
			if(is_numeric($result)){
				$this->success('修改成功！',U('User/Balance/index'));
			}else{
				$this->error($result);
				exit;
			}
		}else{
			//查询显示银行信息
			$bank_data = get_bank_cache();
			//接收传值
			$gets = I('get.');
			//将对应的id的信息查询出来
			$map['id'] = $gets['id'];
			$info = get_info(D($this->model),$map);
			
			//将信息显示到页面中
			$data['bank_data'] = $bank_data;
			$data['info'] = $info;
			$this->assign($data);
			$this->display();
		}
	}
	public function del(){
		$id = I('id');
		$result = delete_data($this->cards_table,array('id'=>$id));
		if($result){
			$this->success('删除成功！！',U('User/Balance/index'));
		}else{
			$this->error('删除失败！！',U('User/Balance/index'));
		}
	}
    /**
     * 资金提现
     * @author						李东
     * @date						2015-07-04
     */
    public function drawals(){
    	if(IS_POST){
    		$result = $this->update_money_record();
    		if($result['status'] == 1){
    			$this->success($result['msg'],U('/User/Balance/record'));
    		}else{
    			$this->error($result['msg']);
    		}
    	}else{
     		$member_id = session('home_member_id');
			$map['member_id'] = $member_id;
			/*查询当前登录用户信息*/
			$member_info = get_info($this->member_table,array('id'=>$member_id));
 			$cards_result = get_result($this->cards_table,$map);
			/*获取卡包信息*/
			$cards_result = get_result(D($this->model),$map);
			
			$data['cards_result']=$cards_result;
			$data['member_info']=$member_info;
			$this->assign($data);
    		$this->display();
    	}
    }
    
    public function recharge(){
    	$member_id = session('home_member_id');
    	$map['member_id'] = $member_id;
    	/*查询当前登录用户信息*/
    	$member_info = get_info($this->member_table,array('id'=>$member_id));
    	$cards_result = get_result($this->cards_table,$map);
    	/*获取卡包信息*/
    	$cards_result = get_result(D($this->model),$map);
    		
    	$data['cards_result']=$cards_result;
    	$data['member_info']=$member_info;
    	$this->assign($data);
    	$this->display();
    }
    
    
    protected function update_money_record(){
    	if(IS_POST){
    		$posts =I();
     		$member_id = session('home_member_id');
    		$money = $posts['drawals_money'];		/*提现金额*/
    		$cards_id = $posts['cards_id'];			/*选择提现的账户在卡包中的信息ID*/
    		$order_num =  get_order_num ( intval ( $shop_id ), intval ( $member_id ) );						/*生成订单号*/	
    						
    		if(!trim($posts['dealpassword'])){
    			return array('status'=>'0','msg'=>'请输入支付密码');
    		}
    		if(floatval($money)<=0){
    			return array('status'=>'0','msg'=>'提现金额必须大于0');
    		}
    		if(intval($cards_id)<=0){
    			return array('status'=>'0','msg'=>'请选择提现账户');
    		}
    		/*查询当前登录用户信息*/
    		$info = get_info($this->member_table,array('id'=>$member_id));
    		/*判断用户可提现余额状态*/
    		if($info['wallet_status']==0){
    			return array('status'=>'0','msg'=>'账户已被冻结，请联系管理员');
    		}
    		/*判断用户可提现余额是否充足*/
    		if($info['withdrawals']<$money){
    			return array('status'=>'0','msg'=>'可提现余额不足'.$money.',请重新输入');
    		}
    		
    		/*判断支付密码是否正确*/
    		if(md5(md5($posts['dealpassword'])) == $info['deal_password']){
    			$_POST = array(
    					'type'=>'3',					/*记录资金流动类型为提现*/
    					'frozen'=>'2',					/*记录资金流动账户为可提现账户*/
    					'member_id'=>$member_id,		/*记录提现用户ID*/
    					'money'=>-$money,				/*记录提现金额*/
    					'cards_id'=>$cards_id,			/*记录银行卡信息ID*/
    					'order_num'=>$order_num,		/*记录订单号*/
    					'from_member_id'=>$member_id,	/*记录钱的来源，提现来源为本身账户*/
    					'status'=>'0',					/*设置记录状态为提现等待中*/
    			);
    			/*开启事务*/
    			$Model = M();
    			$Model->startTrans();
    			/*更新资金流动记录*/
    			$result1 = update_data($this->table);
    			/*计算提现后余额*/
    			$new_withdrawals = $info['withdrawals'] - $money;
    			$_POST = array(
    					'id'=>$member_id,
    					'withdrawals'=>$new_withdrawals,
    			);
    			/*更新用户可提现余额*/
    			$result2 = update_data($this->member_table);
    			
    			if(is_numeric($result1)&&is_numeric($result2)){
    				/*如果数据都更新成功，提交事务*/
    				$Model->commit();
    				return array('status'=>'1','msg'=>'申请成功');
    			}else{
    				/*如果有其中一个失败，执行事务回滚*/
    				$Model->rollback();
    				return array('status'=>'0','msg'=>'申请失败');
    			}
    		}else{
    			return array('status'=>'0','msg'=>'支付密码错误');
    		}
    		
    		
    	}else{
    		return array('status'=>'0','msg'=>'错误请求');
    		
    	}
    }
	/**
	*资金管理
	*	用户可以管理自己的资金记录,允许用户取消提现申请
	*流程分析
	*	1、关键词筛选
	*	2、查询用户的资金记录
	*	3、将资金的状态反馈到页面
	*	4、int转化
	*	5、页面显示
	*@aouthor 刘浩 <qq:37298053>
	*@time 2015-07-14
	**/
	public function record(){
		$member_id = session('home_member_id');
		//$order_num = I('order_num');
		$add_time  = I('add_time');
		$status    = I('status');
		//$money	   = I('money');
		$keyword   = I('keyword');
		//关键词筛选
		if($keyword){
			$map['order_num|money'] = array('like',"%$keyword");
			$data['keyword'] = $keyword;
		}
		if($add_time){
			$map['add_time']=array('like',"%$add_time%");
			$data['add_time'] = $add_time;
		}

		if($status){
			$map['status'] = $status;
			$data['status'] = $status;
		}
		//查询用户的资金记录
		//不管是普通的用户还是个人译者还是翻译公司，都将其资金记录添加到会员账户下面
		$map['member_id|from_member_id'] = $member_id; 
		$member_record = $this->page($this->table,$map,array('add_time'=>desc),'',10);

		//将资金的状态反馈到页面
		$status = array(
			0=>'申请提现',
			1=>'提现成功',
			2=>'提现失败',
			3=>'交易成功',
		);
		//将int数据转换成文字信息
		$member_record = int_to_string($member_record,array('type'=>array(1=>'充值',2=>'消费',3=>'提现',4=>'订单收入',5=>'退款'),'frozen'=>array(0=>'余额账户',1=>'冻结账户',2=>'可提现账户',3=>'在线购买'),'status'=>array(0=>'申请提现',1=>'提现成功',2=>'提现失败',3=>'交易成功',4=>'提现取消'),'recharge_type'=>array(0=>'支付宝支付',1=>'微信支付')));
		/* $member_record = int_to_string($member_record,array('frozen'=>array(0=>'余额账户',1=>'冻结账户',2=>'可提现账户')));
		$member_record = int_to_string($member_record,array('status'=>array(0=>'申请提现',1=>'提现成功',2=>'提现失败',3=>'交易成功',4=>'提现取消')));
		$member_record = int_to_string($member_record,array('recharge_type'=>array(0=>'支付宝支付',1=>'微信支付'))); */
		
		//将记录显示到页面中
		$data['member_record'] = $member_record;
		$data['status'] = $status;
		$this->assign($data);
		$this->display();
	}
	/**
	*取消提现
	*	用户通过资金记录页面可以取消提现申请
	**/
    public function abolish(){
		//接收要取消提现的id
		$id = intval(I('id'));
	
		$_POST['id'] = $id;
		$_POST['status'] = 4;//表示取消提现
		//判断原来的数据的状态是否是状态0
		$info = get_info($this->table,array('id'=>$id));
		
		if($info['status']==0){//判断记录是否是等待提现
			$result = update_data($this->table);
		}
		
		if(is_numeric($result)){
			$this->success('取消成功！');
		}else{
			$this->error($result);
		}
	}
}