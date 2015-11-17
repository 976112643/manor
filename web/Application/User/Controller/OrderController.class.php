<?php
namespace User\Controller;
use User\Controller\ShopBaseController;
use Boris\ExportInspector;
class OrderController extends ShopBaseController {
	protected $table='orders';							/*订单表*/
	protected $model ='OrderView';						/*订单查询视图*/
	protected $comment = 'comment';						/*评论信息表*/
	protected $products = 'products';					/*产品表*/
	protected $comment_product = 'CommentProductView';
	protected $files_table = 'files';					/*待翻译文件表*/
	protected $refund_model = 'RefundView';				/*退款查询视图模型*/
	protected $refund_table = 'refund';					/*退款文字信息表*/
	protected $refund_files = 'refund_files';			/*退款凭证文件表*/
	protected $files_complete = 'files_complete';		/*翻译完成文件表*/
	protected $history_table = 'order_history';			/*订单状态历史记录表*/
	protected $limit=10;								/*每页显示条数*/
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
	 * 订单搜寻页
	 * @author						刘巧
	 * @date						2015-7-29
	 *
	 */

	public function serach(){
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
		if(I('status')){
			$map['status']=I('status');
		}
		if(I('order_num')){
			$map['order_num']=array('like','%'.I('order_num').'%');
		}
		if(I('telephone')){
			$map['member.telephone|member.username']=array('like','%'.I('telephone').'%');
		}
		if(I('from_language')){
			$map['product_language_id']=array('eq',I('from_language'));
		}
		if(I('to_language')){
			$map['product_to_language_id']=array('eq',I('to_language'));
		}
		if(I('add_time')){
			$map['add_time']=array('like','%'.I('add_time').'%');
		}
		$shop_id = session('home_shop_id');
    	$member_id = session('home_member_id');    	
    	$map['shop_id'] = $shop_id;
    	/*默认排序*/
    	$order = ' add_time desc';
    	/*获取格式为 id=>title 的产品分类数组*/
    	$product_type=id_and_text($this->product_type);   
    	/*查询所有订单*/ 	
    	$result = $this->page(D($this->model),$map,array('pay_time'=>desc),$field=array(),$this->limit);/* ($model,$map=array(),$order='',$field=array(),$limit='') */
    	/*将数字状态赋值为文字状态*/
    	$result=int_to_string($result,array("order_status"=>$this->order_status,'product_type'=>$product_type));
    	/* 0已取消，1未付款，2已付款，3已完成，4待退款，5已退款 */
    	
    	/*统计全部订单数*/
    	$all_count = M($this->table)->where(array('shop_id'=>$shop_id))->count();
    	/*统计已完成订单数*/
    	$completed_count = M($this->table)->where(array('shop_id'=>$shop_id,'status'=>array('in','3,5')))->count();
    	/*统计惊进行中订单数*/
    	$processing_count = M($this->table)->where(array('shop_id'=>$shop_id,'status'=>array('in','2,4')))->count();
    	$data['all_count']=$all_count;
    	$data['completed_count']=$completed_count;
    	$data['processing_count']=$processing_count;
    	$data['result']=$result;
    	$data['order_status']=$this->order_status;
		return $data;
	}
	
	/**
	 * 订单列表页
	 * @author						李东
	 * @date						2015-06-29
	 */
	
    public function index(){
		$map['title']=I('title');
		$map['product_type'] = I('type');
		$map['order_num']=I('order_num');
		$map['telephone']=I('telephone');
		$map['from_language']=I('from_language');
		$map['to_language']=I('to_language');
		$map['add_time']=I('add_time');
		$map['status']=I('status');
		//查询语种分类
		$language_data = list_to_tree(get_language_cache());
    	$data=$this->serach();
    	$data['language_data']=$language_data;
		$data['map']=$map;
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
    		/*将所传*/
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
    			$result = array('status'=>'1','msg'=>$cannot_del.'条信息不可删除，只有取消状态订单才能删除');
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
    		$this->success($result['msg']);
    	}
    	
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
    	
