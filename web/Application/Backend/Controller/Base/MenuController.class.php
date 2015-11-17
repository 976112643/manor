<?php
namespace Backend\Controller\Base;
use Backend\Controller\Base\AdminController;
class MenuController extends AdminController {
	protected $table='menu';
	protected $session_cache_name='menu_result';
	/*
	 * 菜单列表页
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	public function index(){
		$map['pid']=0;
		if(I('pid')){
			$map['pid']=intval(I('pid'));
		}
		$keywords=I('keywords');
		if($keywords){
			$map['title']=array('like', "%$keywords%");
		}
		$map['status']=array('gt',-1);
		$result=$this->page($this->table, $map,'sort desc,id asc');
		$result=int_to_string($result);
		$data['result']=$result;
// 		dump($data);die;
		$this->assign($data);
		$this->display();
	}
	/*
	 * 添加菜单
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	public function add(){
		if(IS_POST){
			$this->update();
		}else{
			$this->display('operate');
		}
	}
	/*
	 * 修改菜单
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	public function edit(){
		if(IS_POST){
			$this->update();
		}else{
			$id=intval(I('id'));
			$map['id']=$id;
			$data['info']=M($this->table)->where($map)->find();
			$result=M($this->table)->where(array('status'=>1,'pid'=>$id))->field('title')->select();
			$sub_menu=array();
			foreach ($result as $row){
				$sub_menu[]=$row['title'];
			}
			$data['sub_menu']=$sub_menu;
			$this->assign($data);
			$this->display('operate');
		}
	}
	/*
	 * 添加/修改操作
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	public function update(){
		if(IS_POST){
			$id=intval(I('post.id'));
			$url=I('post.url');
			$rules = array (
			    array('title','require','菜单名称必须！'), //默认情况下用正则进行验证
			);
			/*
			 * 计算level等级
			 * */
			$pid=intval(I('post.pid'));
			$level=0;
			if($pid>0){
				$parent_info=get_info($this->table,array('id'=>$pid),array('level'));
				if($parent_info){
					$level=$parent_info['level']+1;
				}
			}
			$_POST['level']=$level;
			$result=update_data($this->table, $rules);
			if(is_numeric($result)){
				delete_data($this->table,array('pid'=>$result,'title'=>array('in',array('添加','修改','启用','禁用','删除'))));
				$child_menu=I('post.child_menu');
				foreach ($child_menu as $row){
					$row_arr=explode("|", $row);
					$_POST=array(
						'pid'=>$result,
						'title'=>$row_arr[0],
						'url'=>str_replace('index', $row_arr[1], $url),
						'status'=>1,
						'level'=>2,
					);
					update_data($this->table);
				}
				session('menu_result',null);
				$this->success('操作成功',U('index',array('pid'=>intval(I('post.pid')))));
			}else{
				$this->error($result);
			}
		}else{
			$this->success('违法操作',U('index'));
		}
	}
	
	/*
	 * 删除数据库中的数据，如果是删除数据到回收站，不需要此函数
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	function del(){
		$ids=I('ids');
		if(is_array($ids)){
			$map['id']=array('in',$ids);
			$result=delete_data($this->table,$map);
			$ids_str=implode(',', $ids);
			if($result){
				session('menu_result',null);
				action_log($this->table,$ids);
				$this->success('操作成功',U('index'));
			}else{
				$this->error($result);
			}
		}else{
			$ids=intval($ids);
			$map['id']=$ids;
			$result=delete_data($this->table,$map);
			if($result){
				session('menu_result',null);
				action_log($this->table,$ids);
				$this->success('操作成功',U('index'));
			}else{
				$this->error($result);
			}
		}
	}
	
}