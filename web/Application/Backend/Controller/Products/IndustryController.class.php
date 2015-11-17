<?php
namespace Backend\Controller\Products;
use Backend\Controller\Base\AdminController;
class IndustryController extends AdminController {
	protected $table='category';
	protected $type='1';
	protected $limit=15;							/* 每页显示条数 */
	protected $cache_name='industry_cache';		/* 缓存名 */
	
	/**
	 * 分类列表
	 * 查询出所有商品的分类信息
	 * 包括语种分类，行业分类，技能分类
	 * 三种分类单独显示列表时，以type区分，type值按顺序分别为0，1，2
	 * @author						李东
	 * @date						2015-06-12
	 */
	public function index(){
		$map['type']=$this->type;
		$pid = I('pid')?I('pid'):0;
		$map['pid']=$pid;
		//接收用户筛选信息,具体的信息看，组织查询的条件
		$map['status']=array('GT',-1);
		$keywords=I('keywords');
		if($keywords){
			$keywords= trim($keywords);
			$map['title|description']=array('like', "%$keywords%");//这里还可以添加其他的关键词
			$data['keywords'] = $keywords;//将本次的关键词显示到页面中去
		}
		//按照条件将信息显示出来
		$result=$this->page($this->table,$map,$order='',$field=array(),$this->limit);
		//将信息中的int字段转化成文字信息
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
			$rules = array(
					array('title','require','名称不能为空'),
			);
			$path = '-0-';
			if($posts['pid']){
				/*如果上级id存在，则取出上级path,组合成自身path*/
				$map['status'] =array('gt','-1'); 
				$map['id']=$posts['pid'];
				$pid_info = get_info($this->table,$map);
				$level=$pid_info['level']+1;
				$path =$pid_info['path'].$posts['pid'].'-';
			}
			$_POST = array(
					'id'=>$posts['id'],
					'pid'=>$posts['pid'],
					'title'=>$posts['title'],
					'sort'=>$posts['sort'],
					'type'=>$this->type,
					'description'=>$posts['description'],
					'path'=>$path,
					'level'=>$level,
			);
			/* 限制可添加分类的级数 */
			if($level>3){
				$this->error('最多只能添加三级分类！');exit;
			}else{
				$result=update_data($this->table, $rules);
			}
			/* 判断执行操作是否成功 */
			if(is_numeric($result)){
				F($this->cache_name,null); /* 操作成功清除缓存 */
				$this->success('操作成功',U('index',array('pid'=>intval(I('post.pid')))));
			}else{
				$this->error($result);
			}
		}else{
			$this->error('违法操作',U('index'));
		}
	}
	
	
	
}
