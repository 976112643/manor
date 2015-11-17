<?php

namespace Home\Controller;

use Home\Controller\HomeController;
use User\Controller\UpdateImgController;
use Boris\ExportInspector;

class BuyController extends HomeController {
	protected $table = 'orders'; /* 主要操作表 */
	protected $history_table = 'order_history'; /* 订单状态历史记录表 */
	protected $product_table = 'products'; /* 商品表 */
	protected $model = 'OrderView'; /* 数据视图模型 */
	protected $member_table = 'member'; /* 用户信息表 */
	protected $money_table = 'money_record'; /* 资金流动记录表 */
	protected $product_type; /* 商品分类 */
	protected $first_step_view; /* 不同分类调用的下单第一步的页面 */
	public function __autoload() {
		parent::__autoload ();
		$this->product_type = list_to_tree ( get_ability_cache () ); /* 获取缓存的分类属性转换为树状 */
		$this->first_step_view = array (
				'36' => 'step_1_written',					/*笔译下单第一步模板*/
				'37' => 'step_1_interpret',				/*口译下单第一步模板*/
				'56' => 'step_1_audio',					/*音频翻译下单第一步模板*/
		);
	}
	public function index() {
		$gets = I ( 'get.' );
		$product_id = $gets ['product_id'];
		$member_id = session ( 'home_member_id' );
		
		/* 获取所选商品信息 */
		$map ['id'] = $product_id;
		$product_info = get_info ( $this->product_table, $map );
		$product_type = $product_info ['type'];
		if (! $product_type) {
			$this->error ( '产品类型错误' );
		}
		if (intval ( $product_type )) { // 文件上传插件第二次加载页面时的报错
			/* 确定要加载的第一步的页面 */
			session ( 'buy_first_view', $this->first_step_view [$product_type] );
		}
		$temp ['0'] = $product_info;
		/* 获取格式为 id=>title 的语言分类数组 */
		$language_text = id_and_text ( get_language_cache () );
		/* 获取格式为 id=>title 的领域分类数组 */
		$industry_text = id_and_text ( get_industry_cache () );
		/* 获取格式为 id=>title 的属性分类数组 */
		$ability_text = id_and_text ( get_ability_cache () );
		/* 获取所有属性,并将键值转换为子数组的ID */
		$ability_all = array_id_key ( get_ability_cache () );
		/* 获取所有行业,并将键值转换为子数组的ID */
		$industry_all = array_id_key ( get_industry_cache () );
		$temp = int_to_string ( $temp, array (
				"language_id" => $language_text,
				"to_language_id" => $language_text 
		) );
		/* 将二维数组还原成一维数组 */
		$product_info = $temp [0];
		
		/* 将行业ID转换为包含title的数组 */
		$product_info ['industry_id_arr'] = int_to_arr (json_decode($product_info['industry_id'], true ), $industry_all );
		/* 将属性ID转换为包含title的数组 */
		$product_info ['ability_id_arr'] = int_to_arr (json_decode($product_info ['ability_id'], true ), $ability_all );
		
		// print_r($product_info);
		
		$address_result = get_address_info ( $member_id );
		
		$data ['address_result'] = $address_result;
		$data ['product_info'] = $product_info;
		
		$this->assign ( $data );
		
		// 根据数据表中的字段获取地址信息
		$area = get_area_cache ();
		$data ['area'] = $area;
// 		dump($data['product_info']);die;
		$this->assign ( 'data', $data );
		/* 按照所传产品类型确定第一步加载的页面 */
		$this->display ( session ( 'buy_first_view' ) );
	}
	
