<?php
namespace Home\Controller;
use Home\Controller\HomeController;
class NewsController extends HomeController {
	private	$table	= 'article';
	
	/**
	*新闻列表页面
	*	需求分析
	*		分页显示最先发布的新闻,需要显示标题，新闻图片,部分内容,作者,时间,还要显示左边的推荐公告等
	*	流程分析
	*		1、将新闻从article表中分页取出
	*		2、显示到页面中
	*		3、在页面中调整，显示缓存数据
	*	
	**/
    public function index(){
		//将type为news的数据查询出来
// 		$map['status'] = array('GT',-1);//将正常的新闻查询出来
    	$map['status'] = array('GT',0);//将正常的新闻查询出来
		$map['type'] = 'news';//将新闻查询出来，而不是别的
		
		//分页查询news
		$news_result = $this->page(D('NewsMemberView'),$map);	
		
		//将推荐的公告查询出来
		$map_notice['status'] =	array('GT',0);
		$map_notice['type']	= 'notice';
		$map_notice['recommend'] =	1;//被推荐的公告
		//查询出来
		$notice_result	=  get_result($this->table,$map_notice);
		
		//app数据的推送？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？？
		
		
		$data['news']	=	$news_result;
		$data['notice'] = $notice_result;
		$this->assign($data);
        $this->display();
    }
	
	/**
	*新闻详情页面
	*	需求分析
	*		将对应的新闻显示到页面中
	*	流程分析
	*		1、接收用户传过来的id
	*		2、查询相关数据
	×		3、将页面中的其他数据显示
	**/
	
	public function	detail(){
		//接收新闻信息
		$news_id = I('id');
		$map['id'] = $news_id;
		$news_info = get_info($this->table,$map);
		$data['news_detail'] = $news_info;
		
		//将推荐的公告查询出来
		$map_notice['status'] =	array('GT',0);
		$map_notice['type']	= 'notice';
		$map_notice['recommend'] =	1;//被推荐的公告
		//查询出来
		$notice_result	=  get_result($this->table,$map_notice);
		//增加阅读次数
		$_POST = array(
				'id'=>$news_info['id'],
				'view'=>$news_info['view']+1,
		);
		update_data($this->table);
		
		$data['notice'] = $notice_result;
		$this->assign($data);
		$this->display();
	}
	
	/**
	*系统公告
	*	也就是系统中的帮助中心
	*@author 刘浩 <372980503>
	*@time 2015-7-8
	**/
	public function  notice(){
		//接收新闻信息
		$notice_id = I('id');
		$map['id'] = $news_id;
		$notice_info = get_info($this->table,$map);
		$data['news_detail'] = $notice_info;
		
		//将推荐的公告查询出来
		$map_notice['status'] =	array('GT',0);
		$map_notice['type']	= 'notice';
		$map_notice['recommend'] =	1;//被推荐的公告
		//查询出来
		$notice_result	=  get_result($this->table,$map_notice);
		//增加阅读次数
		
		$data['notice'] = $notice_result;
		$this->assign($data);
		$this->display();
	}
	
	
	
	
	
	
}