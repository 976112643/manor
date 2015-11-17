<?php
namespace Home\Controller;
use Home\Controller\HomeController;
class IndexController extends HomeController {
	private $table = 'banner';
	private $info = array(); //主页数据
	
	public function __autoload(){
		parent::__autoload();
		$banner = getBanner('home');
		$advert = getBanner('advert');
		$news = get_recommend_articles('news',9);
		
		$notice = get_recommend_articles('notice',9);
		
		/*个人译者店铺*/
		$shop_personal = get_recommend_shop(1);
		/*公司店铺*/
		$shop_company = get_recommend_shop();
		
		$shop_new = get_new_shop();
		$written_shop = $this->recommend_shop(36);
		
		$interpreter_shop = $this->recommend_shop(37);
		/*获取网站指数*/
		$site_index = get_site_index();
		$data['site_index'] = $site_index;
		$data['home_banner']=$banner;
		$data['advert']=$advert;
		$data['news']=$news;
		$data['notice']=$notice;
		$data['shop_personal']=$shop_personal;
		$data['shop_company']=$shop_company;
		$data['shop_new']=$shop_new;
		
		$data['written_shop']=$written_shop;
		$data['interpreter_shop']=$interpreter_shop;
		
		$this->info=$data;
		$this->assign($data);
	
	}

	
	/**
	 * 获取推荐的店铺
	 * @param			$type	店铺类型
	 * @param			$limit	显示条数
	 * 
	 * @author			李东
	 * @date			2015-07-16
	 */
	function recommend_shop($type = '',$limit=5){
		if($type){
			/*获取有该类型的产品的店铺*/
			$map2['type'] = $type;
			$map2['status'] = 1;
			$product_info = get_result('products',$map2,'shop_id');
			foreach($product_info as $v){
				$shop_ids_temp[] = $v['shop_id'];
			}
			$shop_ids = array_flip(array_flip($shop_ids_temp));
			if(!empty($shop_ids)){
				$map['id']=array('in',$shop_ids);
			}
		}
		$map['status'] = 1;
		$map['recommend'] = 1;
		$order = ' id desc';
		$result = get_result('shop',$map,'',$order,$limit);
		foreach ($result as $k=> $row){
		
			/*将店铺服务时间转为小时为单位，字数转为万字为单位*/
			$result[$k]['total_translate_time_w'] = sprintf('%.1f',$row['total_translate_time']/3600);
			$result[$k]['total_translate_num_w'] = sprintf('%.1f',$row['total_translate_num']/10000);
		}
		return  $result;
	}
	
	/**
	*
	**/
    public function index(){
		if(C('IS_MOBILE')==1){//表示是手机访问
			
		}else if(C('IS_MOBILE')==0){//表示是pc访问
			
		}
		$apptype=$this->appParam();
		if($apptype==-1){
			$apptype=false;
		}else{
			$apptype=true;
		}
		//$apptype = (!empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY'))?true:false;//手机app接口密钥
		
		if($apptype){
			
			$language = get_language_cache();
			
			foreach($this->info["shop_company"] as $k=>$row){
			
				$language_id_key = array_id_key($language);
				$good_at = json_decode($row['good_at'],true);
				$good_at_info=array();
				$i=0;
				foreach($good_at as $key=>$val){
					if($i<2){
						if(!empty($language_id_key[$val]['title'])){
							$i++;
							$good_at_info[]=$language_id_key[$val]['title'];
						}
					}else{
						continue;
					}
				}
				if(!empty($good_at_info)){
				  $this->info["shop_company"][$k]["good_at_info"]=implode(" ",$good_at_info);
				}
			}
			$rec_language = get_language_recommend_cache();				/*查询推荐的源语言*/
		
			$this->info['recommend_language'] = $rec_language;
			dump($info);
			$this->ajaxReturn($this->info);
			
		}else{
			//查询出banner数据
			$result = get_result($this->table,array('status'=>1));
			if(C('IS_MOBILE')==1){//表示是手机访问
				$this->ajaxReturn(array('result'=>$result));
			}else if(C('IS_MOBILE')==0){//表示是pc访问
				$this->display();
			}
		}
    }
}