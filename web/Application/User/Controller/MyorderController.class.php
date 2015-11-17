<?php
namespace User\Controller;
use User\Controller\BaseController;
class MyorderController extends BaseController {
	protected $table='orders';							/*订单表*/
	protected $model ='OrderView';						/*订单查询视图*/
	protected $comment = 'comment';						/*评论信息*/
	protected $comment_image = 'comment_image';			/*评论晒图信息表*/
	protected $refund_model = 'RefundView';				/*退款查询视图模型*/
	protected $refund_table = 'refund';					/*退款文字信息表*/
	protected $shop_table = 'shop';						/*店铺信息表*/
	protected $history_table = 'order_history';			/*订单状态历史记录表*/
	protected $refund_files = 'refund_files';			/*退款文件信息表*/
	protected $files_complete = 'files_complete';		/*翻译完成文件表*/
	protected $member_table = 'member';					/*用户表*/
	protected $limit=10;
	protected $good_comment=0;
	protected $mid_comment=0;
	protected $bad_comment=0;
	protected $product_type;							/*商品分类*/
	protected $order_status = array( 					/*订单状态*/
			"0"=>"已取消",
			"1"=>"未付款",
			"2"=>"已付款",
			"3"=>"已完成",
			"4"=>"待退款",
			"5"=>"已退款",
			"6"=>"翻译完成",
			"7"=>"已确认",
	);
	
	public function __autoload(){
		parent::__autoload();
		$this->product_type = list_to_tree(get_ability_cache());		/*获取缓存的分类属性转换为树状*/
	}
	
	/**
	 * 订单列表页
	 * @author						李东
	 * @date						2015-06-29
	 */
    public function index(){
   		$gets = I('get.');
    	if($gets['title']){
    		$map['title']=array('like','%'.$gets['title'].'%');
		}
    	if ($gets['type'] && $gets['type']!='all'){
    		/*如果筛选条件不是全部，则按条件添加搜索*/
    		if($gets['type'] == 3){
    			$map['status'] = array('in','3,5');
    		}else if($gets['type']==2){
    			$map['status'] = array('in','2,4');
    		}
    	}else{
    		$map['order_status'] = array('gt','-1');
    	}
		if($gets['status']){
			$map['status']=$gets['status'];
		}
		if($gets['order_num']){
			$map['order_num']=array('like','%'.$gets['order_num'].'%');
		}
		if($gets['add_time']){
			$map['add_time']=array('like','%'.$gets['add_time'].'%');
		}
    	/*获取登录用户ID*/
    	$member_id = session('home_member_id');
    	if(is_numeric($gets['status'])){
    		$map['order_status'] = $gets['status'];
    	}
    	$map['member_id'] = $member_id;
    	$map['order_type'] = 0;
    	$order = ' add_time desc';
    	/*获取格式为 id=>title 的产品分类数组*/
    	$product_type=id_and_text($this->product_type);   
    	/*查询所有订单*/ 	
    	$result = $this->page(D($this->model),$map,array('add_time'=>desc),$field=array(),$this->limit);/* ($model,$map=array(),$order='',$field=array(),$limit='') */
    	/*将数字状态赋值为文字状态*/
    	$result=int_to_string($result,array("order_status"=>$this->order_status,'product_type'=>$product_type));
    	/* 0已取消，1未付款，2已付款，3已完成，4待退款，5已退款 */
    	
    	/*统计全部订单数*/
    	$all_count = M($this->table)->where(array('member_id'=>$member_id,'type'=>'0'))->count();
    	/*统计已完成订单数*/
    	$completed_count = M($this->table)->where(array('member_id'=>$member_id,'status'=>array('in','3,5'),'type'=>'0'))->count();
    	/*统计惊进行中订单数*/
    	$processing_count = M($this->table)->where(array('member_id'=>$member_id,'status'=>array('in','2,4'),'type'=>'0'))->count();
    	
    	$data['all_count']=$all_count;
    	$data['completed_count']=$completed_count;
    	$data['processing_count']=$processing_count;
    	$data['result']=$result;
    	$data['order_status']=$this->order_status;
		$data['map']=array(
			'order_num'=>I('get.order_num'),
			'add_time'=>I('get.add_time'),
			'status'=>I('get.status'),
		);
// 		dump($data);die;
    	$this->assign($data);
        $this->display();
    }
    
