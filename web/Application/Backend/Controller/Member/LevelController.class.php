<?php
namespace Backend\Controller\Member;
use Backend\Controller\Base\AdminController;
class LevelController extends AdminController {
	protected $table='level';
	protected $action_filed='id,title';
	/*
	*用户列表
	*	需求分析
	*		将等级信息显示出来
	*	流程分析
	×		1、接收用户的关键词等
	*		2、组织查询条件
	*		3、分页查询数据
	*/
	public function index(){
		//接收用户筛选信息,具体的信息看，组织查询的条件
		$map['status']=array('gt',-1);//表示没有删除的等级
		//$map['type'] = 2;//表示用户等级
		$keywords=I('keywords');
		//$map['role_id'] = 8;//表示普通用户
		if($keywords){
			$map['title|description']=array('like', "%$keywords%");//这里的username是真实姓名还是平台生成的呢？？这里还可以添加其他的关键词
			$data['keywords'] = $keywords;//将本次的关键词显示到页面中去
		}
		
		//按照条件将信息显示出来
		$result=$this->page('level', $map,'add_time desc');
		//将信息中的int字段转化成文字信息
		$result=int_to_string($result,array('type'=>array(1=>'商品翻译等级',2=>'用户等级')));
		
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
			$_POST['type'] = 2;
			$this->update();
		}else{
			//将用户的等级信息显示出来
			$level_type = array(1=>'商品翻译等级',2=>'用户等级');
			
			$data['level_type']= $level_type;
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
			//将用户的等级信息显示出来
			$level_type = array(1=>'商品翻译等级',2=>'用户等级');
			
			$data['level_type']= $level_type;
			
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
			//print_r($_POST);
			//exit;
			$id=intval(I('post.id'));
			$max_price = floatval(I('post.max_price'));
			$min_price = floatval(I('post.min_price'));
			if($max_price<=$min_price){
				$this->error("最高价格不能小于或等于最低价格");
			}
			$_POST["max_price"]=$max_price;
			$_POST["min_price"]=$min_price;
			
			$rules = array ( 
			    array('title','require','标题必须填写！'),
				array('description','require','描述必须填写！'),
				array('min_price','require','最低价格必须填写！'),
				array('max_price','require','最高价格必须填写！'),
			);
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
