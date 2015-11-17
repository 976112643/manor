<?php
namespace Home\Controller;
use Home\Controller\HomeController;
class CeshiController extends HomeController {
	private	$table	= 'article';
	
	/**
	*帮助中心的主页
	*	需要显示的是关于N邦的文章
	*流程分析
	*	1、建立
	**/
    public function index(){
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