    	$files = get_result($this->files_table,array('order_id'=>$order_id));
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
    	$temp = int_to_string($temp,array("order_status"=>$this->order_status,'product_type'=>$product_type,'is_need_invoice'=>array('0'=>'否','1'=>'是'),'pay_type'=>array('0'=>'余额支付','1'=>'支付宝支付'),"product_language_id"=>$language_text,"product_to_language_id"=>$language_text,));
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
    	if($order_info['expected_start_time']=='0000-00-00 00:00:00'){
    		$order_info['expected_start_time']='';
    	}
    	if($order_info['expected_end_time']=='0000-00-00 00:00:00'){
    		$order_info['expected_end_time']='';
    	}
    	$buyer_info['address_text'] =$address_text;
    	$map=array();
    	$map['order_id'] = $order_id;
    	$order_result = get_result('order_history',$map);
    	$order_result = int_to_string($order_result,array('order_status'=>$this->order_status));
    	if($order_info['order_status']==4){
    		$map['order_id']=$order_id;
    		$refund_result =get_result(D($this->refund_model),$map);
    		foreach ($refund_result as $k=>$row){
    			if($row['type'] ==1){
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
    	$data['grade']=array_id_key(get_grade_cache());		/*获取难度等级*/
    	$data['info']=$order_info;
    	$data['result']=$order_result;
    	$data['buyer_info']=$buyer_info;
    	$data['refund_result']=$refund_result;
    
    	$this->assign($data);
    	$this->display();
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
    	$info = get_info($this->files_table,array('id' =>$id));
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
     *用户评价
     *	显示用户的评价
     **/
    public function evaluate(){
    	$post = I('post.');
    	$sort = 'comment.add_time desc';
    
    	if(!empty($post)){
    		if($post['radio']!=0){//0表示全部
    			$maps['type'] = $post['radio'];
    		}
    		//变换查询条件
    		switch($post['select']){
    			case 1:
    				$sort = 'comment.add_time desc';
    				break;
    			case 2:
    				$sort = 'comment.add_time asc';
    				break;
    		}
    	}
    	$shop_id = session('home_shop_id');
    	//获取店铺信息
    	$shop_info = get_info(D("ShopView"),array('id'=>$shop_id));
    	//dump($shop_info);
    	//查询店铺的产品
    	/*$shop_products_id = get_result($this->products,array('id'=>$shop_id));
    	$products_id = '';
    	foreach($shop_products_id as $k=>$v){
    		$products_id .= $v['id'].',';
    	}
    	$products_id = trim($products_id,',');
    
    	//获取筛选数据
    	if(!empty($products_id)){
    	   $maps['product_id'] = array('in',$products_id);
    	}*/
    	$maps['shop_id'] = $shop_id;
    	$maps['status'] = array('in',"0,2");
    	$maps['pid'] = 0;//pid为0的属于评价而不是评价回复
    	//$maps = array_merge($maps,$map);
    	//计算店铺的星级？？？？？？
    
    	//统计店铺的好评数,中评数，差评数
    	$shop_good_comments = get_result($this->comment,array('shop_id'=>$shop_id,'status'=>array('in',"0,2"),'type'=>1,'pid'=>0));
    	$good_comments_num = count($shop_good_comments);
    
    	$shop_mid_comments = get_result($this->comment,array('shop_id'=>$shop_id,'status'=>array('in',"0,2"),'type'=>2,'pid'=>0));
    	$mid_comments_num = count($shop_mid_comments);
    
    	$shop_bad_comments = get_result($this->comment,array('shop_id'=>$shop_id,'status'=>array('in',"0,2"),'type'=>3,'pid'=>0));
    	$bad_comments_num = count($shop_bad_comments);
    
    	$total_comments_num = $good_comments_num+$mid_comments_num+$bad_comments_num;
    
    	$shop_comments = $this->page(D($this->comment_product),$maps,$sort,'',$this->limit);//关联查询商品和评论表
    
    	$shop_comments = int_to_string($shop_comments,array('type'=>array(1=>'好评',2=>'中评',3=>'差评')));
    	//将语言和评论关联起来
    	$language_data = get_language_cache();
    	$language_data = array_id_key($language_data);
        //dump($shop_comments);
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
    		$shop_comments_s = get_result($this->comment,array('status'=>array('in',"0,2"),'pid'=>array('in',trim($ids,','))));
    		$shop_comments_s = int_to_string($shop_comments_s,array('type'=>array(1=>'好评',2=>'中评',3=>'差评')));
    		if($shop_comments_s){
    			foreach($shop_comments_s as $v){
    				$shop_comments[]=$v;
    			}
    			$shop_comments = list_to_tree($shop_comments,'id','pid','_child',0);
    		}
    	}
    	$comments = array(
    			'total_comments_num' => $total_comments_num,
    			'good_comments_num' => $good_comments_num,
    			'mid_comments_num' => $mid_comments_num,
    			'bad_comments_num' => $bad_comments_num,
    			'shop_comments' => $shop_comments,
    			'comments_image' => $comments_image,
    	);
    	$data["shop_info"]=$shop_info;
    	$this->assign($data);
    	$this->assign($comments);
    	if(!empty($post)){
    		$this->display('order_evaluate_ajax');
    	}else{
    		$this->display('order_evaluate');
    	}
    }
	/**
	*数据图表
	*	反应店铺的运营状况的数据图表
	*		1、成交金额：指的是一段时间内店铺达成交易的金额
	*		2、成交笔数：指的是一段时间内的店铺成交的次数
	*		3、成交用户：指的是一段时间内的店铺中购买产品的用户数量
	*		4、成交数量；指的是一段时间内的店铺中总共卖出的产品的数量
	*流程分析
	*	1、根据时间筛选条件确定数据筛选的时间段
	*	2、组织sql语句
	*	3、查询所要处理的数据
	*	4、判断数据是否存在
	*	5、处理数据
	*	6、数据的显示
	*@author 刘浩 <qq:372980503>
	*@time: 2015-07-15
	**/
	public function data(){
		//将属于本店铺的数据查询出来
		$shop_id = session('home_shop_id');
		//查询店铺的所有的订单，按照时间做为筛选
		if(!empty(I('get.'))){
			$time = I('get.time');
			
		}else{
			$time = 2;//默认筛选今日
		}
		//根据接收到的时间关键词确定开始时间和结束时间
		if($time==1){//表示时间筛选是指的是昨天
			$start_time = date("Y-m-d",strtotime("-1 day"));
			$start_time .= ' 00:00:00';
			$end_time = date("Y-m-d");
			$end_time .= ' 00:00:00';
		}else if($time==2){//表示时间筛选指的是今天
			$start_time = date("Y-m-d");
			$start_time .= ' 00:00:00';
			$end_time = date("Y-m-d",strtotime("+1 day"));
			$end_time .= ' 00:00:00';
		}else if($time==3){//表示时间筛选是本周
			$start_time = date("Y-m-d",strtotime("-1 week -1 day"));
			$start_time .= ' 00:00:00';
			$end_time = date("Y-m-d");
			$end_time .= ' 00:00:00';
		}else if($time==4){//表示时间筛选是本月
			$start_time = date('Y-m-01', strtotime(date("Y-m-d")));
			$start_time .= ' 00:00:00';
			//$end_time = date("Y-m-d");
			$end_time=date('Y-m-d', strtotime("$start_time +1 month -1 day"));
			$end_time .= ' 00:00:00';
		}
		//书写sql语句
		$sql = "select * from `sr_orders` where `confirm_time` >= '$start_time' and `confirm_time` <= '$end_time' and `shop_id`='$shop_id'";
		$model = M();
		$result = $model->query($sql);
		//统计订单数据
		$order_data = $this->order_data($result);
		//dump($order_data);
		
		
		if(IS_POST){
			$this->ajaxReturn($order_data);
		}else{
			$this->assign($order_data);
			$this->display('order_data');
		}
	}
	/**
	*评价回复/修改功能
	*	商家在自己的后台能够回复用户的评论，并且能够修改评价回复
	**/
	public function reply(){
		//接收用户提交的回复信息
		$post = I('post.');
		//验证信息的完整性
		$rules = array(
			array('comment_level','require','评价信息不完整'),
			array('reply','require','评价内容必须！')
		);
		//将信息组织好
		$_POST = array(
			'type'=>intval($post['comment_level']),
			'pid'=>intval($post['comment_id']),
			'content'=>htmlspecialchars($post['reply']),
			'member_id'=>session('home_member_id'),
			'product_id'=>0,//有待完善
			'status'=>0
		);
		if($post['reply_id']){
			$_POST['id'] = $post['reply_id'];
			$_POST['status'] = 0;//表示回复没有审核
		}
		$result = update_data($this->comment);
		if(is_numeric($result)){
			$this->success('回复成功,等待后台审核！');
		}else{
			$this->error($result);
		}
	}
	/**
	 * 商家上传完成翻译的文件
	 * 
	 * @author						李东
	 * @date						2015-07-14
	 */
	public function upload_completeFiles(){
		if(IS_POST){
			$posts = I('post.');
			$order_id = $posts['order_id'];
			$member_id = session('home_member_id');
			if(!empty($posts['save_path'])){
				$map['id'] = $order_id;
				$order_info = get_info($this->table,$map);
				if(!$order_info){
					$tips_msg = array('status'=>'0','msg'=>'订单不存在');
					goto export;
				}
				/*开启事务*/
				$Model = M();
				$Model->startTrans();
				$_POST = array(
					'id'=>$order_id,
					'step'=>4,
					'status'=>6,
				);
				/*将订单更新为翻译完成状态*/
				$res = update_data($this->table);
				
				/*将订单状态记录添加到订单历史记录表中*/
				$_POST =array(
						'order_id'=>$order_id,
						'order_status'=>'6',
						'description'=>'商家提交翻译完成文件',
				);
				$res2 = update_data($this->history_table);
				if(is_numeric($res)&&is_numeric($res2)){
					/*事务提交*/
					$Model->commit();
					/* 判断是否提交了文件 */
					$data_array=array();
					foreach($posts['save_path'] as $val){
						if(!empty(intval($val))){
							$data_array[$val]=array(
								"title"=>$posts["filename_".$val],
								"order_id"=>$order_id,
							);
						}
					}
					multi_file_uploads($posts['save_path'],'Uploads/UserFiles/'.$member_id,'files_complete',$data_array,'save_path');
					//multi_file_upload($posts['save_path'],'Uploads/UserFiles/'.$member_id,'files_complete','order_id',$order_id,'save_path');
					$tips_msg = array('status'=>'1','msg'=>'上传成功');
				}else{
					/*事务回滚*/
					$Model->rollback();
					$tips_msg = array('status'=>'0','msg'=>'上传失败');
				}
				
			}else{
				$tips_msg = array('status'=>'0','msg'=>'请先上传文件');
			}
		}else{
			$tips_msg = array('status'=>'0','msg'=>'请求错误');
		}
		
		export:
		if($tips_msg['status'] ==1){
			$this->success($tips_msg['msg']);
		}else{
			$this->error($tips_msg['msg']);
		}
		
	}
	
	/**
	 * 回复退款申请
	 * 提交描述信息
	 * 
	 * @author						李东
	 * @date						2015-07-16
	 */
	public function refund_replay(){
		if(IS_POST){
			$posts = I('post.');
			$order_id = $posts['order_id'];
			$member_id = session('home_member_id');
			$shop_id =	session('home_shop_id');
			if(!$member_id){
				$tips_msg = array('status'=>'0','msg'=>'请登录后再操作1');
				goto export;
			}
			if(!$shop_id){
				$tips_msg = array('status'=>'0','msg'=>'请登录后再操作2');
				goto export;
			}
			$_POST = array(
				'order_id'=>$order_id,
				'member_id'=>$member_id,
				'shop_id'=>$shop_id,
				'description'=>$posts['description'],
				'type'=>'2',
			);
			
			/*开启事务*/
			$Model = M();
			$Model->startTrans();
			/* 更新退款信息表 */
			$res = update_data($this->refund_table);
				
			/* 添加订单历史记录*/
			$_POST = array(
					'order_id'=>$order_id,
					'order_status'=>'4',
					'description'=>'商家回复原因描述',
			);
			$res2 = update_data($this->history_table);
			if(is_numeric($res)&&is_numeric($res2)){
				/*全部成功提交事务*/
				$Model->commit();
				$tips_msg = array('status'=>'1','msg'=>'添加成功');
			}else{
				/*没有全部成功，回滚事务*/
				$Model->rollback();
				$tips_msg = array('status'=>'0','msg'=>'添加失败');
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
	 * 退款
	 * 在查看订单详情页面进行退款处理
	 * 需提交参数，
	 * 		退款订单ID(order_id)
	 * 		退款金额(refund_price)
	 * 		操作用户ID(member_id)
	 * @author						李东
	 * @date						2015-06-30
	 */
	public function refund(){
		if(IS_POST){
			$posts = I('post.');
			$id = $posts['order_id'];
			$refund_price = $posts['refund_price'];
			$member_id = session('home_member_id');
			$deal_password = $posts['deal_password'];
			if(!$id){
				$result = array('status'=>'0','msg'=>'操作失败');
				goto export;
			}
			if(!floatval($refund_price)){
				$result = array('status'=>'0','msg'=>'请填写退款金额');
				goto export;
			}
			if(!$member_id){
				$result = array('status'=>'0','msg'=>'请登录后再操作');
				goto export;
			}
			$member_info = get_info('member',array('id'=>$member_id,));
			if(md5(md5($deal_password))!= $member_info['deal_password']){
				$result = array('status'=>'0','msg'=>'支付密码不正确');
				goto export;
			}
			$description = "商家同意退款";
			$result = order_refund($id,floatval($refund_price),$description);
			
			export:
			if($result['status'] == 1){
				$this->success($result['msg'],U('index'));
			}else{
				$this->error($result['msg']);
			}
		}else{
			$this->error('请求错误!');
		}
	}
    /**
	*处理订单数据最终生成图表数据
	*	需要将数据处理成成交总额，成交笔数，成交的用户数，成交数量
	*@result array 需要处理的订单信息
	*@return array 返回参数数组
	*	order_price 指的是成交总额
	*	order_num   指的是成交的笔数
	*	member_num  指的是成交的用户数
	*	deal_num	指的是成交数量
	*@author 刘浩 <372980503>
	*@time  2015-07-15
	**/
	private function order_data($result){
		//统计成交总额
		$order_price = 0;
		foreach($result as $k=>$v){
			$order_price += $v['total_price'];
		}
		//统计成交笔数
		$order_num = count($result);
		//统计成交的用户数
		$member_num = 0;
		$member_ids = array();
		foreach($result as $k=>$v){
			$member_ids[] = $v['member_id'];
		}
		$member_ids = array_unique($member_ids);
		$member_num = count($member_ids);
		//统计成交数量
		$deal_num = 0;
		foreach($result as $k=>$v){
			if($v['product_type']==1){
				$qty = floor($v['qty']/1000);
			}else{
				$qty = floor($v['qty']);
			}
			$deal_num += $v['qty'];
		}
		$order_data = array(
			'order_price' => $order_price,
			'order_num' => $order_num,
			'member_num'=>$member_num,
			'deal_num'=>$deal_num
		);
		return $order_data;
	}
	
	/**
	 * ajax删除文件
	 *
	 */
	function ajaxDelete_files_complete(){
		if(IS_POST){
			$posts=I("post.");
			$info=get_info($this->files_complete,array("id"=>$posts['file_id']));
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
					delete_data($this->files_complete,array("id"=>$posts['file_id']));
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
				delete_data($this->files_complete,array("id"=>$posts['file_id']));
				$this->success("文件不存在，删除失败，数据被清空");
			}
		}
	}
	
}