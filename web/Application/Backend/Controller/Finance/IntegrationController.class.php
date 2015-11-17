<?php
namespace Backend\Controller\Finance;
use Backend\Controller\Base\AdminController;
class IntegrationController extends AdminController {
// 	protected $table='integration';
	protected $table='member';
	protected $action_filed='id,member_id';
	/*
	*需求分析
	*		需要将用户的资金流动信息显示出来，还要显示一写备注信息，具体看页面
	*流程分析
	*		1、接收筛选信息
	*		2、组织筛选条件
	*		3、分页查询
	*		4、转换字符串
	*/
	public function index(){
		//接收筛选的条件信息
		$keywords = I('keywords');
		if($keywords){
			$keywords = trim($keywords);
			$map['member.telephone|member.email'] = array('like',"%$keywords%");
			$data['keywords'] = $keywords;
		}
		//组织筛选条件
		$map['status'] = array("GT",-1);//表示没有被后台删除
		
		//分页查询
// 		$result = $this->page(D('IntegrationView'),$map,'add_time');
		$result = $this->page($this->table);
		//转换字符串
// 		$result = int_to_string($result,array('frozen'=>array(1=>'可提现账户',2=>'余额账户',3=>'冻结账户')));
// 		dump($result);die;
		$data['result'] = $result;
// 		dump($data);die;
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
			$_POST['type'] = 'help';
			$_POST['member_id'] = session('member_id');
			$_POST['update_member_id'] = session('member_id');
			$_POST['update_time'] = date('Y-m-d H:i:s',time());
			$this->update();
		}else{
			//将帮助分类的类别显示出来
			$map['status'] = array("GT",0);//将正常的显示出来
			$map['type'] = 3;
			
			$help_category = get_result('category',$map);
			
			//将帮助信息数据组织成树形
			$help_category = list_to_tree($help_category);
			$data['help_category'] = $help_category;
			
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
			
			//将帮助分类的类别显示出来
			$map['status'] = array("GT",0);//将正常的显示出来
			$map['type'] = 3;
			
			$help_category = get_result('category',$map);
			
			//将帮助信息数据组织成树形
			$help_category = list_to_tree($help_category);
			$data['help_category'] = $help_category;
			
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
			    array('username','require','用户名必须填写！'),
			);

			$_POST['is_admin']=0;
			if($id==0){//如果是添加
				$rules[] = array('password','require','密码必须填写！');
				$_POST['password']=md5(md5(I('post.password')));
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
}
