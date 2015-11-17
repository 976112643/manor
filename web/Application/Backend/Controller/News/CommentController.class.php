<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Backend\Controller\News;
use Backend\Controller\Base\AdminController;
/**
 * 资讯下载控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class CommentController extends AdminController {
	protected $table='comment';
	protected $type='news';
	public function index(){
		$title       =   I('title');
		$map['status']=array('gt',-1);
		$search_type=I('search_type');
		if($title){
			if($search_type==2){
				$map['content']=   array('like', '%'.$title.'%');
			}else{
				$map['id']    =   intval($title);
			}
			$data['title']=$title;
			$data['search_type']=$search_type;
		}
		$map['comment.type']=$this->type;
		$list   = $this->page(D('CommentView'), $map,'id desc');
		$list=int_to_string($list);
        $this->assign('_list', $list);
		$this->assign('type',$type);
		$this->assign('meta_title',"评论列表");
		$this->display();	
	}

	/*
	 * 删除评论
	 * 1、根据点击删除传递过来的ID获取相关数据
	 * 2、获取评论内容中的图片并删除
	 * 3、统计评论删除后新闻的评论数量
	 * 4、删除数据表中的数据
	 * @time 2015-05-14
	 * @author	康利民  <3027788306@qq.com>
	 * */
	public function del(){
		//1、根据点击删除传递过来的ID获取相关数据
		$ids = I('ids');
		$map['id']=array("in",$ids);
		$result=get_result($this->table,$map);
		//2、获取评论内容中的图片并删除
		foreach ($result as $key => $value) {
			//删除内容中的图片
			delStrImgs($value['content']);

			//3、统计评论删除后新闻的评论数量
			$_POST['id']=$value['product_id'];
			$_POST['comment']=count_data($this->table,array("product_id"=>$value['product_id']));
			update_data("article");
		}
		//4、删除数据表中的数据
		delete_data($this->table,$map);
		$this->success("删除成功");
	}
}
