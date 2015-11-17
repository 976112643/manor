<?php
namespace Backend\Controller\Finance;
use Backend\Controller\Base\AdminController;
class RecordController extends AdminController {
	protected $table='funds_counts';
	protected $action_filed='id,title';
	protected $model = 'PlatrecordView';
// 	protected $table = 'funds_counts';
	protected $detail_table = 'funds_detail';
	
	
// 	public function index(){
// 		$map['status'] =array('gt','-1');
// 		$result = $this->page($this->table,$map,'add_time');
// 		print_r($result);
// 	}

	
// 	/*
// 	*需求分析
// 	*		需要将用户的资金流动信息显示出来，还要显示一写备注信息，具体看页面
// 	*流程分析
// 	*		1、接收筛选信息
// 	*		2、组织筛选条件
// 	*		3、分页查询
// 	*		4、转换字符串
// 	*/

/*@liuqiao  改。将原来的设计进行修改*/
	public function index(){
		//接收筛选的条件信息
		$keywords = I('keywords');
		if($keywords){
			$keywords = trim($keywords);
			$map['today_date'] = array('like',"%$keywords%");
			$data['keywords'] = $keywords;
		}
		//组织筛选条件
		$map['status'] = array("eq",1);
		$result = $this->page($this->table,$map,'add_time desc');
		foreach($result as $k=>$v){
			$num1=substr($v['today_date'],0,4);
			$num2=substr($v['today_date'],4,5);
			$num2=substr($num2,0,2);
			$num3=substr($v['today_date'],6,7);
			$result[$k]['today_date']=$num1.'-'.$num2.'-'.$num3;
			
		}
		$data['result'] = $result;
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