    /**
     * 删除订单
     * 判断依据：
     * 可删除订单为：已取消订单
     * 是批量删除，需要将所有id数据查询出来判断是否为可删除数据
     * 将不能删除的条数累加，可删除的删除
     *
     * @author					李东
     * @date					2015-06-30
     */
    public function del(){
    	$ids = I('ids');
    	if(is_array($ids)){
    		/*将所传所有ID查询出来*/
    		$all_result = get_result($this->table,array('id'=>array('in',$ids)));
    		$cannot_del = 0;/*不能删除的条数*/
    		$will_del = array();
    		foreach($all_result as $row){
    			if($row['status']==0){
    				/*储存可以删除的ID*/
    				$will_del[]=$row['id'];
    			}else{
    				/*累加不能删除的条数*/
    				$cannot_del++;
    			}
    		}
    		if(!empty($will_del)){
    			$_POST=array(
    					'status'=>'-1',
    			);
    			$result_del = update_data($this->table,array(),array('id'=>array('in',$will_del)));
    		}
    		if($cannot_del>0){ 
    			/*如果有不能删除的数据*/
    			$result = array('status'=>'0','msg'=>$cannot_del.'条信息不可删除，只有取消状态订单才能删除');
    		}else{
    			$result = array('status'=>'1','msg'=>'操作成功');
    		}
    	}elseif(is_numeric($ids)){
    		$info = get_info($this->table,array('id'=>$ids));
    		if($info['status']==0){
    			$result = $this->setStatus('status');
    		}else{
    			$result = array('status'=>'0','msg'=>'该信息不能删除');
    		}
    	}else{
    		$result = array('status'=>'0','msg'=>'请选择操作项');
    	}
    	
    	/*最终返回信息*/
    	if($result['status'] == 1){
    		$this->success('操作成功!',U('index'));
    	}else{
    		$this->error($result['msg']);
    	}
    	
    }
    /**
     * 文件下载
     * @author						李东
     * @date						2015-08-06
     */
    public function downloads(){
    	if(!I('get.id')){
    		$this->error('参数错误',U('index'));
    	}
    	$id = I('get.id');
    	$info = get_info($this->files_complete,array('id' =>$id));
    	$info['title'] = str_replace(' ','_',trim($info['title']));
    	$filename = 'http://'.I('server.HTTP_HOST').__ROOT__.'/'.$info['save_path'];
    	//文件的类型
    
    	$filetype = substr($info['save_path'],strrpos($info['save_path'],'.')+1);
    	$header ="Content-type: application/".$filetype;		//获取文件类型
    
    	header($header);
    	//下载显示的名字
    	$showname = "Content-Disposition: attachment; filename={$info['title']}.{$filetype}";
    	header($showname);
    	readfile($filename);
    	exit();
    }
    /**
     * 查看订单详情
     * @author						李东
     * @date						2015-06-30
     */
    public function detail(){
    	$order_id = I('get.id');
    	if(!$order_id){
    		$this->error('错误操作');
    	}
    	$map['order_id']=$order_id;
    	$order_info = get_info(D($this->model),$map);
    	
    	if($order_info['order_status']==1){
    		redirect(U('/Home/Buy/confirm_quote/',array('order_id'=>$order_id)));
    	}
    	$files = get_result($this->files_complete,array('order_id'=>$order_id));
//     	print_r($files);exit;
    	$order_info['files'] = $files;
    	
    	/*获取格式为 id=>title 的语言分类数组*/
    	$language_text = id_and_text(get_language_cache());    	
    	/*获取格式为 id=>title 的领域分类数组*/
    	$industry_text = id_and_text(get_industry_cache());
    	/*获取格式为 id=>title 的属性分类数组*/
    	$ability_text = id_and_text(get_ability_cache());
    	/*获取格式为 id=>title 的产品分类数组*/
    	$product_type=id_and_text($this->product_type);
    	/*暂时将一维数组转化为二维数组*/
    	$temp[] = $order_info;
    	$temp = int_to_string($temp,array("order_status"=>$this->order_status,'is_need_invoice'=>array('0'=>'否','1'=>'是'),'product_type'=>$product_type,'pay_type'=>array('0'=>'余额支付','1'=>'支付宝支付'),"product_language_id"=>$language_text,"product_to_language_id"=>$language_text,));
    	/*将Json中所有ID转换成文字字符串*/
    	$temp = json_to_chars($temp,array('product_industry_id'=>$industry_text,'product_ability_id'=>$ability_text,));
    	
    	$order_info = $temp[0];/*将二维数组还原成一维数组*/
    	/*获取买家信息*/
    	$buyer_info= get_info('member',array('id'=>$order_info['member_id']));
    	/*获取下单地址信息*/
    	$user_address = get_area_pid($order_info['address_path'].$order_info['address_id']);
    	
    	/*获取发票信息*/
    	if($order_info['is_need_invoice'] == 1){
    		$invoice = get_address_detail($order_info['address_id']);
    	}
    	
    	$address_text = '';
    	foreach($user_address as $row){
    		$address_text .=$row['title'].' ';
    	}
    	$buyer_info['address_text'] =$address_text;
    	$map=array();
    	$map['order_id'] = $order_id;
    	$order_result = get_result('order_history',$map);
    	$order_result = int_to_string($order_result,array('order_status'=>$this->order_status));
		
    	if($order_info['order_status']==4){
    		/*如果订单状态为待退款状态，查询退款原因描述*/  
    		$map['order_id']=$order_id;
    		$refund_result =get_result(D($this->refund_model),$map);
//     		print_r($refund_result);exit;
    		foreach ($refund_result as $k=>$row){
    			if($row['type'] ==1){
    				/*如果是下单用户的信息，查询是否上传凭证文件*/
    				$refund_result[$k]['ask_name'] =$row['username'];
    				$files = get_result($this->refund_files,array('refund_id'=>$row['id']));
    				$files = get_file_type($files);
    				$refund_result[$k]['files'] = $files;
    			}elseif($row['type']){
    				$refund_result[$k]['ask_name'] =$row['shop_title'];
    			}
    		}
    	}
    	$data['invoice']=$invoice;
    	$data['info']=$order_info;
    	$data['result']=$order_result;
    	$data['buyer_info']=$buyer_info;
    	$data['refund_result']=$refund_result;
    
    	$this->assign($data);
    	$this->display();
    }
    public function evaluate(){
		$shop_id = session('home_shop_id');
		//获取筛选数据
		$maps['shop_id'] = $shop_id;
		$maps['status'] = 2;
		//计算店铺的星级？？？？？？
		
		//统计店铺的好评数,中评数，差评数
		$shop_good_comments = get_result($this->comment,array('shop_id'=>$shop_id,'status'=>2,'type'=>1,'pid'=>0));
		$good_comments_num = count($shop_good_comments);
		
		$shop_mid_comments = get_result($this->comment,array('shop_id'=>$shop_id,'status'=>2,'type'=>2,'pid'=>0));
		$mid_comments_num = count($shop_mid_comments);
		
		$shop_bad_comments = get_result($this->comment,array('shop_id'=>$shop_id,'status'=>2,'type'=>3,'pid'=>0));
		$bad_comments_num = count($shop_bad_comments);
		
		$shop_comments = $this->page($this->comment,$maps);
		$shop_comments = int_to_string($shop_comments,array('type'=>array(1=>'好评',2=>'中评',3=>'差评')));
		//将评价和回复结合起来
			//当前页面的回复的id
		foreach($shop_comments as $v){
			$ids.= $v['id'].',';
		}
		if($ids){
			$shop_comments_s = get_result($this->comment,array('shop_id'=>$shop_id,'status'=>2,'pid'=>array('in',trim($ids,','))));
			$shop_comments_s = int_to_string($shop_comments_s,array('type'=>array(1=>'好评',2=>'中评',3=>'差评')));
			if($shop_comments_s){
				foreach($shop_comments_s as $v){
					$shop_comments[]=$v;
				}
				$shop_comments = list_to_tree($shop_comments,'id','pid','_child',0,'id');
			}
		}
		$comments = array(
			'good_comments_num' => $good_comments_num,
			'mid_comments_num' => $mid_comments_num,
			'bad_comments_num' => $bad_comments_num,
			'shop_comments' => $shop_comments,
		);
		
		$this->assign($comments);
		$this->display('order_evaluate');
	}
	//评价回复功能
	public function reply(){
		//接收用户提交的回复信息
		$post = I('post.comment_level');
		
		$this->ajaxReturn(array('comment_level'=>$post));
	}
	
	
	public function confirm(){
		if(IS_GET){
			$gets = I('get.');
			/*获取订单ID*/
			$member_id = session('home_member_id');
			$order_id = $gets['order_id'];
			$map['member_id'] = $member_id;
			$map['id'] = $order_id;
			$info = get_info($this->table,$map);
			
			/*获取初期金额百分比*/
			$precent = C('EARLY_PERCENT')/100;	
			if(!$info){
				$tips_msg = array('status'=>'0','msg'=>'请登录后再操作');
				/* 跳转到输出位置 */
				goto export;
			}
			if($info['status']!=6){
				$tips_msg = array('status'=>'0','msg'=>'该订单无法进行此操作');
				/* 跳转到输出位置 */
				goto export;
			}
			/* 开启事务 */
			$Model = M();
			$Model->startTrans();
			/*将订单状态更新为已确认*/
			$_POST = array(
					'id'=>$gets['order_id'],
					'status'=>'7',
					'step'=>5,
					'confirm_time'=>date('Y-m-d H:i:s'),//@liuqiao增加确认时间
			);
			$result = update_data($this->table);
			/* 查询订单涉及的店铺所属的用户信息 */
			$shop_member_info = get_info(D('MemberShopView'),array('shop_id'=>$info['shop_id']));
			/*金额从平台账户转入商家账户*/
			$platform_info = get_info($this->member_table,array('id'=>C('PLATFORM_ID')));
			$_POST=array(
					'id'=>trim(C('PLATFORM_ID')),
					'withdrawals'=>$platform_info['withdrawals']-$info['total_price']*$precent,
			);
			/*金额从平台账户转出*/
			$result1 = update_data($this->member_table);
			/*金额转入商家账户*/
			$_POST = array(
					'id'=>$shop_member_info['member_id'],
					'withdrawals'=>$shop_member_info['withdrawals']+$info['total_price']*$precent,
			);
			$result2 = update_data('member');
			/*更新店铺的服务次数以及服务时长或字数*/
			$result3 = update_shop_data($info);
			/*将订单变更记录记录到订单历史记录表*/
			$_POST = array(
					'order_id'=>$info['id'],
					'order_status'=>'7',
					'description'=>'用户确认订单',
			);
			$result4 = update_data($this->history_table);
			/*添加商家用户的资金记录*/
			$res4 = add_money_record($info['order_num'], trim(C('PLATFORM_ID')), $shop_member_info['member_id'], $info['total_price']*$precent, 4, 2, $info['pay_type']);
			if(is_numeric($result)&&is_numeric($result1)&&is_numeric($result2)&&is_numeric($result3)&&is_numeric($result4)){
				/*事务提交*/
				$Model->commit();
				F('site_index',null);//清除网站指数缓存
				$tips_msg = array('status'=>'1','msg'=>'操作成功');
			}else{
				/*事务回滚*/
				$Model->rollback();
				$tips_msg = array('status'=>'0','msg'=>'操作失败');
			}
		}else{
			$tips_msg = array('status'=>'0','msg'=>'请求错误！');
		}
		/*输出部分*/
		export:
		if($tips_msg['status']>0){
			$this->success($tips_msg['msg']);
		}else{
			$this->error($tips_msg['msg']);
		}
	}
	
	
	/** 
	 * 同意支付尾款
	 * @author						李东
	 * @date						2015-08-07
	 */
	public function due_pay(){
		if(IS_GET){
			$gets = I('get.');
			$order_id = $gets['order_id'];
			if($order_id){
				$result = pay_balance_due($order_id);
				if($result){
					$tips_msg = array('status'=>'1','msg'=>'操作成功');
				}else{
					$tips_msg = array('status'=>'0','msg'=>'操作失败');
				}
			}else{
				$tips_msg = array('status'=>'0','msg'=>'参数错误');
			}	
		}else{
			$tips_msg = array('status'=>'0','msg'=>'请求错误！');
		}
		export:
		if($tips_msg['status']==1){
			$this->success($tips_msg['msg']);
		}else{
			$this->error($tips_msg['msg']);
		}
	}
	
	
	
