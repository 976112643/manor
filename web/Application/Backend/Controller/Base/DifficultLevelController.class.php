<?php
namespace Backend\Controller\Base;
use Backend\Controller\Base\AdminController;
class DifficultLevelController extends AdminController {
	protected $table='level';
	protected $cache_name = 'grade_cache'; /*缓存名称*/
	protected $action_filed='id,title';
	/*
	*用户列表
	*	需求分析
	*		需要将所有用户的信息全部显示到这张表中
	*	流程分析
	*		1、接收用户筛选信息,具体的信息看
	*		2、组织查询的条件
	*		3、按照条件将信息显示出来
	*		4、将信息中的int字段转化成文字信息
	*/
	public function index(){
		//接收用户筛选信息,具体的信息看，组织查询的条件
		$map['type'] = 7;//表示信用等级
		$map['status']=array('gt',-1);//状态是没有删除的
		//按照条件将信息显示出来
		$result=$this->page($this->table,$map,'add_time desc');
		//将信息中的int字段转化成文字信息
		$result=int_to_string($result);
		
		$data['result']=$result;
		$this->assign($data);
		$this->display();
	}
	/*
	 *用户列表页添加功能
	 *	需求分析
	 *		尽量将用户的信息显示出来，让后台能够控制的东西更多
	 *	流程分析	
	 *		1、将用户的等级信息显示出来
	 *		2、生成用户登录的数字登录码
	 *		3、添加用户
	 * */
	public function add(){
		if(IS_POST){
			$_POST['type'] = 7;
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
			$rules = array ( 
			    array('title','require','标题不能为空！'),
				array('description','require','描述必须填写！'),
				
			);/*刘巧*/
			$_POST['is_admin']=0;
			//if($id==0){//如果是添加
			
			//}else{//如果是修改
			//	if(I('post.password')){
				//	$_POST['password']=md5(md5(I('post.password')));
				//}else{
				//	unset($_POST['password']);
				//}
			//}
			$result=update_data($this->table, $rules);
			if(is_numeric($result)){
				F($this->cache_data,null);
				$this->success('操作成功',U('index'));
			}else{
				$this->error($result);
			}
		}else{
			$this->success('违法操作',U('index'));
		}
	}
}
