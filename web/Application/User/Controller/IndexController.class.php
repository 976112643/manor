<?php
namespace User\Controller;
use User\Controller\BaseController;
class IndexController extends BaseController {
	protected $comment = 'comment';						/*评论信息表*/
	protected $products = 'products';					/*产品表*/
	protected $comment_product = 'CommentProductView';
	protected $limit = 10;
    public function index(){
		header("location:".U("User/Myorder/index"));	
		
    }
    /**
     *我的评价
     *	查询显示用户的评价
     *流程分析
     *	就是比店铺评价多了一个member_id筛选
     **/
    public function member_evaluate(){
    	$post = I('post.');
    	$sort = 'comment.add_time desc';
    	$member_id = session('home_member_id');
    	if(!empty($post)){
    		if($post['radio']!=0){//0表示全部
    			$maps['type'] = $post['radio'];
    		}
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
    	//获取筛选数据
    	//$maps['shop_id'] = $shop_id;
    	//$maps['status'] = 2;
    	$maps['member_id'] = $member_id;
    	$maps['pid'] = 0;
    	//$maps = array_merge($maps,$map);
    	//计算店铺的星级？？？？？？
    
    	//统计店铺的好评数,中评数，差评数
    	$shop_good_comments = get_result($this->comment,array('member_id'=>$member_id,'type'=>1,'pid'=>0));
    	$good_comments_num = count($shop_good_comments);
    
    	$shop_mid_comments = get_result($this->comment,array('member_id'=>$member_id,'type'=>2,'pid'=>0));
    	$mid_comments_num = count($shop_mid_comments);
    
    	$shop_bad_comments = get_result($this->comment,array('member_id'=>$member_id,'type'=>3,'pid'=>0));
    	$bad_comments_num = count($shop_bad_comments);
    	
    	$total_comments_num = $good_comments_num+$mid_comments_num+$bad_comments_num;
    
    	$shop_comments = $this->page(D($this->comment_product),$maps,$sort,'',$this->limit);//关联查询商品和评论表
    
    	$shop_comments = int_to_string($shop_comments,array('type'=>array(1=>'好评',2=>'中评',3=>'差评')));
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
    		$shop_comments_s = get_result($this->comment,array('pid'=>array('in',trim($ids,','))));
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
    	$this->assign($comments);
    	if(!empty($post)){
    		$this->display('order_evaluate_ajax');
    	}else{
    		$this->display('order_evaluate');
    	}
    }
}