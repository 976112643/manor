<?php
namespace User\Controller;
use User\Controller\ShopBaseController;
use Think\Page;
class SaleController extends ShopBaseController {
	protected $table = 'products'; 		/*商品存储数据表*/
	protected $model = 'ProductsView';	/*商品视图模型*/
	protected $shop_table = 'shop';		/*店铺信息表*/
	protected $limit = 5;				/*每页条数*/
	protected $product_type;			/*商品分类*/
	
	public function __autoload(){
		parent::__autoload();
		$this->product_type = list_to_tree(get_ability_cache());		/*获取缓存的分类属性转换为树状*/
	}
   
    /**
     * 商品列表页 
     * 展示该店铺所拥有的所有商品
     *
	 * @author						李东
	 * @date						2015-06-25
     */
    public function index(){
//     	if(I('type')){
//     		$map['type']=I('type');
//     	}
//@赵群@按用户搜索条件搜索
    	if(I('language_id')){
    		$map['language_id']=I('language_id');
    	}
    	if(I('to_language_id')){
    		$map['to_language_id']=I('to_language_id');
    	}
    	if(I('type_text')){
    		$map['products.type']=array('like','%'.I('type_text').'%');
    	}
    	if(I('industry_id_text')){
    		$map['products.industry_id']=array('like','%'.I('industry_id_text').'%');
    	}
    	
        $map['shop_id'] = session('home_shop_id');//按照登录店铺的ID来查看
    	$map['product_status'] = array('gt','-1');
    	$product_lists = $this->page(D($this->model),$map,$order=' id desc ',$field=array(),$this->limit);
    	/*获取格式为 id=>title 的语言分类数组*/
    	$language_text = id_and_text(get_language_cache());
//     	dump($language_text);die;

    	/*获取格式为 id=>title 的等级分类数组*/
    	$level_text = id_and_text(get_product_level_cache());
    	/*获取格式为 id=>title 的产品分类数组*/
    	$product_type=id_and_text($this->product_type);
    	
    	$product_lists = int_to_string($product_lists,array('level_id'=>$level_text,"type"=>$product_type,"language_id"=>$language_text,"to_language_id"=>$language_text,));
    	/*将json格式ID转为逗号分隔的Title*/

    	/*获取格式为 id=>title 的领域分类数组*/
    	$industry_text = id_and_text(get_industry_cache());

    	/*获取格式为 id=>title 的属性分类数组*/
    	$ability_text = id_and_text(get_ability_cache());

    	/*将Json中所有ID转换成文字字符串*/
    	$product_lists = json_to_chars($product_lists,array('industry_id'=>$industry_text,'ability_id'=>$ability_text,));
    	
    	$data['product_lists'] = $product_lists;
    	$data['product_type'] = $this->product_type;
    	
    	//获取语言信息
    	$data['language_text'] = $language_text;
    	//获取领域信息
    	$data['industry_text'] = $industry_text;
    	//获取技能信息
    	$data['ability_text'] = $ability_text;
    	
    	//@赵群@获取语言、技能和行业信息
    	$data['ability'] =get_ability_cache();
    	$data['industry'] =get_industry_cache();
    	$data['language'] = get_language_cache();
    	$language_array_text = array();
    	$ability_array_text = array();
    	$industry_array_text = array();
    	foreach ($data['language'] as $key=>$value){
    		if($value['pid'] != 0){
    			$language_array_text[$value['id']] = $value['title'];
    		}
    	}
    	foreach ($data['ability'] as $key=>$value){
    		if($value['type'] == 2 && $value['level'] == 1){
    			$ability_array_text[$value['id']] = $value['title'];
    		}
    	}
    	foreach ($data['industry'] as $key=>$value){
    		if($value['type'] == 1 && $value['level'] == 2){
    			$industry_array_text[$value['id']] = $value['title'];
    		}
    	}

    	$data['language_array_text'] = $language_array_text;
    	$data['ability_array_text'] = $ability_array_text;
    	$data['industry_array_text'] = $industry_array_text;
    	$tips_msg=array('status'=>1,'info'=>$data);
    	$this->appOut($tips_msg,$this->apptype,1,'list');
//    	$this->assign($data);
//    	$this->display('list');
    }
    
