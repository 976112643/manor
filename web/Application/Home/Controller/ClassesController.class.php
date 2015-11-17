<?php
namespace Home\Controller;
use Home\Controller\HomeController;
class ClassesController extends HomeController {
	protected $table = 'shop';					/*主要操作的数据表*/
	protected $product_table = 'products';		/*产品表*/
	protected $seller_type ;					/*译者类型(公司或者个人)*/
	protected $limit = 10;						/*每页显示信息条数*/
	protected $model = 'ShoplistView';			/*查询模型*/
	protected $search;							/*首页查询的条件数组*/
	protected $product_type;					/*商品分类*/
	protected $product_model = 'ProductsView';	/*商品视图模型*/
	protected $shop_level = 'ShopLevelView';//店铺等级
	protected $resources = 'resources';//相册/视频
	protected $comment_product = 'CommentProductView';
	protected $comment = 'comment';//评论信息
	protected $shop_time = 'shop_time';//日程安排
	protected $shop = 'shop';//店铺信息
	protected $shop_undergo = 'shop_undergo';//店铺的过往经历
	protected $shop_id = 0;//初始化店铺id 
	protected $member_info=array();
	protected $member_model='ShopView';
	
	public  function __autoload(){
		parent::__autoload();
		
		
		$this->seller_type = array( 			/*译者类型(公司或者个人) *重定义*/
				'1'=>'个人',
				'2'=>'公司',
		);
		$this->search = array(					/*定义搜索条件返回的数组中所包含的所有键值*/
				'language_id'=>'',				/*搜索的源语言*/
				'to_language_id'=>'',			/*搜索目标语言*/
				'ability_id'=>'',				/*搜索的技能属性*/
				'industry_id'=>'',				/*搜索的行业*/
				'type'=>'',						/*搜索的店铺类型*/
				'city'=>'',						/*城市*/
		);
		$this->product_type = list_to_tree(get_ability_cache());		/*获取缓存的分类属性转换为树状*/
		$this->shop_id = I('shop_id');
		if(session('home_member_id')){
			$info_k=get_info('member',array('id'=>session('home_member_id')));
			$this->member_info=$info_k;
		}
		$shop_info = $this->get_shop_description();//店铺信息	
// 		dump($shop_info);die;
		$shop_id = I('shop_id');
		if($shop_id){
			/*查询店铺信息*/
			$shop_info = get_info(D($this->member_model),array('id'=>$shop_id));
			if(empty($shop_info)){
				$this->error('店铺信息不存在');
			}else if(empty($shop_info['seller_id'])){
				$this->error('店铺信息不存在');
			}else if($shop_info and $shop_info["shop_status"]!=1){
				if($shop_info["shop_status"]==2){
					$this->error("该店铺已被禁用");
				}else{
					$this->error("该店铺暂未开通");
				}
			}
// 			dump($shop_info);
			//查询地理信息
			$area_data = get_area_cache();
			$area_list=list_to_tree($area_data);
			//查询店铺的地理信息
			$shop_area_id = $shop_info['area_id'];
			foreach ($area_list as $val){
				foreach ($val['_child'] as $v){
					foreach ($v['_child'] as $vv){
						if($vv['id']==$shop_area_id){
							$shop_info['city']=$v['title'];
							$shop_info['province']=$val['title'];
							$shop_info['area']=$vv["title"];
							$city_data=$val['_child'];
							$area_k=$v['_child'];
						}
					}
				}
			}
			/*获取过往经历*/
			$result1 = get_experience($shop_id, 1);
			if($result1['status'] == 1){
				$shop_info_old = $result1['shop_info'];
			}
			/*获取成功分享*/
			$result2 = get_experience($shop_id, 2);
			if($result2['status'] == 1){
				$shop_info_new = $result2['shop_info'];
			}
			
			/*获取语言信息*/
			$language = get_language_cache();
			/*获取技能信息*/
			$ability =list_to_tree(get_ability_cache());
			/*获取行业信息*/
			$industry = get_industry_cache();
			
			/*获取数组键值为语言ID的新数组*/
			foreach($language as $val){
				$language_id_key[$val['id']] =$val;
			}
			
			/*处理擅长语言*/
			$good_at = json_decode($shop_info['good_at'],true);
			foreach($good_at as $k=>$v){
				$good_at_new[$k]['id'] = $v;
				$good_at_new[$k]['title'] = $language_id_key[$v]['title'];
			}
			$shop_info['good_at_arr'] = $good_at_new;
			/*处理店铺logo*/
			if(is_file($shop_info['logo'])){
				$shop_info['logo'] = __ROOT__.'/'.$shop_info['logo'];
			}else{
				$shop_info['logo'] = __ROOT__.'/Public/Home/img/company_img.jpg';
			}
			/*处理数字值，将数字转化为文字*/
			$temp[0]=$shop_info;
			$temp = int_to_string($temp,array('type'=>$this->seller_type));
			$shop_info = $temp[0];
			//print_r($product_result);
			$data['shop_info_1'] = $shop_info;		
// 			dump($shop_info);die;	
			//获取店铺收藏信息
			$show_result = get_info('collect',array('collect_id'=>$this->shop_id));
// 			dump($show_result);die;
			$hot_product = get_hot_product($shop_id);
			
			//@赵群@查询地理信息
			$map2['member.id']=array('gt',0);
			$result = $this->page(D($this->member_model),$map2,$order,'',$this->limit);
			$area = get_area_cache();
			$area_id_key = array_id_key($area);
			$address_data = array();
			foreach ($result as $key=>$value){
				$address_data[] = get_info('address',array('member_id'=>$value['member_id']),$field=array('member_id','area_path'));
			}
			foreach ($address_data as $key=>$value){
				$address_arr = $value['area_path'];
				$path = explode('-',$address_arr);
				$new_address = $area_id_key[$path['2']]['title'].$area_id_key[$path['3']]['title'].$area_id_key[$path['4']]['title'];
				$address_data[$key]['path'] = $new_address;
			}
// 			dump($address_data);die;
			$data['result'] = $result;
			$data['hot_product'] = $hot_product;
			$data['shop_info_old'] = $shop_info_old;
			$data['shop_info_new'] = $shop_info_new;
			$data['show_result'] = $show_result;
			$this->assign($data)->assign('address_data',$address_data);
		}
	}
	