	/**
	 * 提交退款请求
	 * 先添加数据到refund表中
	 * 如果上传了文件 再将文件添加到文件表中
	 * 变更订单状态
	 * 将变更记录添加到历史记录表中
	 * @author						李东
	 * @date						2015-07-16
	 */
	public function refund(){
		if(IS_POST){
			$posts = I('post.');
			$order_id = $posts['order_id'];
			$member_id = session('home_member_id');
			
			if(!$member_id){
				$tips_msg = array('status'=>'0','msg'=>'请登录后再操作');
				goto export;
			}
			if(empty($posts['description'])&&empty($posts['save_path'])){
				$tips_msg = array('status'=>'0','msg'=>'请填写退款原因或上传凭证');
				goto export;
			}			
			/*查询订单信息*/
			$info = get_info($this->table,array('id'=>$order_id));
			$shop_id =	$info['shop_id'];						//session('home_shop_id');
			$_POST = array(
					'order_id'=>$order_id,
					'product_id'=>$product_id,
					'member_id'=>$member_id,
					'shop_id'=>$shop_id,
					'description'=>$posts['description'],
					'type'=>'1',
			);
			/*开启事务*/
			$Model = M();
			$Model->startTrans();
			/*将数据添加到退款文字信息表*/
			$res = update_data($this->refund_table);
			if(is_numeric($res)){
				if(!empty($posts['save_path'])){
					/*如果上传了文件凭证，将文件更新到退款文件信息表中*/
					multi_file_upload($posts['save_path'],'Uploads/RefundFiles/'.$member_id,'refund_files','refund_id',$res,'save_path');
				}
				/*将订单状态更新为待退款*/
				$_POST = array(
					'id'=>$order_id,
					'status'=>'4',
				);
				$res2 = update_data($this->table);
				
				/*将订单状态信息跟新到历史记录表中*/
				$_POST = array(
					'order_id'=>$order_id,
					'order_status'=>'4',
					'description'=>'提交退款申请原因描述',
				);
				$res3 = update_data($this->history_table);
				if(is_numeric($res)&&is_numeric($res2)&&is_numeric($res3)){
					/*全部成功提交事务*/
					$Model->commit();
					$tips_msg = array('status'=>'1','msg'=>'添加成功');
				}else{
					/*没有全部成功，回滚事务*/
					$Model->rollback();
					$tips_msg = array('status'=>'0','msg'=>'添加失败');
				}
			}else{
				$tips_msg = array('status'=>'0','msg'=>'添加失败');
			}
			
		}else{
			$tips_msg = array('status'=>'0','msg'=>'请求错误！');
		}
		
		/*输出位置*/
		export:
		if($tips_msg['status']==1){
			$this->success($tips_msg['msg']);
		}else{
			$this->error($tips_msg['msg']);
		}
	}
	
	
	/**
	 * 店铺评论添加
	 * 1.验证用户评论信息是否都填写完整，不完整给出提示，
	 * 2.验证用户评论的订单是否处于可评论状态，不可评论给出提示，
	 * 3.验证用户是否已经评论过，已经评论过给出提示，
	 * 
	 * 开启事务
	 * 4.将评论信息以及计算出的综合评分记录到评论表
	 * 5.点星级信息以及评价总数更新到店铺信息表
	 * 6.将订单评论状态改为已评论
	 * 7.将订单历史记录表数据更新
	 * 4、5、6、7都成功，提交事务，否则回滚事务
	 * 
	 */
	public function comment(){	
		if(IS_POST){
			$posts = I('post.');
			$product_id=$posts['product_id'];
			$order_id = $posts['order_id'];
			$member_id =session('home_member_id');
			$type = $posts['type'];
			if(!in_array(intval($type), array(1,2,3))){
				$tips_msg = array('status'=>'0','msg'=>'请选择给该店铺好/中/差评');
				goto export;
			}
			if(!$posts['speed_star_num']){
				$tips_msg = array('status'=>'0','msg'=>'请为本次服务速度打分');
				goto export;
			}
			if(!$posts['quality_star_num']){
				$tips_msg = array('status'=>'0','msg'=>'请为本次服务质量打分');
				goto export;
			}
			if(!$posts['serve_star_num']){
				$tips_msg = array('status'=>'0','msg'=>'请为本次服务打分');
				goto export;
			}
			if(!$posts['content']){
				$tips_msg = array('status'=>'0','msg'=>'请填写评价内容');
				goto export;
			}
			$map['id'] = $order_id;
			$map['status'] = 3;
			$order_info = get_info($this->table,$map);
			if(!$order_info){
				/*判断订单是否是存在*/
				$tips_msg = array('status'=>'0','msg'=>'订单不存在或不能评论','url'=>U('index'));
				goto export;
			}
			if($member_id != $order_info['member_id']){
				/*判断用户评论的是否是自己的订单*/
				$tips_msg = array('status'=>'0','msg'=>'只能评论自己的订单','url'=>U('index'));
				goto export;
			}
			if($order_info['is_comment']>0){
				/*判断是否已经评论过*/
				$tips_msg = array('status'=>'0','msg'=>'订单已经评论过','url'=>U('index'));	
				goto export;
			}
			/*计算单条评价综合评分*/
			$star_num = round((($posts['speed_star_num']+$posts['quality_star_num']+$posts['serve_star_num'])/3),1);
			/*开启事务*/
			$Model =M();
			$Model->startTrans();
			$posts['content']=get_word_search($posts['content']);
			$_POST = array(
				'order_id'=>$order_id,
				'product_id'=>$order_info['product_id'],
				'shop_id' => $order_info['shop_id'],
				'content'=>$posts['content'],
				'member_id'=>$member_id,
				'type'=>$type,
				'speed_star_num'=>$posts['speed_star_num'],
				'quality_star_num'=>$posts['quality_star_num'],
				'serve_star_num'=>$posts['serve_star_num'],
				'star_num'=>$star_num,
			);
			$res = update_data($this->comment);
			/*店铺表评论数增加1,统计平均星级并更新*/
			$map=null;
			$map['id'] = $order_info['shop_id'];
			$shop_info = get_info($this->shop_table,$map);
			$comment_num = $shop_info['comment_num']+1;
			/*计算平均速度星级*/
			$speed_star_num = ($shop_info['speed_star_num']*$shop_info['comment_num']+$posts['speed_star_num'])/$comment_num;
			/*计算平均质量星级*/
			$quality_star_num = ($shop_info['quality_star_num']*$shop_info['comment_num']+$posts['quality_star_num'])/$comment_num;
			/*计算平均服务星级*/
			$serve_star_num = ($shop_info['serve_star_num']*$shop_info['comment_num']+$posts['serve_star_num'])/$comment_num;
			/*计算平均综合星级*/
			$new_star_num = round((($shop_info['star_num']*$shop_info['comment_num']+$star_num)/$comment_num),1);
			
			$_POST = array(
					'id'=>$order_info['shop_id'],									/*店铺ID*/
					'speed_star_num'=>$speed_star_num,								/*速度星级*/
					'quality_star_num'=>$quality_star_num,							/*质量星级*/
					'serve_star_num'=>$serve_star_num,								/*服务星级*/
					'star_num'=>$new_star_num,										/*综合星级*/
					'comment_num'=>$comment_num,									/*总评论数*/
			);
			$res2 = update_data($this->shop_table);
			
			/*将订单评论状态改为已评论状态*/
			$_POST =array(
					'id' => $order_id,
					'is_comment' => 1,
			);
			$res3 = update_data($this->table);
			/*将订单状态信息跟新到历史记录表中*/
			$_POST = array(
				'order_id'=>$order_id,
				'order_status'=>'7',
				'description'=>'评价订单成功',
			);
			$res4 = update_data($this->history_table);
			
			if(is_numeric($res)&&is_numeric($res2)&&is_numeric($res3)&&is_numeric($res4)){
				/*都执行成功提交事务*/
				$Model->commit();
				if(!empty($posts['save_path'])){
					/*如果上传了图片则将图片更新到评论图片信息表中*/
					multi_file_upload($posts['save_path'],'Uploads/Orders/comment',$this->comment_image,'comment_id',$res,'save_path');
				}
				$cache_name='shop_comment_count_'.$order_info['shop_id'];
				F($cache_name,null);
				$tips_msg = array('status'=>'1','msg'=>'评论添加成功','url'=>U('index'));
			}else{
				/*其中之一执行失败则回滚事务*/
				$Model->rollback();
				$tips_msg = array('status'=>'0','msg'=>'评论添加失败','url'=>U('index'));
			}
			
			
			export:
			if($tips_msg['status'] == 1){
				$this->success($tips_msg['msg'],$tips_msg['url']);
			}else{
				$this->error($tips_msg['msg'],$tips_msg['url']);
			}
			
		}else{
			
			$gets = I('get.');
			$order_id = $gets['order_id'];
			$map['id'] = $order_id;
			$map['status'] = 3;
			$order_info = get_info($this->table,$map);
			
			if($order_info['is_comment']>0){
				/*判断是否已经评论过*/
				$tips_msg = array('status'=>'0','msg'=>'订单已经评论过','url'=>U('index'));
				$status =true;
			}
			
			$coment_count = get_shops_comment_count($order_info['shop_id']);
			if($status){
				$this->error($tips_msg['msg'],$tips_msg['url']);
			}
			$data['order_id']  = $order_id;
			/*@liuqiao 改*/
			if(I('get.type')){
				$map=array(
				'type'=>I('get.type'),
				);
			}
			else{
				$map=array(
				'type'=>array(in,array(1,2,3)),
				);
			}
			$comments = $this->get_shop_comments();
			//dump($comments);
			$this->assign($comments);
			//$data['goods_results']=get_result('comment',$map);//查出该产品的所有评价
			$data['coment_count']=$coment_count;
			$data['type']=I('get.type');
			$this->assign($data);
			$this->display();
		}
		
	}
	

	
	