    /**
     * 过期商品列表页
     * 展示该店铺所拥有的所有商品
     *
     * @author						李东
     * @date						2015-07-01
     */
    public function has_failed(){
//     	if(I('type')){
//     		$map['type']=I('type');
//     	}
//     	$map['_query'] = " expired_time<'".date('Y-m-d H:i:s',time())."'&`expired_time` <> '0000-00-00 00:00:00'&_logic=and";
    	
    	//@赵群@按用户搜索条件搜索
    	if(I('language_id')){
    		$map['language_id']=I('language_id');
    	}
    	if(I('to_language_id')){
    		$map['to_language_id']=I('to_language_id');
    	}
    	if(I('type_text')){
    		$map['products.type']=array('like','%'.I('type_text').'%');
    	}
    	if(I('industry_id_text')){
    		$map['products.industry_id']=array('like','%'.I('industry_id_text').'%');
    	}
    	
    	$map['shop_id'] = session('home_shop_id');//按照登录店铺的ID来查看
    	$map['expired_time']=array('LT',date('Y-m-d H:i:s',time()));
    	
    	$where['expired_time'] = array('neq','0000-00-00 00:00:00');
    	$where['products.status'] = array('gt','-1');
    	$where['_logic'] = 'and';
    	$map['_complex'] = $where;
    	
    	
//     	$map['shop_id'] = session('home_shop_id');//按照登录店铺的ID来查看

    	$product_lists = $this->page(D($this->model),$map,$order='',$field=array(),$this->limit);
    	/*获取格式为 id=>title 的语言分类数组*/
    	$language_text = id_and_text(get_language_cache());
    	/*获取格式为 id=>title 的等级分类数组*/
    	$level_text = id_and_text(get_product_level_cache());
    	 
    	$product_lists = int_to_string($product_lists,array('level_id'=>$level_text,"type"=>array('0'=>'暂未分类',"1"=>"笔译","2"=>"音频翻译",'3'=>'口译'),"language_id"=>$language_text,"to_language_id"=>$language_text,));
    	/*将json格式ID转为逗号分隔的Title*/
    	  
    	/*获取格式为 id=>title 的领域分类数组*/
    	$industry_text = id_and_text(get_industry_cache());
    	/*获取格式为 id=>title 的属性分类数组*/
    	$ability_text = id_and_text(get_ability_cache());
    	/*将Json中所有ID转换成文字字符串*/
    	$product_lists = json_to_chars($product_lists,array('industry_id'=>$industry_text,'ability_id'=>$ability_text,));
    
    	$data['product_lists'] = $product_lists;
//     	dump($data);die;

    	//@赵群@获取语言、技能和行业信息
    	$data['ability'] =get_ability_cache();
    	$data['industry'] =get_industry_cache();
    	$data['language'] = get_language_cache();
    	$language_array_text = array();
    	$ability_array_text = array();
    	$industry_array_text = array();
    	foreach ($data['language'] as $key=>$value){
    		if($value['pid'] != 0){
    			$language_array_text[$value['id']] = $value['title'];
    		}
    	}
    	foreach ($data['ability'] as $key=>$value){
    		if($value['type'] == 2 && $value['level'] == 1){
    			$ability_array_text[$value['id']] = $value['title'];
    		}
    	}
    	foreach ($data['industry'] as $key=>$value){
    		if($value['type'] == 1 && $value['level'] == 2){
    			$industry_array_text[$value['id']] = $value['title'];
    		}
    	}
    	
    	$data['language_array_text'] = $language_array_text;
    	$data['ability_array_text'] = $ability_array_text;
    	$data['industry_array_text'] = $industry_array_text;
    	
    	$tips_msg=array('status'=>1,'info'=>$data);
    	$this->appOut($tips_msg,$this->apptype,1,'list');
    	 
//    	$this->assign($data);
//    	$this->display('list');
    }
    