	//店铺的头部信息
	private function header(){
		$shop_info = $this->get_shop_description();//店铺信息
		$shop_comments = $this->get_shop_comments();//店铺评价
		$underGo = $this->underGo();//过往经历
		//成果分享？？？？？
		//将店铺的擅长的标签查询出来
		$good_at = $shop_info['good_at'];
		$good_at_ids = explode(',',$good_at);
		$good_at_data = get_language_recommend_cache();//被推荐的语种缓存
		$good_at_data = array_id_key($good_at_data);
		$shop_good_at = array();
		foreach($good_at_ids as $k=>$v){
			$shop_good_at[] = $good_at_data[$v]['title'];
		}
		//将店铺的热销产品查询出来
		$map = array(
			'shop_id'=>$this->shop_id,
			'recommend'=>1,
		);
		
		$recommend_product = get_result($this->product_table,$map,'','',10);
		
		//将店铺的地址查询出来
		$area_data = get_area_cache();
		$area_data = array_id_key($area_data);
		$area_data = $area_data[$shop_info['area_id']]['title'];
		$area_data = $area_data.$shop_info['address'];
		
		$data['underGo'] = $underGo;
		$data['shop_info'] = $shop_info;
		$data['shop_good_at'] = $shop_good_at;
		$data['recommend_product'] = $recommend_product;
		$data['area_data'] = $area_data;
		$this->assign($data);
		$this->assign($shop_comments);
	}
	
