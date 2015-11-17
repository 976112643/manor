<?php
namespace Backend\Controller\Base;
use Backend\Controller\Base\AdminController;
class GroupController extends AdminController {
	protected $table='role';

	/*
	 * 菜单列表页
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	public function index(){
		$map['is_admin']=1;
		$map['status']=array('gt',-1);
		$keywords=I('keywords');
		if($keywords){
			$map['title']=array('like', "%$keywords%");
		}
		$result=$this->page($this->table, $map,'id asc');
		$result=int_to_string($result);
		$data['result']=$result;
		
		$this->assign($data);
		$this->display();
	}
	/*
	 * 添加操作
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
	 * 修改操作
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	public function edit(){
		if(IS_POST){
			$this->update();
		}else{
			$id=intval(I('id'));
			$map['id']=$id;
			$data['info']=get_info($this->table,$map);
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
			$rules = array (
			    array('title','require','用户组名称必须！'), //默认情况下用正则进行验证
			);
			session('menu_result',null);
			session("menu_arr",null);
			$result=update_data($this->table,$rules);
			if(is_numeric($result)){
				$this->success('操作成功',U('index'));
			}else{
				$this->error($result);
			}
		}else{
			$this->success('违法操作',U('index'));
		}
	}
	/*
	 * 访问授权
	 * @time 2014-12-30
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	function access(){
		$id=intval(I('id'));
		if(IS_POST){
			$access_id_arr=I('post.access_id');
			$rules=implode(',', $access_id_arr);
			$_POST=array(
				'id'=>$id,
				'rules'=>$rules,
			);
			$result=update_data($this->table, $rules);
			if(is_numeric($result)){
				session('menu_result',null);
				session("menu_arr",null);
				$this->success('操作成功',U('index'));
			}else{
				$this->error($result);
			}
		}else{
			$Menu=M('menu');
			$menus=$Menu->where(array('stauts'=>1))->order('sort desc')->select();
			$data['menus']=list_to_tree($menus);
			$data['info']=get_info($this->table,array('id'=>$id));
			$this->assign($data);
			$this->display();
		}
	}
	
}