    /**
     * 商品添加
     * 如果是表单提交添加数据
     * 否则直接加载添加表单页面
     * 
     * @author					李东
     * @date					2015-07-03
     */
    public  function add_product(){
    	if(IS_POST){
    		$shop_id = session('home_shop_id');
    		$result = $this->update($shop_id);
    		$this->appOut($result,$this->apptype,0);
//    		if($result['status'] == 1){
//    			$this->success($result['msg'],$result['url']);
//    		}else{
//    			$this->error($result['msg'],$result['url']);
//    		}
    		
    	}else{
    		$translate_type_info = json_decode(session('translate_type'),true);
    		$translate_type ='您的店铺服务范围是：';
    		foreach ($translate_type_info as $val){
    				if($val=='0'){
    					$translate_type .= '中译外&nbsp;&nbsp;';
    				}
    				if($val=='1'){
    					$translate_type .= '外译外&nbsp;&nbsp;';
    				}
    				if($val=='2'){
    					$translate_type .= '外译中&nbsp;&nbsp;';
    				}
    		}
    		$data['translate_type'] = $translate_type;
    		$shop_id = session('home_shop_id');
    		$data['product_level']=get_product_level_cache();
    		$data['product_type'] =$this->product_type;
    		$data['ability'] = list_to_tree(get_ability_cache());
    		$data['industry'] = list_to_tree(get_industry_cache());
    		$data['language'] = get_language_cache();
			$language_data = get_info('products',array('shop_id'=>$shop_id));
			$data['language_data'] = $language_data;
    		$tips_msg=array('status'=>1,'info'=>$data);
    		$this->appOut($tips_msg,$this->apptype,1,'operate');
//    		$this->assign($data);
//    		$this->display('operate');
    	}
    }
    
    /**
     * 商品编辑
     * 如果是表单提交更新数据
     * 否则查询出要编辑的商品，在页面中展示高商品的信息
     *
     * @author					李东
     * @date					2015-07-03
     */
    public  function edit(){
    	if(IS_POST){
    		$shop_id = session('home_shop_id');
    		$result = $this->update($shop_id);
    		$this->appOut($result,$this->apptype,0);
//    		if($result['status'] == 1){
//    			$this->success($result['msg'],$result['url']);
//    		}else{
//    			$this->error($result['msg'],$result['url']);
//    		}
    
    	}else{
    		
    		$gets = I('get.');
    		$id = $gets['id'];
    		$map['id']=$id;
    		$info = get_info(D($this->model),$map);
    		/*获取格式为 id=>title 的语言分类数组*/
    		$language_text = id_and_text(get_language_cache());
    		/*获取格式为 id=>title 的领域分类数组*/
    		$industry_text = id_and_text(get_industry_cache());
    		/*获取格式为 id=>title 的属性分类数组*/
    		$ability_text = id_and_text(get_ability_cache());
    		/*获取格式为 id=>title 的产品分类数组*/
    		$product_type=id_and_text($this->product_type);
    		/*暂时将一维数组转化为二维数组*/
    		$temp[] = $info;
    		$temp = int_to_string($temp,array("language_id"=>$language_text,"to_language_id"=>$language_text,));
    		/*将Json中所有ID转换成文字字符串*/
    		$temp = json_to_chars($temp,array('industry_id'=>$industry_text,'ability_id'=>$ability_text,));
    		 
    		$info = $temp[0];/*将二维数组还原成一维数组*/
    		
    		$ability_ids = json_decode($info['ability_id'],true);
    		$industry_ids = json_decode($info['industry_id'],true);
//     		print_r($info);exit;
    		$data['info'] = $info;
    		$data['ability_ids'] = $ability_ids;
    		$data['industry_ids'] = $industry_ids; 
    		$data['product_level']=get_product_level_cache();
    		$data['product_type'] =$this->product_type;
    		$data['ability'] = list_to_tree(get_ability_cache());
    		$data['industry'] = list_to_tree(get_industry_cache());
    		$data['language'] = get_language_cache();
    		
    		$tips_msg=array('status'=>1,'info'=>$data);
    		$this->appOut($tips_msg,$this->apptype,1,'operate');
//    		$this->assign($data);
//    		$this->display('operate');
    	}
    }
    