	/**
	 * ajax删除文件
	 * 
	 */
	function ajaxDelete_refund_files(){
		if(IS_POST){
			$posts=I("post.");
			$info=get_info($this->refund_file,array("id"=>$posts['file_id']));
			dump($info);
			die;
			$path=$info['save_path'];
			$_POST=null;
			F($this->cache_name,null);
			if(file_exists($path)){
				if(@unlink($path)){
					$_POST['id']=$posts['file_id'];
					$_POST['save_path']='';
					/*清空数据表的储存字段*/
					//update_data($this->refund_file,array("id"=>$posts['file_id']));
					/*直接删除文件表中数据*/
					delete_data($this->refund_file,array("id"=>$posts['file_id']));
					$this->success("删除成功");
				}else{
					$this->error("删除失败");
				}
			}else{
				$_POST['id']=$posts['id'];
				$_POST['save_path']='';
				/*清空数据表的储存字段*/
				//update_data($this->refund_file,array("id"=>$posts['file_id']));
				/*直接删除文件表中数据*/
				delete_data($this->refund_file,array("id"=>$posts['file_id']));
				$this->success("文件不存在，删除失败，数据被清空");
			}
		}
	}
    
	function get_shop_comments(){
		$get = I('get.');
		$sort = 'comment.add_time desc';
		$maps["type"]=array('in',array(1,2,3));
		if(!empty($get)){
			if($get['type']!=0){//0表示全部
				$maps['type'] = $get['type'];
			}
		}
		//获取筛选数据
		$maps['pid'] = 0;//表示正常的回复

		$maps["status"]=2;//审核通过并被启用的评论
		
		//查询当前订单信息
		$order_id = intval(I("get.order_id"));
		$order_info = get_info("orders",array("id"=>$order_id));
		//dump($order_info);
		$maps["product_id"]=$order_info["product_id"];
		
		//统计店铺的好评数,中评数，差评数
		$shop_good_comments = get_result($this->comment,array('product_id'=>$order_info['product_id'],'status'=>2,'type'=>1,'pid'=>0));
		$good_comments_num = count($shop_good_comments);
		
		$shop_mid_comments = get_result($this->comment,array('product_id'=>$order_info['product_id'],'status'=>2,'type'=>2,'pid'=>0));
		$mid_comments_num = count($shop_mid_comments);
		
		$shop_bad_comments = get_result($this->comment,array('product_id'=>$order_info['product_id'],'status'=>2,'type'=>3,'pid'=>0));
		$bad_comments_num = count($shop_bad_comments);
		
		$all_comments_num = $good_comments_num+$mid_comments_num+$bad_comments_num;//所有评价的次数
		
		
		$shop_comments = $this->page(D('CommentProductView'),$maps,$sort);
		//dump($shop_comments);
		//将语言和评论关联起来
		$language_data = get_language_cache();
		$language_data = array_id_key($language_data);
		//将所属技能与评价关联
		$get_serve_cache = get_serve_cache();
		$get_serve_cache = array_id_key($get_serve_cache);
		foreach($shop_comments as $k=>$v){
			$shop_comments[$k]['language_text'] = $language_data[$v['language_id']]['title'];
			$shop_comments[$k]['to_language_text'] = $language_data[$v['to_language_id']]['title'];
			$shop_comments[$k]['serve_text'] = $get_serve_cache[$v['product_type']]['title'];
			//查询当前评论的图片
			$image=get_result("comment_image",array("comment_id"=>$v["id"]));
			$comments_image[$v["id"]]=$image;
		}
		//将评价和回复结合起来
		//当前页面的回复的id
		foreach($shop_comments as $v){
			$ids.= $v['id'].',';
		}
		if($ids){
			$shop_comments_s = get_result($this->comment,array('product_id'=>$order_info['product_id'],'status'=>2,'pid'=>array('in',trim($ids,','))));
			$shop_comments_s = int_to_string($shop_comments_s,array('type'=>array(1=>'好评',2=>'中评',3=>'差评')));
			if($shop_comments_s){
				foreach($shop_comments_s as $v){
					$shop_comments[]=$v;
				}
				$shop_comments = list_to_tree($shop_comments,'id','pid','_child',0,'id');
			}
		}
		$comments = array(
				'good_comments_num' => $good_comments_num,
				'mid_comments_num' => $mid_comments_num,
				'bad_comments_num' => $bad_comments_num,
				'all_comments_num' => $all_comments_num,
				'shop_comments' => $shop_comments,
				'comments_image' =>	$comments_image,
		);
		return $comments;
	}
}