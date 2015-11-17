<?php
namespace Backend\Controller\Products;
use Backend\Controller\Base\AdminController;
class ProductsController extends AdminController {
	protected $table='products';
	protected $model='ProductsView';
	protected $limit=15;		
	
	/**
	 * 分类列表
	 * 查询出所有商品的分类信息
	 * 包括语种分类，行业分类，技能分类
	 * 三种分类单独显示列表时，以type区分，type值按顺序分别为0，1，2
	 * @author						李东
	 * @date						2015-06-12
	 */
// 	public function index(){
// 		$map['status'] = array('gt','-1');
// 		$result = $this->page(D($this->model),$map,$order='',$field=array(),$this->limit);
// 		$result=int_to_string($result,array("product_status"=>array("0"=>"审核","1"=>"取消审核"),"recommend"=>array("0"=>"推荐","1"=>"取消推荐")));
// 		$data['result']=$result;
		
		
// // 		$result = $this->page(D($this->model),$map,$order='',$field=array(),$this->limit);
// // 		/*获取格式为 id=>title 的语言分类数组*/
// // 		$language_text = id_and_text(get_language_cache());
// // 		/*获取格式为 id=>title 的等级分类数组*/
// // 		$level_text = id_and_text(get_product_level_cache());
// // 		/*获取格式为 id=>title 的产品分类数组*/
// // 		$product_type=id_and_text($this->product_type);
// // 		$result = int_to_string($result,array('level_id'=>$level_text,"type"=>$product_type,"language_id"=>$language_text,"to_language_id"=>$language_text,));
// // 		/*获取格式为 id=>title 的领域分类数组*/
// // 		$industry_text = id_and_text(get_industry_cache());
// // 		/*获取格式为 id=>title 的属性分类数组*/
// // 		$ability_text = id_and_text(get_ability_cache());
// // 		/*将Json中所有ID转换成文字字符串*/
// // 		$result = json_to_chars($result,array('industry_id'=>$industry_text,'ability_id'=>$ability_text,));
		
			
// 		$this->assign($data);
// 		$this->display('index');
// 	}
	
	public function index(){
		//接收用户筛选信息,具体的信息看，组织查询的条件
		$map['status']=array('GT',-1);
		$keywords=I('keywords');
		$language_title=I('language_title');
		$to_language_title=I('to_language_title');
		//获取语言信息
		$temp_language= get_language_cache();
		$language=array();
		foreach($temp_language as $val){
			//让数组的Key值与当前数据的ID对应
			$language[$val['id']]=$val;
		}
		
		if($keywords){
			$keywords= trim($keywords);
			$map['shop_title']=array('like', "%$keywords%");//这里还可以添加其他的关键词
			$data['keywords'] = $keywords;//将本次的关键词显示到页面中去
		}
		
		//将源语言存入过滤条件中
		if($language_title){
			$language_title= trim($language_title);
			foreach ($language as $key=>$value){
				if(is_numeric(strpos($value['title'],$language_title))){
					//$map['language_id']=array('in',$language_ids);
					$language_ids[]=$key;
				}
//				foreach ($value as $k=>$v){
//					if ($language_title == $v){
//							$map['language_id'] = $key;
//					}
//				}
			}
			if(!empty($language_ids)){
				$map['language_id']=array('in',$language_ids);
			}else{
				$map['to_language_id']=0;
			}
			$data['language_title']=$language_title;
		}
		
		
		//将目标语言存入过滤条件中
		if($to_language_title){
			$to_language_title= trim($to_language_title);
			foreach ($language as $key=>$value){
				if(is_numeric(strpos($value['title'],$to_language_title))){
					//$map['language_id']=array('in',$language_ids);
					$to_language_ids[]=$key;
				}
//				foreach ($value as $k=>$v){
//					if ($to_language_title == $v){
//						$map['to_language_id'] = $key;
//					}
//				}
			}
			if(!empty($to_language_ids)){
				$map['to_language_id']=array('in',$to_language_ids);
			}else{
				$map['to_language_id']=0;
			}
			$data['to_language_title']=$to_language_title;
		}

		//按照条件将信息显示出来
		$result=$this->page(D($this->model),$map,$order='id desc',$field=array(),$this->limit);
		//将信息中的int字段转化成文字信息
		$result=int_to_string($result,array("product_status"=>array("0"=>"下架","1"=>"上架"),"recommend"=>array("0"=>"推荐","1"=>"取消推荐"),'assess'=>array("0"=>'审核',"1"=>'取消审核')));
		$data['result']=$result;
		$this->assign($data);
		$this->display();
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
// 			$posts = I('post.');
// 			$industry_id = json_encode($posts['industry_id']);
// 			echo $industry_id,' ----  ';
// 			print_r($posts);exit;
			$this->update();
		}else{
			$shop_data = get_result('shop');
			$this->assign('shop_data',$shop_data);
// 			dump($shop_data);die;
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
			$map['id']=$id;
			$info = get_info(D($this->model),$map);
			$data['info'] = $info;
// 			dump($info);die;
			$this->assign($data);
			$this->display('operate');
		}
	}
	
	
	
	public function update(){
		if(IS_POST){
			$posts = I('post.');
			/* 定义添加验证规则 */
			$rules = array(
					array('title','require','发布店铺不能为空'),
					array('language_id','require','源语言不能为空'),
					array('type','require','类型不能为空'),
					array('to_language_id','require','目标语言不能为空'),
					array('short_description','require','简介不能为空'),
					array('price','require','请填写价格'),
			);
			$industry_id = json_encode($posts['industry_id']);
			$_POST = array(
					'id'=>$posts['id'],
					'title'=>$posts['title'],
					'language_id'=>$posts['language_id'],
					'to_language_id'=>$posts['to_language_id'],
					'short_description'=>$posts['short_description'],
					'description'=>$posts['description'],
					'price'=>$posts['price'],
					'keywords'=>$posts['keywords'],
					'industry_id'=>$industry_id,
					'type'=>$posts['ability_1'],
					'ability_id'=>json_encode($posts['ability_3']),
					'shop_id'=>$posts['shop_id'],
			);
			
			$result=update_data($this->table, $rules);
			/* 判断执行操作是否成功 */
			if(is_numeric($result)){
				F($this->category_cache,null); /* 操作成功清除缓存 */
				$this->success('操作成功',U('index',array('pid'=>intval(I('post.pid')))));
			}else{
				$this->error($result);
			}

		}else{
			$this->error('违法操作',U('index'));
		}
	}
	/**
	 * 推荐/取消推荐
	 * @author						李东
	 * @date						2015-06-17
	 */
	public function setRec(){
		$this->setStatus('recommend');
	}
	
	public function setAssess(){
		$this->setStatus('assess');
	}
	
	
}