	/**
	 * 下订单
	 * 判断是否是POST提交
	 * 如果是POST提交则进行下单操作
	 * 否则 提示错误
	 *
	 *
	 * @author 李东
	 *         @date						2015-07-09
	 */
	public function add_order() {
		if (IS_POST) {
			$posts = I ( 'post.' );
			// print_r($posts);exit;
			/* 获取当前登录的用户的ID */
			$member_id = session ( 'home_member_id' );
			
			$rules = array (
					
					// array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
					array (
							'qty',
							'/^[1-9]\d*(\.\d+)?$/',
							'数量必须大于1',
							'regex' 
					) 
			); // 默认情况下用正则进行验证
			
			if (! $posts ['agree']) {
				$tips_msg = array (
						'status' => '0',
						'msg' => '请同意保密协议' 
				);
				goto export;
				/* 直接跳转到输出位置 */
			}
			/* 如果需要发票，判断发票信息是否填写 */
			if ($posts ['need_invoice']) {
				if (empty ( trim ( $posts ['invoice'] ) )) {
					$tips_msg = array (
							'status' => '0',
							'msg' => '请填写发票抬头' 
					);
					goto export;
				}
				if (intval ( $posts ['address_id'] ) < 1) {
					$tips_msg = array (
							'status' => '0',
							'msg' => '请选择发票寄送地址' 
					);
					goto export;
				}
			}
			
			$product_info = get_info ( $this->product_table, array (
					'id' => $posts ['product_id'] 
			) );
			$shop_id = $product_info ['shop_id']; 			/* 获取店铺ID */
			$product_type = $product_info ['type']; 		/* 获取产品类型 */
			$address_path = '';
			$area_detail = '';
			$product_title = $product_info['title'];		/*获取产品title*/
			$product_description = array ();
			if (empty($posts['expected_start_time'] ) || empty($posts['expected_end_time'])) {
				$tips_msg = array ('status' => '0','msg' => '请填写完整预约服务时间');
				goto export;
			}
			if($posts['expected_start_time']>=$posts['expected_end_time']){
				$tips_msg = array ('status' => '0','msg' => '结束时间必须大于开始时间');
				goto export;
			}
			if ($product_type == 37) {
				
				/* 判断是否填写了详细信息 */
				if (empty ( trim ( $posts ['area_detail'] ) )) {
					$tips_msg = array ('status' => '0','msg' => '请填写详细地址');
					goto export;
				} elseif (empty ( $posts ['addpath'] )) {
					$tips_msg = array (
							'status' => '0',
							'msg' => '请选择服务地址' 
					);
					goto export;
				} else {
					/* 选择服务地址的path */
					$address_path = $posts ['addpath'] . $posts ['area'];
					$area_detail = $posts ['area_detail'];
				}
			} else {
				if (! $posts ['language_sbt']) {
					$tips_msg = array (
							'status' => '0',
							'msg' => '请选择翻译模式' 
					);
					goto export;
				}
				/* 判断文件用途 */
				if ($posts ['ability_id'] == 'other') {
					$description = $posts ['ability_other'];
					$product_description ['ability_other'] = $description;
				} elseif (is_numeric ( $posts ['ability_id'] )) {
					$product_description ['ability_id'] = $posts ['ability_id'];
				} else {
					$tips_msg = array (
							'status' => '0',
							'msg' => '请选择服务领域或文件领域' 
					);
					goto export;
				}
			}
			/* 判断专业领域 */
			if ($posts ['industry_id'] == 'other') {
				$description = $posts ['industry_other'];
				$product_description ['industry_other'] = $description;
			} elseif (is_numeric ( $posts ['industry_id'] )) {
				$product_description ['industry_id'] = $posts ['industry_id'];
			} else {
				$tips_msg = array (
						'status' => '0',
						'msg' => '请选择服务领域或文件领域' 
				);
				goto export;
			}
			$expired_time = date ( 'Y-m-d H-i-s', time () + intval ( C ( 'TIME_LEFT' ) ) * 24 * 3600 ); /* 计算失效时间 */
			/* 判断音频翻译是否填网址或者上传文件 */
			if ($product_type != 37) {
				if (empty ( $posts ['save_path'] ) && ! $posts ['files_url']) {
					$tips_msg = array (
							'status' => '0',
							'msg' => '请上传要翻译的文件' 
					);
					goto export;
				}
			}
			
			/* 计算数量(千字/小时) */
			$qty = floatval ( $posts ['qty'] );
			$total_price = $qty * $product_info ['price']; /* 计算总价 */
			$order_num = get_order_num ( intval ( $shop_id ), intval ( $member_id ) );
			// dump($address_path);die;
			$_POST = array (
					'title'=>$product_title,											/* 添加标题*/
					'order_num' => $order_num,									/*订单号*/
	    			'member_id' => $member_id,									/*下单用户*/
    				'shop_id' => $posts ['shop_id'],								/*被下单店铺*/
	    			'product_id' => $posts ['product_id'],							/*被下单产品ID*/
	    			'qty' => $qty,												/*翻译字数或者小时数*/
	    			'product_type' => $product_type,								/*产品类型*/
	    			'total_price' => $total_price,								/*总价*/
	    			'status' => '1',												/*订单设置为未付款状态*/
	    			'expired_time' => $expired_time,								/*订单失效时间*/
	    			'description' => $posts ['description'],						/*用户备注*/
	    			'product_description' => json_encode ( $product_description ),	/*用户备注*/
	    			'address_id' => $posts ['address_id'],							/*用户收货地址ID*/
	    			'requirements' => $posts ['language_sbt'],						/*翻译要求:1纯目标语言提交,2对照提交*/
	    			'is_need_invoice' => $posts ['need_invoice'],					/*是否需要发票*/
	    			'step' => 1,													/*用户操作到第几步*/
	    			'address_path' => $address_path,								/*用户选择地址path*/
	    			'area_detail' => $area_detail,									/*用户填写的详细地址*/
	    			'expected_start_time' => $posts ['expected_start_time'],		/*用户选择的服务开始时间*/
	    			'expected_end_time' => $posts ['expected_end_time'],			/*用户选择的服务结束时间*/
	    			'invoice' => $posts ['invoice'],								/*发票抬头*/
	    			'address_id' => $posts ['address_id'],							/*发票寄送地址*/
	    			'files_url' => $posts ['files_url'],
					'is_confirm_price'=>'1',										/*确认价格*/
					/*音频翻译填写的网址*/
	    	);
	    	/* 开启事务 */
			$Model =M();
			$Model->startTrans();
	    	$result1 = update_data($this->table,$rules);
	    	if(is_numeric($result1)){
	    		$_POST = array(
	    				'order_id'=>$result1,
	    				'order_status'=>1,
	    				'description'=>'下单成功',
	    		);
	    		$result2 = update_data($this->history_table);
	    		if(!empty($posts['save_path'])){
	    			/* 判断是否提交了文件 */
	    			//multi_file_upload($posts['save_path'],'Uploads/UserFiles/'.$member_id,'files','order_id',$result1,'save_path');
	    			$data_array=array();
	    			foreach($posts['save_path'] as $val){
	    				if(!empty(intval($val))){
	    					$data_array[$val]=array(	
	    					  "title"=>$posts["filename_".$val],
	    					  "order_id"=>$result1,
	    					  "member_id"=>$member_id,
	    					  "shop_id"=>$posts['shop_id'],
	    					  "description"=>$posts['description'],							
	    					);
	    				}
	    			}
	    		    multi_file_uploads($posts['save_path'],'Uploads/UserFiles/'.$member_id,'files',$data_array,'save_path');
	    		}
	    		/*发送订单消息*/
	    		$result3 = order_msg_send($member_id, $result1, $order_num,$shop_id);
	    		if(is_numeric($result1)&&is_numeric($result2)&&is_numeric($result3)){
	    			/*提交事务*/
	    			$Model->commit();	
					/*@刘巧 给订单用户发送短信 */
					$shop_info=get_info('shop',array('shop_id' => $posts ['shop_id']),array('title'));
					$content=C('ORDSUCCESS');
					$content2=C('MESSAGE_SHOP');
					$content=str_replace("（0）", $total_price, $content);//替换金额;
					$content=str_replace("（1）",$shop_info['title'], $content);//替换商家电铺字符串
					$content2=str_replace("（0）",$total_price, $content2);//替换订单用户字符串
					$content2=str_replace("（2）",session ( 'username' ), $content2);//替换订单用户字符串
					$account=session ( 'home_member_tel' );
					$stauts1=send_code($content,$account);
					/*@liuqiao 给商家用户发送短信*/
					$stauts2=send_code($content1,$account1);
	    			$tips_msg = array('status'=>'1','msg'=>'下单成功','order_id'=>$result1);
	    		}else{
	    			/*事务回滚*/
	    			$Model->rollback();
	    			$tips_msg = array('status'=>'0','msg'=>'下单失败');
	    		}
	    		
	    		
	    	}else{
	    		$tips_msg = array('status'=>'0','msg'=>$result1);
	    	}
    	}else{
    		$tips_msg = array('status'=>'0','msg'=>'错误请求');
    	}
    	
    	
    	export:
    	/*判断需要展示的web提示信息*/
    	if($tips_msg['status']){
    		$this->success($tips_msg['msg'],U('/Home/Buy/confirm_quote',array('order_id'=>$tips_msg['order_id'])));
    	}else{
    		$this->error($tips_msg['msg']);
    	}
    }
    
    
    /**
     * 确认报价
     * 判断是否有权限进行确认报价(只有被下单店铺才有接单权限)
     * 
     * 只有下单用户跟接单店铺才有查看订单信息的权限
     * 查询出订单信息
     * @author						李东
     * @date						2015-07-10
     */
    public function confirm_quote(){
    	if(IS_POST){
    		/*判断是否是表单提交*/
    		$posts = I('post.');
    		
    		$shop_id = session("home_shop_id");
    		$order_id = $posts['order_id'];
    		$price = $posts['total_price'];
    		$rules = array(
    				array('expected_start_time','require','请设置开始时间',1), 	//默认情况下用正则进行验证
    				array('expected_end_time','require','请设置结束时间',1), 	//默认情况下用正则进行验证
    				array('grade','require','请设置难度等级',1), 	//默认情况下用正则进行验证
    		);
    		if($shop_id&&$order_id){
    			/*判断是否是商家提交*/
    			$map['id'] = $order_id;
    			$order_info = get_info($this->table,$map);
    		}else{
    			$tips_msg = array('status'=>'0','msg'=>'无权进行该操作');
    			/*跳转到输出位置*/
    			goto export;
    		}
    		if($order_info['status']>1){
    			$tips_msg = array('status'=>'0','msg'=>'本订单已经支付过，无法修改价格');
    			/*跳转到输出位置*/
    			goto export;
    		}
    		if(!floatval($price)){
    			$tips_msg = array('status'=>'0','msg'=>'请填写大于零的数字');
    			/*跳转到输出位置*/
    			goto export;
    		}
    		if(str_replace('-', '', $posts['expected_start_time'])>=str_replace('-', '', $posts['expected_end_time'])){
    			$tips_msg = array('status'=>'0','msg'=>'结束时间必须大于开始时间');
    			/*跳转到输出位置*/
    			goto export;
    		}
    		if(!$order_info){
    			$tips_msg = array('status'=>'0','msg'=>'无权进行该操作');
    			/*跳转到输出位置*/
    			goto export;
    		}else{
    			$_POST = null;
    			if($order_info['total_price'] !=$price){
    				/*如果修改了价格，则将修改后的价格更新到数据表*/
    				$_POST['total_price'] = $price;
    			}
    			/*将订单设置为已确认价格*/
    			
    			$_POST['is_confirm_price']=1;
    			$_POST['id'] = $order_id;
    			$_POST['grade'] = $posts['grade'];
    			$_POST['expected_start_time'] = $posts['expected_start_time'];
    			$_POST['expected_end_time'] = $posts['expected_end_time'];
    			/*将数据更新到数据库*/
    			$result = update_data($this->table,$rules); 
    			if(is_numeric($result)){
    				$_POST = array(
    						'order_id'=>$order_id,
    						'order_status'=>'1',
    						'description'=>'商家确认报价为:'.$price.'元',
    				);
    				update_data($this->history_table); 
    				$tips_msg = array('status'=>'1','msg'=>'操作成功','url'=>U('User/Order/index'));
    			}else{
    				$tips_msg = array('status'=>'0','msg'=>$result);
    			}
    		}
    		export:
    		/*判断需要展示的web提示信息*/
    		if($tips_msg['status']>0){
    			$this->success($tips_msg['msg'],$tips_msg['url']);
    		}else{
    			$this->error($tips_msg['msg']);
    		}
    	}else{
    		$gets = I('get.');
    		$member_id = session("home_member_id");
    		$shop_id = session('home_shop_id');
    		$order_id = $gets['order_id'];
    		if($member_id&&$order_id){
    		
    			/*如果是用户查看*/
    			$map['member_id']=$member_id;
    			$map['id'] = $order_id;
    			/*如果是商家查看订单*/
    			$order_info = get_info(D($this->model),$map);

    			/*获取语言列表*/
    			$language = array_id_key(get_language_cache());
//     			print_r($order_info['to_language_id']);exit;
    			$order_info['product_to_language_id_text'] = $language[$order_info['product_to_language_id']]['title'];
    			$order_info['product_language_id_text'] = $language[$order_info['product_language_id']]['title'];
//     			print_r($order_info);
    			$tips_msg = array('status'=>'1','msg'=>'加载成功','order_info'=>$order_info);
    		}elseif(!$member_id){
    			$tips_msg = array('status'=>'0','msg'=>'请登录后再操作');
    		}else{
    			$tips_msg = array('status'=>'0','msg'=>'错误请求');
    		}
    		
    		if($tips_msg['status']){
    			$data['order_info']=$order_info;
    			$this->assign($data);
    			$this->display('step_2_quote');
    		}else{
    			$this->error($tips_msg['msg']);
    		}
    	}
    }
    
