<?php
namespace Backend\Controller\Shop;
use Backend\Controller\Base\AdminController;
class PendingshopController extends AdminController {
	protected $table='shop';
	protected $model ='ShopView';
	protected $limit=15;
	protected $cache_name='pending_shop_cache';		/* 缓存名 */
	
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
// 		$map['shop_status'] =array(in,array(0,2));/*@liuqiao  将冻结的放在待审核列表*/
		$map['shop_status'] = 0;//将所有正在审核的店铺显示到待审核店铺列表中
		$order = ' status asc,id desc';
		$result = $this->page(D($this->model),$map,$order,$field=array(),$limit='');/* ($model,$map=array(),$order='',$field=array(),$limit='') */
// 		$result=int_to_string($result,array("shop_status"=>array("0"=>"审核","1"=>"取消审核","2"=>"取消冻结"),"recommend"=>array("0"=>"推荐","1"=>"取消推荐")));
		$result=int_to_string($result,array("shop_status"=>array("0"=>"审核通过","1"=>"取消审核","2"=>"取消冻结"),"recommend"=>array("0"=>"推荐","1"=>"取消推荐")));
		$data['result']=$result;
// 		dump($data);die;
		$this->assign($data);
		$this->display('index');
	}
	
	/**
	 * 分类添加
	 * 包括语种分类，行业分类，技能分类 的添加
	 * 三种分类单独显示列表时，以type区分，type值按顺序分别为0，1，2
	 * @author						李东
	 * @date						2015-06-12
	 */
	public function add(){
		if(IS_POST){
			$this->update();
		}else{
			$this->display('operate');
		}
		
	}
	
	/**
	 * 分类修改
	 * 修改分类信息
	 * @author						李东
	 * @date						2015-06-12
	 */
	public function edit(){
		if(IS_POST){
			$this->update();
		}else{
			$gets = I('get.');
			$id = $gets['id'];
			$map['shop.id']=$id;
			$info = get_info(D($this->model),$map);
			$data['info'] = $info;
			$this->assign($data);
			$this->display('operate');
		}
	}
	public function update(){
		if(IS_POST){
			$posts = I('post.');
			/* 定义添加验证规则 */
			$rule = array(
					array('title','require','标题必须填写',1),
					array('good_at','require','请选择擅长语言',1),
					array('short_description','require','请填写简介',1),
					//array('description_now','require','请填写成功案例',1),
					//array('description_old','require','请填写过往经历',1),
			);
			/* $str='';
				foreach($posts['good_at'] as $value){
				$str .=$value.',';
			} */
			$member_info=get_info('member',array('username|telephone|email'=>$posts['username']));
			$member_id = $member_info['id'];
			if(!$member_id){
				$this->error('所属用户未填写或填写的用户不存在');
			}
			// 			$good_at = substr($str,0,-1);/*将擅长组装成字符串*/
			$good_at = json_encode($posts['good_at']);
			$_POST = array(
					'id'=>$posts['id'],
					'logo'=>$posts['logo'],
					'title'=>$posts['title'],
					'slogan'=>$posts['slogan'],
					'member_id'=>$member_id,
					'type'=>$posts['type'],
					'short_description'=>$posts['short_description'],
					'description_now'=>$posts['description_now'],
					'description_old'=>$posts['description_old'],
					'good_at'=>$good_at,
					'keywords'=>$posts['keywords'],
			);
				
			$result=update_data($this->table, $rule);
				
			/* 判断执行操作是否成功 */
			if(is_numeric($result)){
				/*将店铺logo更新到数据库*/
				multi_file_upload($posts['logo'],'Uploads/Shop','shop','id',$result,'logo');
				F($this->cache_name,null); /* 操作成功清除缓存 */
				$this->success('操作成功',U('index',array('pid'=>intval(I('post.pid')))));
			}else{
				$this->error($result);
			}
	
		}else{
			$this->error('违法操作',U('index'));
		}
	}
	
	
	/*public function update(){
		if(IS_POST){
			$posts = I('post.');
			// 定义添加验证规则 
			$rule = array(
					array('title','require','分类名称不能为空'),
			);
			$path = '-0-';
			if($posts['pid']){
				//如果上级id存在，则取出上级path,组合成自身path
				$map['status'] =array('gt','-1'); 
				$map['id']=$posts['pid'];
				$pid_info = get_info($this->table,$map);
				$level=$parent_info['level']+1;
				$path =$pid_info['path'].$posts['pid'].'-';
			}
			$_POST = array(
					'id'=>$posts['id'],
					'pid'=>$posts['pid'],
					'title'=>$posts['title'],
					'sort'=>$posts['sort'],
					'type'=>$posts['type'],
					'description'=>$posts['description'],
					'path'=>$path,
					'level'=>$level,
			);
			if($level>3){
				$this->error('最多只能添加三级分类！');exit;
			}else{
				$result=update_data($this->table, $rules);
			}
			// 判断执行操作是否成功 
			if(is_numeric($result)){
				F($this->cache_name,null); // 操作成功清除缓存 
				$this->success('操作成功',U('index',array('pid'=>intval(I('post.pid')))));
			}else{
				$this->error($result);
			}

		}else{
			$this->error('违法操作',U('index'));
		}
	}*/
   public function checkshop(){
	   	if(IS_POST){
	   		$posts = I('post.');
	   		if(!in_array($posts['is_identity_confirm'], array('0','1'))){
	   			$this->error('请审核');
	   		}
	   		if(!in_array($posts['is_qualified_comfirm'], array('0','1'))){
	   			$this->error('请审核');
	   		}
	   		if(!in_array($posts['status'], array('0','1'))){
	   			$this->error('请审核');
	   		}
			$_POST = array(
					'id'=>$posts['shop_id'],
					'is_identity_confirm'=>$posts['is_identity_confirm'],
					'is_qualified_comfirm'=>$posts['is_qualified_comfirm'],
					'status'=>$posts['status'],
			);
	   		$res = update_data($this->table);
	   		if(is_numeric($res)){
	   			$this->success('操作成功');
	   		}else{
	   			$this->error('操作失败');
	   		}
	   		
	   	}else{
	   		$gets = I('get.');
	   		$id = $gets['id'];
	   		$map['shop.id']=$id;
	   		$info = get_info(D($this->model),$map);
	   		//过往经历
	   		$undergo = get_result("shop_undergo",array("shop_id"=>$id));
	   		$shop_image = get_result("shop_image",array("shop_id"=>$id));
	   		$data['info'] = $info;
	   		$data['undergo'] = $undergo;
	   		$data['shop_image'] = $shop_image;
	   		$this->assign($data);
	   		$this->display();
	   	}
   }
    /*
     * 过往经历
    * @author 龚双喜
    * */	
   public function undergo(){
   	 if(IS_POST){
   	 	$gets = I('get.');
   	 	$id = $gets['id'];
   	 	$shop_id = $gets['shop_id'];
   	 	//接收用户传来的值
   	 	$start_time_year = intval(I('start_time_year'));
   	 	$start_time_month = intval(I('start_time_month'));
   	 	$end_time_year = intval(I('end_time_year'));
   	 	$end_time_month = intval(I('end_time_month'));
   	 	$content = htmlspecialchars((I('content')));
   	 	
   	 	$start_time = mktime(0,0,0,$start_time_month,1,$start_time_year);
   	 	$end_time = mktime(0,0,0,$end_time_month,1,$end_time_year);
   	 	$now_time = time();
   	 	//判断用户的结束时间是否超过当前时间
   	 	if ($end_time > $now_time){
   	 		$this->error("结束时间不能大于当前时间！！");
   	 	}
   	 	//判断时间格式是否正确
   	 	if(!$end_time){
   	 		$this->error('时间格式错误！！');
   	 	}
   	 	if($start_time >= $end_time){
   	 		$this->error('开始时间不能超过结束时间！！');
   	 	}
   	 	if(empty($content)){
   	 		$this->error("请填写过往经历");
   	 	}else if(mb_strlen($content,'utf-8')>200||mb_strlen($content,'utf-8')<50){
   	 		$this->error("填写的经历字数应满足50-200字");
   	 	}
   	 	
   	 	//将经历选项和经历内容合并
   	 	$content = $content;
   	 	//将记录添加到表中
   	 	$_POST = array(
   	 			'start_time'=>$start_time,
   	 			'end_time'=>$end_time,
   	 			'id'=>$id,
   	 			'content'=>$content,
   	 			'type'=>1,
   	 	);
   	 	$result = update_data('shop_undergo');
   	 	if(is_numeric($result)){
   	 		$this->success("修改成功！！",U('Backend/Shop/Pendingshop/undergo',array("id"=>$shop_id)));	
   	 	}else{
   	 		$this->error('修改失败！！',U('Backend/Shop/Pendingshop/undergo',array("id"=>$shop_id)));	
   	 	}
   	 }else{
   	 	$gets = I('get.');
   	 	$id = $gets['id'];
   	 	//$shop_id = $gets['shop_id'];
   	 	$edit = $gets['edit'];
   	 	//过往经历
   	 	if(!$edit){
   	 	  $undergo = get_result("shop_undergo",array("shop_id"=>$id,'type'=>1));
   	 	}else{
   	 	  $undergo = get_info("shop_undergo",array("id"=>$id,'type'=>1));
   	 	}
   	 	$data['undergo'] = $undergo;
   	 	$this->assign($data);
   	 	if($edit){
   	 	   $this->display('undergo_edit');
   	 	}else{
   	 	   $this->display();
   	 	}
   	 }
   }	
   /*
    * 成功案例
    * @author 龚双喜
    * */
   public function shop_success(){
   	if(IS_POST){
   		$gets = I('get.');
   		$id = $gets['id'];
   		$shop_id = $gets['shop_id'];
   		//接收用户传来的值
   		$start_time_year = intval(I('start_time_year'));
   		$start_time_month = intval(I('start_time_month'));
   		$end_time_year = intval(I('end_time_year'));
   		$end_time_month = intval(I('end_time_month'));
   		$content = htmlspecialchars((I('content')));
   
   		$start_time = mktime(0,0,0,$start_time_month,1,$start_time_year);
   		$end_time = mktime(0,0,0,$end_time_month,1,$end_time_year);
   		$now_time = time();
   		//判断用户的结束时间是否超过当前时间
   		if ($end_time > $now_time){
   			$this->error("结束时间不能大于当前时间！！");
   		}
   		//判断时间格式是否正确
   		if(!$end_time){
   			$this->error('时间格式错误！！');
   		}
   		if($start_time >= $end_time){
   			$this->error('开始时间不能超过结束时间！！');
   		}
   		if(empty($content)){
   			$this->error("请填写过往经历");
   		}else if(mb_strlen($content,'utf-8')>200||mb_strlen($content,'utf-8')<50){
   			$this->error("填写的经历字数应满足50-200字");
   		}
   
   		//将经历选项和经历内容合并
   		$content = $content;
   		//将记录添加到表中
   		$_POST = array(
   				'start_time'=>$start_time,
   				'end_time'=>$end_time,
   				'id'=>$id,
   				'content'=>$content,
   				'type'=>2,
   		);
   		$result = update_data('shop_undergo');
   		if(is_numeric($result)){
   			$this->success("修改成功！！",U('Backend/Shop/Pendingshop/shop_success',array("id"=>$shop_id)));
   		}else{
   			$this->error('修改失败！！',U('Backend/Shop/Pendingshop/shop_success',array("id"=>$shop_id)));
   		}
   	}else{
   		$gets = I('get.');
   		$id = $gets['id'];
   		//$shop_id = $gets['shop_id'];
   		$edit = $gets['edit'];
   		//过往经历
   		if(!$edit){
   			$undergo = get_result("shop_undergo",array("shop_id"=>$id,'type'=>2));
   		}else{
   			$undergo = get_info("shop_undergo",array("id"=>$id,'type'=>2));
   		}
   		$data['undergo'] = $undergo;
   		$this->assign($data);
   		if($edit){
   			$this->display('success_edit');
   		}else{
   			$this->display('success');
   		}
   	}
   }
   /*
    * 过往经历，成功分享删除
    * @author 龚双喜
    * */
   public function undergo_del(){
   	
   	 $id=intval(I("get.id"));
   	 $result=delete_data("shop_undergo",array("id"=>$id));
   	 if($result){
   	 	$this->success("删除成功");
   	 }else{
   	 	$this->error("删除失败");
   	 }
   }
}
