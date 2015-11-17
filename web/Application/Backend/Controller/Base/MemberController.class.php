<?php
namespace Backend\Controller\Base;
use Backend\Controller\Base\AdminController;
class MemberController extends AdminController {
	protected $table='member';
	protected $action_filed='id,username as title';
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
			$map['username|email']=array('like', "%$keywords%");
		}
		$result=$this->page(D("Common/MemberView"), $map,'id asc');
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
			$data['group_result']=M('role')->where(array('status'=>1))->order('id asc')->select();
			$this->assign($data);
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
			$data['group_result']=M('role')->where(array('status'=>1))->order('id asc')->select();
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
			$role_id=intval(I('post.role_id'));//获取添加后台管理员用户组
// 			dump(I('post.password'));die;
			$rules = array ( 
			    array('username','require','用户名必须填写！'),
// 				array('password','require','请填写密码！'),
				array('email','email','请填写正确的邮箱格式！'),
			);
			$_POST['is_admin']=1;
			if($id==0){//如果是添加
				$rules[] = array('password','require','密码必须填写！');
				$_POST['password']=md5(md5(I('post.password')));
				$_POST['role_id'] = $role_id;
			}else{//如果是修改
				if(I('post.password')){
					$_POST['password']=md5(md5(I('post.password')));
				}else{
					unset($_POST['password']);
				}
			}
			$result=update_data($this->table, $rules);
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