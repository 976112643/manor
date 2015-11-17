<?php
namespace User\Controller;
use Home\Controller\HomeController;
class CollectController extends HomeController {
	protected $table_collect='collect';							/*收藏表*/
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
	}
	
    public function index(){
    	$member_id = session('home_member_id');
    	if($member_id){
			$result_collect = get_result($this->table_collect,array('member_id'=>$member_id,'status'=>1));
			$result = array();
			foreach ($result_collect as $key=>$value){
				$result[] = get_info(D($this->member_model),array('id'=>$value['collect_id']));
				$result[$key]['collect_id'] = $value['id'];
			}
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
// 			dump($data['result']);die;
			$this->assign($data)->assign('address_data',$address_data);
			$this->display();
    	}else{
    		$this->error('请先登录！');
    	}   
    }
}