    /*
     * 要判断操作的数据是否为本人的
     * */
    public function is_own(){
    	$ids=I('ids');
		if(is_array($ids)){
			$map['id']=array('in',$ids);
		}else{
			$map['id']=intval($ids);
			if($map['id']<=0){
				return false;
			}
		
		}
		$map['shop_id'] = session('home_shop_id');//按照登录店铺的ID来查看
		$list=get_result($this->table,$map);
		if(!$list){
			return false;
		}else{
			if(count($list)!=count($ids)){
				return false;
			}else{
				return $list;
			}
		}
    }
    
	/**
	 * 商品上架
	 * @author						李东
	 * @date						2015-06-25
	 */
	public function added_shelf(){
		$temp=$this->is_own();
		if(!$temp){
			$this->appOut(array('status'=>0,'info'=>'上架失败'),$this->apptype,0);
		}
		foreach($temp as $key=>$val){
			if($val['expired_time']<date('Y-m-d H:i:s',time())){
				$this->appOut(array('status'=>0,'info'=>'上架失败,请修改有效时间'),$this->apptype,0);
			}
		}
		
		
		$result = $this->setStatus('status');
		$this->appOut($result,$this->apptype,0);
//		if($result['status'] == 1){
//			$this->success($result['msg'],$result['url']);
//		}else{
//			$this->error($result['msg'],$result['url']);
//		}
	}
	