	//获取店铺的日程计划
	private function get_date_programme(){
		$map['shop_id'] = $this->shop_id;
		$shop_time = get_result($this->shop_time,$map);
		//将店铺的日程按照一天中的三个时间段组织起来
		$shop_date_programme = array();
		foreach($shop_time as $k=>$v){
			if($v['time']==1){
				$shop_date_programme[1][]=$v;
			}else if($v['time']==2){
				$shop_date_programme[2][]=$v;
			}else if($v['time']==3){
				$shop_date_programme[3][]=$v;
			}
		}
		return $shop_date_programme;
	}
	//获取店铺的服务语言
	private function get_servece_language($limit){
		$map['shop_id'] = $this->shop_id;
		$map['assess']=1;//后台已通过的
		$map['status']=1;
		$servece_language = get_result($this->product_table,$map,'','id desc',$limit);
		return $servece_language;
	}
	//获取店铺的相册
	private function get_albumVideo_data(){
		$map['pid'] = $this->shop_id;
		$map['type'] = array('in',array(1,4));//表示表中的视频和店铺图片
		
		$shop_resource = $this->page($this->resources,$map);
		return $shop_resource;
	}
	//获取店铺资料
	private function get_shop_description(){
		$shop_id = $this->shop_id;
		$map['id'] = $shop_id;
		//关联查询店铺信息和店铺等级信息
		$shop_info = get_info(D($this->shop_level),$map);
		return $shop_info;
	}
	//获取店铺的所有评价
	private function get_shop_comments(){
        $post = I('post.');
		$sort = 'comment.add_time desc';
		//$this->ajaxReturn($post['shop_id']);
		$maps["type"]=array('in',array(1,2,3));
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
		//获取筛选数据
		$maps['shop_id'] = $this->shop_id;

		//$maps['status'] = 2;----
		$maps['pid'] = 0;//表示正常的回复
		//$maps = array_merge($maps,$map);
		//计算店铺的星级？？？？？？
		/*if(I('get.type')){
				$maps=array(
				'type'=>I('get.type'),
				'status'=>2,
				);
			}
		else{
			$maps=array(
			'type'=>array(in,array(1,2,3)),
			'status'=>2,
			);
		}*/
		$maps["status"]=2;//审核通过并被启用的评论

		//dump($maps);
		//exit;
		//统计店铺的好评数,中评数，差评数
		$shop_good_comments = get_result($this->comment,array('shop_id'=>$this->shop_id,'status'=>2,'type'=>1,'pid'=>0));
		$good_comments_num = count($shop_good_comments);
		
		$shop_mid_comments = get_result($this->comment,array('shop_id'=>$this->shop_id,'status'=>2,'type'=>2,'pid'=>0));
		$mid_comments_num = count($shop_mid_comments);
		
		$shop_bad_comments = get_result($this->comment,array('shop_id'=>$this->shop_id,'status'=>2,'type'=>3,'pid'=>0));
		$bad_comments_num = count($shop_bad_comments);

		$all_comments_num = $good_comments_num+$mid_comments_num+$bad_comments_num;//所有评价的次数

		$shop_comments = $this->page(D($this->comment_product),$maps,$sort,'',$this->limit);
 		//dump($shop_comments);//die;
		
		//$this->ajaxReturn(session('sql'));
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
			'all_comments_num' => $all_comments_num,
			'shop_comments' => $shop_comments,
			'comments_image' =>	$comments_image, 
		);
		return $comments;
	}
	
	/**
	 *店铺评价
	 *		显示店铺的评价信息
	 **/
	public function evaluate(){
		$this->header();//店铺内页主要模块信息
		$post = I('post.');
		//$this->ajaxReturn($post['radio']);
		//获取用户的评价等信息
		$comments = $this->get_shop_comments();//店铺的评价信息
		//$this->ajaxReturn(count($comments['shop_comments']));
		$this->assign($comments);
		//dump($comments);die;
		if(!empty($post)){
			$this->display('comments_ajax');
		}else{
			$this->display('comments');
		}
	}
	
	
	//获取店铺的过往经历
	private function underGo(){
		 $shop_id = session('home_shop_id');
		 $map['shop_id'] = $shop_id;
		 $shop_info = get_result($this->shop_undergo,$map,'','start_time desc');
		return $shop_info;
	}
	/**
	 * 店铺搜索列表首页
	 * 按照客户点击的筛选条件来设置查询条件  
	 * 查询出店铺内有用户搜索商品的所有店铺
	 * @author						李东
     * @date						2015-07-07
	 */
    public function index(){
    	//手机app接口密钥
    	$apptype = (!empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY'))?true:false;
    	if($apptype){
    		$app_key=trim(I("post.key"));//md5加密的登录时间
    		$member_id=I("post.home_member_id");//用户id
    		$this->isLoginExpire($app_key,$member_id);//判断登录过期
    	}
    	/*通过产品列表与用户选择条件获取店铺查询条件*/
    	$map2 = $this->get_term();
    	//排序
    	$ord=intval(I('order'));
    	if($ord<=0){
    		$order = 'recommend desc';	/*默认排序条件*/
    	}else if($ord==1){
    		$order = 'star_num desc';
    	}else if($ord==2){
    		$order = 'comment_num desc';
    	}
    	$data['order']=$ord;
    	
    	//价格搜索
    	$min_price=I('min_price');
    	$data['min_price']=$min_price;
    	$max_price=I('max_price');
    	$data['max_price']=$max_price;
    	$price_map=array();
    	if(is_numeric($min_price)){
    		$price_map[]=array('egt',$min_price);
    	}else{
    		$price_map[]=array('egt',0);
    	}
    	if(is_numeric($max_price)){
    		$price_map[]=array('elt',$max_price);
    	}
    	if(count($price_map)>=2){
    		$price_map[]='and';
    		$map2['min_price']=$price_map;
    	}else{
    		$map2['min_price']=$price_map[0];
    	}
		
		
    	//标题值搜索
		$language_text = id_and_text(get_language_cache());
    	$keywords=I('keywords','');
    	if(!empty($keywords)){
    		$map2['title']=array('like','%'.$keywords.'%');
    	}
    	$data['keywords']=$keywords;
    	$map2['member.id']=array('gt',0);
    	$result = $this->page(D($this->member_model),$map2,$order,'',$this->limit);
// 		dump($result);die;
    	/*计算查询出的店铺数量*/
    	$seller_count = count($result); 
		
    	/*获取语言信息*/
    	$language = get_language_cache();  
    	/*获取技能信息*/
    	$ability =list_to_tree(get_ability_cache());
    	/*获取行业信息*/
    	$industry = get_industry_cache();
    	
    	/*获取数组键值为语言ID的新数组*/
    	foreach($language as $val){
    		$language_id_key[$val['id']] =$val; 
    	}
    	foreach($result as $key => $row){
    		/*处理擅长语言*/
    		$good_at = json_decode($row['good_at'],true);
    		$good_at_new=array();//必须设置为空，不然会影响循环之后的数据
    		foreach($good_at as $k=>$v){
    			$good_at_new[$k]['id'] = $v;
    			$good_at_new[$k]['title'] = $language_id_key[$v]['title'];
    		}
    		$result[$key]['good_at_arr'] = $good_at_new;
    		/*处理店铺logo*/
    		if(is_file($row['logo'])){
    			$result[$key]['logo'] = __ROOT__.'/'.$row['logo'];
    		}else{
    			$result[$key]['logo'] = __ROOT__.'/Public/Home/img/company_img.jpg';
    		}
    	}
    	/*常见问题*/
    	$common_problem = get_common_problem();
    	$data['common_problem'] = $common_problem;
		$data['seller_count'] = $seller_count;
    	$data['result']=$result;
    	$data['language']=$language;
    	$data['ability']=$ability;
    	$data['industry']=$industry;
    	$data['seller_type'] =$this->seller_type;
    	$data['param'] = $this->search;
    	if($apptype){
    		$this->ajaxReturn($data);//返回给手机app的json数据
    	}
    	
    	$buy_shop=array();
    	if(!empty($this->member_info)){
    		$buy_shop=json_decode($this->member_info['has_buy'],true);
    		
    	}
    	$data['buy_shop']=$buy_shop;
    	//@赵群@查询地理信息
    	$area = get_area_cache();
    	$area_id_key = array_id_key($area);
		$address_data = array();
		foreach ($result as $key=>$value){
			$address_data[] = get_info('address',array('member_id'=>$value['member_id']),$field=array('member_id','area_path'));
		}
		foreach ($address_data as $key=>$value){
			$address_arr = $value['area_path'];
			$path = explode('-',$address_arr);
			$new_address = $area_id_key[$path['2']]['title'].$area_id_key[$path['3']]['title'].$area_id_key[$path['4']]['title'];
			$address_data[$key]['path'] = $new_address;
		}
// 		dump($address_data);die;
// dump($data);die;
    	$this->assign($data)->assign('address_data',$address_data);
        $this->display();
    }

    //店铺内页主页
    public function lists(){
    	$shop_id = I('get.shop_id');
    	
    	$status = true;
    	if(intval($shop_id)<=0){
    		/*判断参数是否符合规则*/
    		$result = array('status'=>'0','msg'=>'错误请求');
    		$status = false;
    	}
    	if($status){
    		/*如果前置条件符合，执行以下操作*/
    		
	    	/*查询店铺信息*/
	    	$shop_info = get_info($this->table,array('id'=>$shop_id));
	    	//处理营业时间
// 	    	$work_time_s = json_decode($shop_info['work_time_s'],true);
// 	    	$work_time_e = json_decode($shop_info['work_time_e'],true);
	    	/*获取语言信息*/
	    	$language = get_language_cache();
	    	/*获取技能信息*/
	    	$ability =list_to_tree(get_ability_cache());
	    	/*获取行业信息*/
	    	$industry = get_industry_cache();
	    	 
	    	/*获取数组键值为语言ID的新数组*/
	    	foreach($language as $val){
	    		$language_id_key[$val['id']] =$val;
	    	}
	    
    		/*处理擅长语言*/
    		$good_at = json_decode($shop_info['good_at'],true);
    		foreach($good_at as $k=>$v){
    			$good_at_new[$k]['id'] = $v;
    			$good_at_new[$k]['title'] = $language_id_key[$v]['title'];
    		}
    		$shop_info['good_at_arr'] = $good_at_new;
    		/*处理店铺logo*/
    		if(is_file($shop_info['logo'])){
    			$shop_info['logo'] = __ROOT__.'/'.$shop_info['logo'];
    		}else{
    			$shop_info['logo'] = __ROOT__.'/Public/Home/img/company_img.jpg';
    		}
    		/*处理数字值，将数字转化为文字*/
			$temp[0]=$shop_info;
			$temp = int_to_string($temp,array('type'=>$this->seller_type));
			$shop_info = $temp[0];
	    	
	
	    	/*查询店铺所属商品*/
	    	$map['status']=1;
	    	$map['shop_id'] = $shop_id;
	    	/*默认排序条件*/
	    	$order = ' recommend desc,sort desc,id desc';
	    	$product_result = get_result(D($this->product_model),$map,'',$order);
	    	
	    	/*获取格式为 id=>title 的语言分类数组*/
	    	$language_text = id_and_text(get_language_cache());
	    	/*获取格式为 id=>title 的等级分类数组*/
	    	$level_text = id_and_text(get_product_level_cache());
	    	/*获取格式为 id=>title 的产品分类数组*/
	    	$product_type=id_and_text($this->product_type);
	    	
	    	$product_result = int_to_string($product_result,array('level_id'=>$level_text,"type"=>$product_type,"language_id"=>$language_text,"to_language_id"=>$language_text,));
	    	/*将json格式ID转为逗号分隔的Title*/
	    	
	    	/*获取格式为 id=>title 的领域分类数组*/
	    	$industry_text = id_and_text(get_industry_cache());
	    	/*获取格式为 id=>title 的属性分类数组*/
	    	$ability_text = id_and_text(get_ability_cache());
	    	/*将Json中所有ID转换成文字字符串*/
	    	$product_result = json_to_chars($product_result,array('industry_id'=>$industry_text,'ability_id'=>$ability_text,));

	    	//print_r($product_result);
	    	$data['shop_info'] = $shop_info;
	    	$data['product_result'] = $product_result;
	    	
	    	//店铺的过往经历
	    	/********************************TODO:最近浏览**************************************************/
	    	$view_shops = cookie('view_shops');
// 	    	print_r($view_shops);
// 	    	echo '<br/>';
// 	    	cookie('view_shops',$view_shops);
	    	$view_shops[$shop_info['id']]=json_encode($shop_info);
	    	cookie('view_shops',$view_shops);
// 	    	echo '<pre />';
// 	    	print_r(cookie('view_shops'));
	    	/*************************************************************************************/
	    	
	    	$this->assign($data);
	    	
	    	$this->display();
    	}else{
    		/*如果前置条件不符合，执行以下操作*/
    		$this->error($result['msg']);
    	}
    }
	
    public function detail(){
		$post = I('post.');
		//加载头部信息
		$this->header();//店铺内页主要模块信息
		$shop_time = $this->get_date_programme();//日程安排
		$shop_servece = $this->get_servece_language(2);//店铺的服务,2表示查询两个
		
// 		$all_product = 
		
		
		/*查询店铺所属商品*/
		$shop_id = I('get.shop_id');
		$map['status']=1;
		$map['shop_id'] = $shop_id;
		/*默认排序条件*/
		$all_product = get_result(D($this->product_model),$map);
		
		/*获取格式为 id=>title 的语言分类数组*/
		$language_text = id_and_text(get_language_cache());
		/*获取格式为 id=>title 的等级分类数组*/
		$level_text = id_and_text(get_product_level_cache());
		/*获取格式为 id=>title 的产品分类数组*/
		$product_type=id_and_text($this->product_type);
		
		$all_product = int_to_string($all_product,array('level_id'=>$level_text,"type"=>$product_type,"language_id"=>$language_text,"to_language_id"=>$language_text,));
		/*将json格式ID转为逗号分隔的Title*/
		
		/*获取格式为 id=>title 的领域分类数组*/
		$industry_text = id_and_text(get_industry_cache());
		/*获取格式为 id=>title 的属性分类数组*/
		$ability_text = id_and_text(get_ability_cache());
		/*将Json中所有ID转换成文字字符串*/
		$all_product = json_to_chars($all_product,array('industry_id'=>$industry_text,'ability_id'=>$ability_text,));
		
		$data['all_product'] = json_encode($all_product);
		
		//$all_product=
		
		$type_arr=$this->product_type;
		$type=array();
		foreach($type_arr as $key=>$val){
			$type[$val['id']]=$val['title'];
		}
		
		$shop_servece=int_to_string($shop_servece,array('type'=>$type));
		$map['pid'] = $this->shop_id;
		$map['type'] = array('in',array(1,4));//表示表中的视频和店铺图片
		
		$shop_albumVideo_data = get_result($this->resources,$map);
		$shop_info = $this->get_shop_description();//表示店铺的描述信息
		$comments = $this->get_shop_comments();//店铺的评价信息
		//print_r($shop_albumVideo_data);exit;
		$data['shop_time'] = $shop_time;
		$data['shop_servece'] = $shop_servece;
		$data['shop_albumVideo_data'] = $shop_albumVideo_data;
		$data['shop_info'] = $shop_info;
		
		
    	$language = get_language_cache();  
    	
    	/*获取数组键值为语言ID的新数组*/
    	foreach($language as $val){
    		$language_id_key[$val['id']] =$val; 
    	}
   
    	$good_at = json_decode($shop_info['good_at'],true);
    	foreach($good_at as $k=>$v){
    		$good_at_new[$k]['id'] = $v;
    		$good_at_new[$k]['title'] = $language_id_key[$v]['title'];
    	}
    	$shop_info['good_at_arr'] = $good_at_new;
		
		if(cookie('view_shops')){
			
		 	$remark=json_decode(cookie('view_shops'),true);
		 	$temp=0;
		 	$_key=count($remark);
		 	foreach($remark as $key=>$val){
		 		$_val=json_decode($val,true);
		 		if($_val['id']==$shop_info['id']){
		 			$temp=1;
		 			break;
		 		}
		 	}
		 	if($temp==0){
		 		$remark[$_key]=json_encode(array('id'=>$shop_info['id'],'logo'=>$shop_info['logo'],'title'=>$shop_info['title'],'service_times'=>$shop_info['service_times'],'good_at_arr'=>$shop_info['good_at_arr']));
		 		cookie('view_shops',json_encode($remark));
		 	}
		 }else{
		 	$remark[0]=json_encode(array('id'=>$shop_info['id'],'logo'=>$shop_info['logo'],'title'=>$shop_info['title'],'service_times'=>$shop_info['service_times'],'good_at_arr'=>$shop_info['good_at_arr']));
		 	cookie('view_shops',json_encode($remark));
		 }
		$this->assign($data);
// 		dump($data);die;
		$this->assign($comments);
		if(IS_AJAX){
			$this->display('comments_ajax');
		}else{
			$this->display('detail');
		}
    }
    
    //店铺和商品收藏
    public function collect(){
    $member_id = session('home_member_id');
    		$gets = I('get.');
    		$collect_id = $gets['shop_id'];
			$id = $gets['id'];
    		$_POST = array(
    				'id'=>$id,
    				'member_id'=>$member_id,
    				'collect_id'=>$collect_id,
    				'type'=>1,
    				'status'=>1,
    		);
    		$collection_data = update_data('collect',array('collect_id'=>$collect_id));
    		if(is_numeric($collection_data)){
    			$this->success('收藏成功！');
    		}else{
    			$this->error('收藏失败！');
    		}
    }
    
    //取消店铺收藏
    public function collect_cancel(){
    	$member_id = session('home_member_id');
    		$gets = I('get.');
    		$collect_id = $gets['shop_id'];
			$id = $gets['id'];
    		$_POST = array(
    				'id'=>$id,
    				'member_id'=>$member_id,
    				'collect_id'=>$collect_id,
    				'type'=>1,
    				'status'=>-1,
    		);
    		$collection_data = update_data('collect',array('collect_id'=>$collect_id));
    		if(is_numeric($collection_data)){
    			$this->success('取消收藏成功！');
    		}else{
    			$this->error('取消收藏失败！');
    		}
    }
	
	//表示店铺的服务信息
	public function servece(){
 		$this->header();//店铺内页主要模块信息
// 		//获取用户的评价等信息
 		$comments = $this->get_shop_comments();//店铺的评价信息
// 		//店铺的服务信息
// 		$shop_servece = $this->get_servece_language(10);//店铺的服务,2表示查询两个
		
// 		$data['shop_servece'] = $shop_servece;

		$shop_id = I('get.shop_id');
		 
		$status = true;
		if(intval($shop_id)<=0 and empty(I("post."))){
			/*判断参数是否符合规则*/
			$result = array('status'=>'0','msg'=>'错误请求');
			$status = false;
		}
		if($status){
			//获取用户的评价等信息
			$comments = $this->get_shop_comments();//店铺的评价信息
			/*如果前置条件符合，执行以下操作*/
		
			/*查询店铺信息*/
			$shop_info = get_info($this->table,array('id'=>$shop_id));
		
			/*获取语言信息*/
			$language = get_language_cache();
			/*获取技能信息*/
			$ability =list_to_tree(get_ability_cache());
			/*获取行业信息*/
			$industry = get_industry_cache();
			 
			/*获取数组键值为语言ID的新数组*/
			foreach($language as $val){
				$language_id_key[$val['id']] =$val;
			}
			 
			/*处理擅长语言*/
			$good_at = json_decode($shop_info['good_at'],true);
			foreach($good_at as $k=>$v){
				$good_at_new[$k]['id'] = $v;
				$good_at_new[$k]['title'] = $language_id_key[$v]['title'];
			}
			$shop_info['good_at_arr'] = $good_at_new;
			/*处理店铺logo*/
			if(is_file($shop_info['logo'])){
				$shop_info['logo'] = __ROOT__.'/'.$shop_info['logo'];
			}else{
				$shop_info['logo'] = __ROOT__.'/Public/Home/img/company_img.jpg';
			}
			/*处理数字值，将数字转化为文字*/
			$temp[0]=$shop_info;
			$temp = int_to_string($temp,array('type'=>$this->seller_type));
			$shop_info = $temp[0];
		
		
			/*查询店铺所属商品*/
			$map['status']=1;
			$map['shop_id'] = $shop_id;
			$map['assess']=1;
			/*默认排序条件*/
			$order = ' recommend desc,sort desc,id desc';
			$product_result = get_result(D($this->product_model),$map,'',$order);
		
			/*获取格式为 id=>title 的语言分类数组*/
			$language_text = id_and_text(get_language_cache());
			/*获取格式为 id=>title 的等级分类数组*/
			$level_text = id_and_text(get_product_level_cache());
			/*获取格式为 id=>title 的产品分类数组*/
			$product_type=id_and_text($this->product_type);
		
			$product_result = int_to_string($product_result,array('level_id'=>$level_text,"type"=>$product_type,"language_id"=>$language_text,"to_language_id"=>$language_text,));
			/*将json格式ID转为逗号分隔的Title*/
		
			/*获取格式为 id=>title 的领域分类数组*/
			$industry_text = id_and_text(get_industry_cache());
			/*获取格式为 id=>title 的属性分类数组*/
			$ability_text = id_and_text(get_ability_cache());
			/*将Json中所有ID转换成文字字符串*/
			$product_result = json_to_chars($product_result,array('industry_id'=>$industry_text,'ability_id'=>$ability_text,));
		
			//print_r($product_result);
			$data['shop_info'] = $shop_info;
			$data['product_result'] = $product_result;
			
			/*查询店铺所属商品*/
			$shop_id = I('get.shop_id');
			$map['status']=1;
			$map['shop_id'] = $shop_id;
			/*默认排序条件*/
			$all_product = get_result(D($this->product_model),$map);
			
			/*获取格式为 id=>title 的语言分类数组*/
			$language_text = id_and_text(get_language_cache());
			/*获取格式为 id=>title 的等级分类数组*/
			$level_text = id_and_text(get_product_level_cache());
			/*获取格式为 id=>title 的产品分类数组*/
			$product_type=id_and_text($this->product_type);
			
			$all_product = int_to_string($all_product,array('level_id'=>$level_text,"type"=>$product_type,"language_id"=>$language_text,"to_language_id"=>$language_text,));
			/*将json格式ID转为逗号分隔的Title*/
			
			/*获取格式为 id=>title 的领域分类数组*/
			$industry_text = id_and_text(get_industry_cache());
			/*获取格式为 id=>title 的属性分类数组*/
			$ability_text = id_and_text(get_ability_cache());
			/*将Json中所有ID转换成文字字符串*/
			$all_product = json_to_chars($all_product,array('industry_id'=>$industry_text,'ability_id'=>$ability_text,));
			
			$data['all_product'] = json_encode($all_product);
		
			//店铺的过往经历
			/********************************TODO:最近浏览**************************************************/
			$view_shops = cookie('view_shops');
			// 	    	print_r($view_shops);
			// 	    	echo '<br/>';
			// 	    	cookie('view_shops',$view_shops);
			$view_shops[$shop_info['id']]=json_encode($shop_info);
			cookie('view_shops',$view_shops);
			// 	    	echo '<pre />';
			// 	    	print_r(cookie('view_shops'));
			/*************************************************************************************/
			$this->assign($data);
			$this->assign($comments);
			if(IS_AJAX){
				$this->display('comments_ajax');
			}else{
				$this->display('list');
			}
		}else{
    		/*如果前置条件不符合，执行以下操作*/
    		$this->error($result['msg']);
    	}
	}
	/**
	*店铺的相册和视频
	*	显示店铺的相册和视频
	*流程分析
	*	找到当前用户查看的店铺的id
	*	根据id查找店铺的资源播放
	**/
	public function videos(){
		$this->header();//店铺内页主要模块信息
		$shop_resource = $this->get_albumVideo_data();
		
		$data['shop_resource'] = $shop_resource;
		
		$this->assign($data);
		$this->display();
	}
	//表示详细信息显示
	public function info(){
		$this->header();//店铺内页主要模块信息
		$shop_info = $this->get_shop_description();
		$data['shop_info'] = $shop_info;
		//店铺的过往经历
		$underGo = $this->underGo();//店铺的过往经历
		
		$data['underGo'] = $underGo;
		$this->assign($data);
		$this->display('info');
	}

	/*
	 * 搜索时获取技能
	 * */
	public function get_ability($id){
		$brands_id = get_result('category',array('path'=>array('like',"%$id%")),'id');
		$brand_ids = array();
		foreach($brands_id as $v){
			$brand_ids[] = $v['id'];
		}
		//将查询条件写入到$map中
		if(!empty($brand_ids)){
			foreach ($brand_ids as $key=>$val){
				$arr[]=array('like','%"'.$val.'"%');		
			}
			if(count($arr)>=2){
				$arr[]='or';
				return $arr;
			}else{
				return $arr[0];
			}
			
			
		}else{
			return array('like','%"'.$id.'"%');
		}
	}
	
    /** 
     * 获取首页搜索参数，组装查询条件，赋值已选择参数
     * @return array 				返回查询店铺的搜索条件
     * 
     * @author						李东
     * @date						2015-07-07
     */
    public function get_term(){
    	$gets = I('get.');
		$post = I('get.');//接收搜索框条件
		/*搜索框条件加入*/
		if(!empty($_GET['type'])){
			//查询技能的id
			$brand_id = $post['ability_id'];
				//查询属于这个技能的所有子技能
//			$brands_id = get_result('category',array('path'=>array('like',"%$brand_id%")),'id');
//			$brand_ids = array();
//			foreach($brands_id as $v){
//				$brand_ids[] = $v['id'];
//			}
//			//将查询条件写入到$map中
//			if(!empty($brand_ids)){
//				foreach ($brand_ids as $key=>$val){
//					$arr[]=array('like','%"'.$val.'"%');
//				
//				}
//				$arr[]='or';
//				$map['ability_id'] = $arr;
//			}
			//$map['ability_id']=$this->get_ability($brand_id);
			
			//$this->search['brand'] = $brand_id;
			$this->search['ability_id'] = $brand_id;
			
			if($post['type']!=3){//查询的是个人翻译公司或个人译者
				/*按选择的店铺类型设置查询条件*/
				$map2['type'] = $post['type'];
				/*设置搜索条件中的参数值*/
				$this->search['type'] = $post['type'];
				//关键词查询
				if(!empty($post['search'])){
					$map2['title'] = array('like',"%".$post['search']."%");
					$this->search['search'] = $post['search'];
				}
			}else{//查询的是语言
				//查询语言关键词的id
				if(!empty($post['search'])){//关键词不能为空
					$language_id = get_result('category',array('title'=>array('like','%'.$post['search'].'%')),'id');
					if(!empty($language_id)){
						$language_ids = array();
						foreach($language_id as $k=>$v){
							$language_ids[] = $v['id']; 
						}
						$map['language_id|to_language_id'] = array('in',$language_ids);
//						if(count($language_ids)==1){
//							$this->search['language_id'] = $language_ids[0];
//							$this->search['to_language_id'] = $language_ids[0];
//						}
						
					}
					$this->search['search'] = $post['search'];
				}
				$this->search['type'] = $post['type'];
			}
 		}
    	/*产品条件*/
    	if($gets['language_id']){    	
    		/*按选择的源语言设置查询条件*/
    		$map['language_id'] = $gets['language_id'];
    		/*设置搜索条件中的参数值*/
    		$this->search['language_id'] = $gets['language_id'];
    	}
    	if($gets['to_language_id']){
    		/*按选择的目标语言设置查询条件*/
    		$map['to_language_id'] = $gets['to_language_id'];
    		/*设置搜索条件中的参数值*/
    		$this->search['to_language_id'] = $gets['to_language_id'];
    	}
    	if($gets['ability_id']){  		
    		
    		$map['ability_id']=$this->get_ability($gets['ability_id']);
    		
    		//print_r($map['ability_id']);exit;
    		
    		/*按选择的属性设置查询条件*/
    		//$map['ability_id'] =array('like','%"'.$gets['ability_id'].'"%');
    		/*设置搜索条件中的参数值*/
    		$this->search['ability_id'] = $gets['ability_id'];
    	}
    	if($gets['industry_id']){
    		/*按选择的行业设置查询条件*/
    		$map['industry_id'] =array('like','%"'.$gets['industry_id'].'"%');
    		/*设置搜索条件中的参数值*/
    		$this->search['industry_id'] = $gets['industry_id'];
    	}
    	
    	if(session('cur_city_id')){
    		$area_list=$this->getArea(session('cur_city_id'));
    		$area[0]=session('cur_city_id');
    		foreach ($area_list as $key=>$val){
    			$area[]=$val['id'];
    		}
    		$map2['area_id']=array('in',$area);
    	}
    	
    	/*店铺条件*/
    	//if($gets['type']){
    		/*按选择的店铺类型设置查询条件*/
    	//	$map2['type'] = $gets['type'];
    		/*设置搜索条件中的参数值*/
    	//	$this->search['type'] = $gets['type'];
    	//}
    	 
    	$map2['status']=1;
    	$map['assess']=1;
		$map['status']=1;
    	//dump($map2);
    	//dump($map);
    	$product_info = get_result($this->product_table,$map,'shop_id');
    	foreach($product_info as $v){
    		$shop_ids_temp[] = $v['shop_id'];
    	}
    	/*去除重复的店铺ID*/
    	$shop_ids = array_flip(array_flip($shop_ids_temp));
    	if(!empty($shop_ids)){
    		$map2['id'] = array('in',$shop_ids);
    	}else{
    		$map2['id'] = 0;
    	}
    	return $map2;
    }
	
    public function interpret(){
    	if(IS_POST){
//    		if(!session('home_member_id')){
//    			$this->redirect('User/Login/index');
//    		}
//    		$_POST['member_id']=session('home_member_id');
    		$posts=$_POST;
    		if(empty($posts['language_id'])){
    			$this->error('请选择源语言');
    		}
    		if(empty($posts['to_language_id'])){
    			$this->error('请选择目的语言');
    		}
    		if(empty($posts['language_id'])){
    			$this->error('请选择译者身份');
    		}
    		if(empty($posts['name'])){
    			$this->error('请填写姓名');
    		}
    		if(empty($posts['telephone'])){
    			$this->error('请填写手机号');
    		}
    		if($posts['language_id']==$posts['to_language_id']){
    			$this->error('源语言和目的语言不能相同');
    		}
    		if(empty($posts['language_id'])){
    			$this->error('请选择源语言');
    		}
    		$_POST['status']=0;
    		$ret=update_data('interpret');
    		if(is_numeric($ret)){
    			$this->success('提交成功');
    		}else{
    			$this->error('提交失败');
    		}
    	}

    }
}