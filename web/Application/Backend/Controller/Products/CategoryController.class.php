<?php
namespace Backend\Controller\Products;
use Backend\Controller\Base\AdminController;
class CategoryController extends AdminController {
	protected $table='category';
	protected $type='news';
	protected $action_filed='id,title,path';		
	protected $category_cache='category_cache';		/* 缓存名 */
	
	/**
	 * 分类列表
	 * 查询出所有商品的分类信息
	 * 包括语种分类，行业分类，技能分类
	 * 三种分类单独显示列表时，以type区分，type值按顺序分别为0，1，2
	 * @author						李东
	 * @date						2015-06-12
	 */
	public function index(){
		$map['type']='0';
		$pid = I('pid')?I('pid'):0;
		$map['pid']=$pid;
		$map['status'] = array('gt','-1');
		$result = get_result($this->table,$map);
		$data['result']=$result;
		$this->assign($data);
		$this->display('index');
	}
	
	public function industry(){
		$map['type']='1';
		$pid = I('pid')?I('pid'):0;
		$map['pid']=$pid;
		$map['status'] = array('gt','-1');
		$result = get_result($this->table,$map);
		$data['result']=$result;
		$this->assign($data);
		$this->display('index');
	}
	
	public function ability(){
		$map['type']='2';
		$pid = I('pid')?I('pid'):0;
		$map['pid']=$pid;
		$map['status'] = array('gt','-1');
		$result = get_result($this->table,$map);
		$data['result']=$result;
		$this->assign($data);
		$this->display('index');
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
			$this->update();
		}else{
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
			$info = get_info($this->table,$map);
			$data['info'] = $info;
			$this->assign($data);
			$this->display('operate');
		}
	}
	
	
	
	public function update(){
		if(IS_POST){
			$posts = I('post.');
			/* 定义添加验证规则 */
			$rule = array(
					array('title','require','分类名称不能为空'),
			);
			$path = '-0-';
			if($posts['pid']){
				/*如果上级id存在，则取出上级path,组合成自身path*/
				$map['status'] =array('gt','-1'); 
				$map['id']=$posts['pid'];
				$pid_info = get_info($this->table,$map);
				$level=$parent_info['level']+1;
				$path =$pid_info['path'].$posts['pid'].'-';
			}
			$_POST = array(
					'id'=>$posts['id'],
					'pid'=>$posts['pid'],
					'title'=>$posts['title'],
					'sort'=>$posts['sort'],
					'type'=>$posts['type'],
					'description'=>$posts['description'],
					'path'=>$path,
					'level'=>$level,
			);
			if($level>3){
				$this->error('最多只能添加三级分类！');exit;
			}else{
				$result=update_data($this->table, $rules);
			}
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
	
	
	
}