	/**
	 * 商品下架
	 * @author						李东
	 * @date						2015-06-25
	 */
	public function less_shelf(){
		$temp=$this->is_own();
		if(!$temp){
			$this->appOut(array('status'=>0,'info'=>'下架失败'),$this->apptype,0);
		}
		$result = $this->setStatus('status');
		$this->appOut($result,$this->apptype,0);
//		if($result['status'] == 1){
//			$this->success($result['msg'],$result['url']);
//		}else{
//			$this->error($result['msg'],$result['url']);
//		}
	}
	
	
	/**
	 * 商品删除
	 * @author						李东
	 * @date						2015-06-25
	 */
	public function del(){
	$temp=$this->is_own();
		if(!$temp){
			$this->appOut(array('status'=>0,'info'=>'删除失败'),$this->apptype,0);
		}
		$result = $this->setStatus('status');
		$shop_id = session('home_shop_id');
		$res = get_result('products',array('shop_id'=>$shop_id,'status'=>array('gt',-1)));
		if (is_numeric($res)){
			$_POST = array(
					'id'=>$shop_id,
					'have_product'=>1,
			);
		}else{
			$_POST = array(
					'id'=>$shop_id,
					'have_product'=>0,
			);
		}
		update_data('shop');
		$this->appOut($result,$this->apptype,0);
//		if($result['status'] == 1){
//			$this->success($result['msg'],$result['url']);
//		}else{
//			$this->error($result['msg'],$result['url']);
//		}
	}
	
	
	/**
	 * 商品更新
	 * 添加商品/修改商品的方法
	 * 
	 * @author						李东
	 * @date						2015-07-01
	 */
public function update($shop_id){
		if(IS_POST){
			$posts = I('post.');
			if(intval($posts['id'])>0){
				$map['id']=$posts['id'];
				$map['shop_id'] = session('home_shop_id');//按照登录店铺的ID来查看
				$info=get_info($this->table,$map);
				if(!$info){
					return array('status'=>0,'msg'=>'操作失败');
				}
			
			}
			
			/* 定义添加验证规则 */
			$rules = array(
					array('title','require','名称不能为空'),
					array('type','require','请选择类型'),
					array('language_id','require','请选择源语言'),
					array('to_language_id','require','请选择目标语言'),
					array('short_description','require','简介不能为空'),
					array('price','require','请填写价格'),
					array('level_id','require','请选择等级'),
			);
			if(empty($posts['industry_id'])){
				return array('status'=>'0','msg'=>'请设置行业');
			}
			if($posts['language_id']==$posts['to_language_id']){
				return array('status'=>'0','msg'=>'源语言不能与目标语言相同');
			}
			if(is_numeric($posts['price'])){
				if(floatval($posts['price'])<=0){
					return array('status'=>'0','msg'=>'价额应大于0');
				}
			}
			if(empty($posts['start_time'])||$posts['start_time']=='0000-00-00 00:00:00'){
				return array('status'=>'0','msg'=>'请填写开始时间');
			}
			if(empty($posts['expired_time'])||$posts['expired_time']=='0000-00-00 00:00:00'){
				return array('status'=>'0','msg'=>'请填写结束时间');
			}
			/*判断添加的产品是否在服务范围内*/
			$status = $this->check_type($shop_id,$posts['language_id'],$posts['to_language_id']);
			
			if(!empty($status)){
				return $status;
			}
			
			
			if($posts['start_time']>$posts['expired_time']){
				return array('status'=>0,'msg'=>'结束时间必须大于开始时间');
			}
			$industry_id = json_encode($posts['industry_id']);
			$language = id_and_text(get_language_cache());
			$title = $language[$posts['language_id']].'翻译成'.$language[$posts['to_language_id']];
			$_POST = array(
					'id'=>$posts['id'],
					'title'=>$title,
					'language_id'=>$posts['language_id'],
					'to_language_id'=>$posts['to_language_id'],
					'short_description'=>$posts['short_description'],
					'description'=>$posts['description'],
					'price'=>$posts['price'],
					'keywords'=>$posts['keywords'],
					'industry_id'=>$industry_id,
					'type'=>$posts['type'],
					'ability_id'=>json_encode($posts['ability_3']),
					'level_id'=>$posts['level_id'],
					'start_time'=>$posts['start_time'],
					'expired_time'=>$posts['expired_time'],
					'description'=>$posts['description'],
					'shop_id'=>session("home_shop_id"),
					'invoice'=>$posts['invoice'],
					'status'=>1,
					'assess'=>1,
			);
			
			$result=update_data($this->table, $rules);
			/* 判断执行操作是否成功 */
			if(is_numeric($result)){
// 				$price=$this->get_min_price();
// 				if($posts['price']<=$price){
// 					$_POST=null;
// 					$_POST['id']=session('home_shop_id');
// 					$_POST['min_price']=$posts['price'];
// 				}
				//当用户添加商品成功时，更新店铺表
				$sql .= "UPDATE sr_shop SET have_product=1 WHERE id=".session('home_shop_id');
				$Model = M();
				$result2 = $Model->execute($sql);
				
				$price=$this->get_min_price();
				if($posts['price']<=$price){
					$_POST=null;
					$_POST['id']=session('home_shop_id');
					$_POST['min_price']=$posts['price'];
				}else{
					$_POST=null;
					$_POST['id']=session('home_shop_id');
					$_POST['min_price']=$price;
				}
				/*更新店铺最低价*/
				update_data($this->shop_table);
				F($this->category_cache,null); /* 操作成功清除缓存 */
				return array('status'=>'1','msg'=>'操作成功','url'=>U('index'));
// 				$this->success('操作成功',U('index',array('pid'=>intval(I('post.pid')))));
			}else{
				return array('status'=>'0','msg'=>$result);
// 				$this->error($result);
			}

		}else{
			return array('status'=>'0','msg'=>'违法操作','url'=>U('index'));
// 			$this->error('违法操作',U('index'));
		}
	}
	