    /**
     * 加载支付页面
     * 用于选择支付方式	
     * @author						李东
     * @date						2015-07-10
     */
    public function pop_pay(){
    	$gets = I('get.');
    	$member_id = session("home_member_id");
    	$order_id = $gets['order_id'];
    	if($member_id&&$order_id){
    		/*判断用户是否登录*/
    		$map['member_id']=$member_id;
    		$map['id'] = $order_id;
    		/*如果是商家查看订单*/
    		$order_info = get_info(D($this->model),$map);
    		
    		$member_info = get_info($this->member_table,array('id'=>$member_id));
    		
    		if(!$order_info){
 				/*如果没有获取到订单信息*/
    			$tips_msg = array('status'=>'0','msg'=>'请登录后再操作');
    			//跳出该if判断,跳转到输出位置
    			goto export;
    		}elseif($order_info['order_status']>1){
    			$tips_msg = array('status'=>'0','msg'=>'该订单无法支付');
    			goto export;
    		}else{
    			$order_info['step'] = 2;/*将用户操作步骤暂时设置为2,当客户完成付款直接到3*/
    		}
    		
    		/*获取语言列表*/
    		$language = array_id_key(get_language_cache());
    		//     			print_r($order_info['to_language_id']);exit;
    		$order_info['product_to_language_id_text'] = $language[$order_info['product_to_language_id']]['title'];
    		$order_info['product_language_id_text'] = $language[$order_info['product_language_id']]['title'];
    		//     			print_r($order_info);
    		$tips_msg = array('status'=>'1','msg'=>'加载成功','order_info'=>$order_info);
    	}elseif(!$member_id){
    		$tips_msg = array('status'=>'0','msg'=>'请登录后再操作');
    	}else{
    		$tips_msg = array('status'=>'0','msg'=>'错误请求');
    	}
    	
    	/*输出位置*/
    	export:
    	
    	if($tips_msg['status']!=0){
    		$data['order_info'] = $tips_msg['order_info'];
    		$data['member_info'] = $member_info;
    		$this->assign($data);
    		$this->display('step_3_payorder');
    	}else{
    		$this->error($tips_msg['msg']);
    	}
    }
    
    
    /**
     * 余额支付
     * 1、判断订单所属用户是否是登录用户，是则可以支付
     * 2、判断用户余额是否足够，足够则可以直接支付，
     * 	   余额不足，查看提现账户，看提现账户余额是否足够支付不足部分，
     * 	   提现账户足够支付剩余部分，则扣除剩余部分，提现账户余额不足以支付，则提示支付失败，余额不足
     * 3、扣除支付用户支付金额之后，生成一条资金流动记录，平台账户资金增加,
     * 
     * @author						李东
     * @date						2015-07-13
     */
    public function balance_pay(){
    	if(IS_POST){
    		$posts = I('post.');
    		$order_id = $posts['order_id'];
    		$dealpassword = $posts['deal_password'];
    		$member_id = session('home_member_id');
    		if(!$member_id){
    			/* 判断用户是否登录 */
    			$tips_msg = array('status'=>'0','msg'=>'请登录后再操作');
    			/*跳转到输出位置*/
    			goto export;
    		}
			
    		$map['id']=$order_id;
    		$map['member_id']=$member_id;
    		/*判断订单是否属于当前用户*/
			$order_info = get_info($this->table,$map);
			if(!$order_info){
				$tips_msg = array('status'=>'0','msg'=>'无权进行该操作');
				/*跳转到输出位置*/
				goto export;
			}
			/*查询登录用户信息*/
			$member_info = get_info($this->member_table,array('id'=>$member_id));
			
			if(md5(md5($dealpassword)) !=$member_info['deal_password']){
				$tips_msg = array('status'=>'0','msg'=>'支付密码错误');
				/*跳转到输出位置*/
				goto export;
			}
			
			/* 开启事务 */
			$Model = M();
			$Model->startTrans();
			/*判断用户消费余额是否足够支付*/
			if($member_info['balance']>=$order_info['total_price']){
				/*账户余额足够支付，则直接扣除余额*/
				$_POST=array(
						'id'=>$member_id,
						'balance'=>$member_info['balance']-$order_info['total_price'],
				);
				/*扣除用户金额*/
				$result1 = update_data($this->member_table);
			}elseif(($member_info['balance']+$member_info['withdrawals'])>=$order_info['total_price']){
				/*余额账户不足，但是加上提现账户足够支付*/
				
				/*计算提现账户需要扣除的金额*/
				$surplus_money = $order_info['total_price'] - $member_info['balance'];
				$_POST = array(
						'id'=>$member_id,
						'balance'=>0,				/*将余额账户清空*/
						'withdrawals'=>$member_info['withdrawals'] -$surplus_money, /*将提现账户扣除余额账户不足部分*/
				);
				/*扣除用户金额*/
				$result1 = update_data($this->member_table);
			}else{
				$tips_msg = array('status'=>'0','msg'=>'余额不足');
				/*直接跳转到输出信息位置*/
				goto export;
			}
			/*查询平台账户信息*/
			$platform_info = get_info($this->member_table,array('id'=>C('PLATFORM_ID')));
			$_POST=array(
					'id'=>trim(C('PLATFORM_ID')),
					'withdrawals'=>$platform_info['withdrawals']+$order_info['total_price'],
			);
			/*金额转入平台账户*/
			$result2 = update_data($this->member_table);
			/*添加资金流动记录*/
			$_POST = array(
					'order_num'=>$order_info['order_num'],
					'frozen'=>0,
					'member_id'=>C('PLATFORM_ID'),
					'money'=>$order_info['total_price'],
					'from_member_id'=>$member_id,
					'description'=>'购买商品支付',
					'type'=>'2',
					'status'=>'3',
			);

			/*更新资金记录表*/
			$result3 = update_data($this->money_table);
			/*将订单状态更新为已支付状态*/
			$_POST = array(
					'id'=>$order_id,
					'status'=>'2',
					'step'=>'3',							/*设置用户的操作步骤*/
					'pay_time'=>date('Y-m-d H:i:s',time()),		
			);
			$result4 = update_data($this->table);
			/*将订单状态记录添加到订单历史记录表中*/
			$_POST =array(
					'order_id'=>$order_id,
					'order_status'=>'2',
					'description'=>'付款成功',
			);
			$result5 = update_data($this->history_table);
			
			/*@liuqiao  购买商品后增加积分*/
			$total_prices=$order_info['total_price'];
			$get_coin=get_coin_points($total_prices,$member_id);
			if(is_numeric($result1)&&is_numeric($result2)&&is_numeric($result3)&&is_numeric($result4)&&is_numeric($result5)&&is_numeric($get_coin)){
				/*判断用户余额，平台余额，资金记录，订单状态，订单状态历史记录是否都更新成功，成功则提交事务*/
				$Model->commit();
				$tips_msg = array('status'=>'1','msg'=>'支付成功','url'=>U('/User/Myorder/index'));
			}else{
				/*如果有任意一个记录为成功执行，则将事务回滚*/
				$Model->rollback();
				$tips_msg = array('status'=>'0','msg'=>'支付失败');
			}
    	}else{
    		$tips_msg = array('status'=>'0','msg'=>'请求错误');
    	}
    	
    	export:
    	/*判断需要展示的web提示信息*/
    	if($tips_msg['status']==1){
    		$this->success($tips_msg['msg'],$tips_msg['url']);
    	}else{
    		$this->error($tips_msg['msg']);
    	}
    }
    
    
    
    
    

}