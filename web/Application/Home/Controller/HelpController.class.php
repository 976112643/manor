<?php
namespace Home\Controller;
use Home\Controller\HomeController;
class HelpController extends HomeController {
	private $model = 'HelpCategoryView';
	private $table = 'article';
	/**
	*帮助中心主页
	*	显示关于N邦的帮助文章
	*流程分析
	*	1、将页面中的菜单查询出来并显示
	*	2、要求是用户在后台添加的时候前台能够自动显示出来菜单并显示到页面中
	**/
     public function index(){
    	$article_id = I('id');
    	/* 获取分类列表 */
    	$help_category_cath = array_id_key(get_help_category_cache());
    	$category_ids = array();
    	foreach($help_category_cath as $row){
    		$category_ids[]=$row['id'];
    	}
    	
    	/* 获取分类下的所有文章 */
    	$map=null;
    	$map['status'] = 1;
    	$map['category_id'] = array('in',$category_ids);
    	$articles = get_result($this->table,$map);
    	
    	
    	/* 获去当前显示的文章 */
    	if ($article_id){
    		$map['id'] = $article_id;
    		$map['status'] = 1;
    		$cur_article = get_info($this->table,$map);
    	}else{
    		$cur_article = $articles[0];
    	}
    	
    	$data['help_category_cath']=$help_category_cath;
    	$data['articles']=$articles;
    	$data['cur_article']=$cur_article;

    	$this->assign($data);
    	$this->display();
    }
    
    
}