	public function check_type($shop_id,$language_id,$to_language_id){
		$shop_info = get_info($this->shop_table,array('id'=>$shop_id),'translate_type');
		if(!$shop_info){
			return array('status'=>'0','msg'=>'请登录后再操作','url'=>U('User/Login/index'));
		}
		/*将范围转换成熟组*/
		$scope = json_decode($shop_info['translate_type'],true);
// 		print_r($scope);exit;
		/*
		 * 0.中译外
		 * 1.外译外
		 * 2.外译中
		 */
		if(in_array('0', $scope)&&in_array('1', $scope)&&in_array('2', $scope)){
			/* 三项都选择不做限制 */			
		}elseif (in_array('0', $scope)&&in_array('1', $scope)&&(!in_array('2', $scope))){
			/*未选择外译中*/
			if($to_language_id == 23){	
				/*判断目标语言是否为中文*/
				return array('status'=>'0','msg'=>'您不能添加外译中的商品');
			}
		}elseif (in_array('0', $scope)&&in_array('2', $scope)&&(!in_array('1', $scope))){
			/*未选择未选择外译外*/
			if($language_id !=23 && $to_language_id != 23){
				/*判断源语言与目标语言是否都是外语*/
				return array('status'=>'0','msg'=>'您不能添加外译外的商品');
			}
		}elseif(in_array('1', $scope)&&in_array('2', $scope)&&(!in_array('0', $scope))){
			/*未选择未选择中译外*/
			if($language_id ==23){
				return array('status'=>'0','msg'=>'您不能添加中译外的商品');
			}
		}elseif(in_array('0', $scope)&&(!in_array('1', $scope))&&(!in_array('2', $scope))){
			/*只选则中译外*/
			if($language_id !=23){
				return array('status'=>'0','msg'=>'您只能添加中译外的商品');
			}
		}elseif(in_array('1', $scope)&&(!in_array('0', $scope))&&(!in_array('2', $scope))){
			/*只选则外译外*/
			if($language_id ==23 || $to_language_id==23){
				return array('status'=>'0','msg'=>'您只能添加外译外的商品');
			}
		}elseif(in_array('2', $scope)&&(!in_array('0', $scope))&&(!in_array('1', $scope))){
			/*只选则外译外*/
			if($to_language_id!=23){
				return array('status'=>'0','msg'=>'您只能添加外译中的商品');
			}
		}
		
	}
	
	
	/**
	 * 获取商品最小值
	 * @return unknown
	 */
	public function get_min_price(){
		$map['shop_id']=session('home_shop_id');
		$map['status']=1;
		$map['assess']=1;
		$price=M('products')->where($map)->min('price');
		return $price;	
	}
	/*
	*皮肤
	*@liuqiao
	*/

	public function  changeSkin(){
		$radio_id=I('get.ids');
		if($radio_id){
			$_POST=array(
				'id'=>session('home_shop_id'),
				'theme_id'=>$radio_id,
			);
			$result=update_data('shop');
			if(is_numeric($result)){
				$this->success('换肤成功');
			}
			else{
				$this->error('操作异常');
			}
		}else{
			$data['skin_results']=get_result('theme',array('status'=>array('eq',1)));
			$map['id'] = session('home_shop_id');
			$shop_info = get_info($this->shop_table,$map,'theme_id');
			$skin_results = get_result('theme',array('status'=>array('eq',1)));
			$data['skin_results']=$skin_results;
			$data['theme_id'] = $shop_info['theme_id'];
			$this->assign($data);
			$this->display();
		}
	}
	
}