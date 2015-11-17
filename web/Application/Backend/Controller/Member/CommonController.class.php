<?php
namespace Backend\Controller\Member;
use Backend\Controller\Base\AdminController;
class CommonController extends AdminController {
	protected $table='member';
	protected $action_filed='id,username as title';
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
		$map['is_admin']=0;
		$map['status']=array('gt',-1);
		$keywords=I('keywords');
		$map['role_id'] = 7;//表示普通用户
		$map['type']=1;
		if($keywords){
			$map['username|email|telephone|realname']=array('like', "%$keywords%");//这里的username是真实姓名还是平台生成的呢？？这里还可以添加其他的关键词
			$data['keywords'] = $keywords;//将本次的关键词显示到页面中去
		}
		$add_time_begin = I('add_time_begin');
		$add_time_end = I('add_time_end');
		if(!empty($add_time_begin) && !empty($add_time_end)){
			$add_time_begink=$add_time_begin.' 00:00:00';
			$add_time_endk=$add_time_end.' 23:59:59';
			$map['add_time'] = array(array('GT',$add_time_begink),array('LT',$add_time_endk));
			$data['add_time_begin'] = $add_time_begin;
			$data['add_time_end'] = $add_time_end;
		}
		//按照条件将信息显示出来
		$result=$this->page(D("Common/MemberView"), $map,'add_time desc');
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
			//生成用户登录的code数字的形式存入到数据库中
			$username = uniqid();//以微秒计数生成唯一的id
			$_POST['username'] = $username;
			$_POST['role_id'] = 7;
			$this->update();
		}else{
			//将用户的等级信息显示出来
			$map['status'] = array('GT',0);
			$map['type'] = 2;
			$group_result = get_result('level',$map);
			$data['group_result'] = $group_result;
			
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
			//将用户的等级信息显示出来
			$_map['status'] = array('GT',0);
			$_map['type'] = 2;
			$group_result = get_result('level',$_map);
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
				array('realname','require','必须填写真实姓名!'),
			);
			$_POST['role_id']=7;
			$_POST['type']=1;
			$_POST['is_admin']=0;
			
			if(empty($_POST['telephone'])){
				$this->error('请输入手机号');
			}
			
			if(!preg_match('/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/', $_POST['telephone'])){
				$this->error('手机号码格式有误');
			}
			if(!empty($_POST['email'])){
				if(!preg_match('/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i', $_POST['email'])){
					$this->error('邮箱格式有误');
				}
			}
			
			
			
			if($id==0){//如果是添加
				$_map['telephone']=$_POST['telephone'];
				$info=get_info('member',$_map);
				if($info){
					$this->error('此手机号已注册');
				}
				
				$rules[] = array('password','require','密码必须填写！');
				if(empty(I('post.password'))){
					$this->error('请输入密码');
				}
				$_POST['password']=md5(md5(I('post.password')));
				$_POST['deal_password']=md5(md5(I('post.deal_password')));
			}else{//如果是修改
				$_map['id']=$id;
				$info=get_info('member',$_map);
				if($info){
					if($info['telephone']!=$_POST['telephone']){
						$map_k['telephone']=$_POST['telephone'];
						$_info=get_info('member',$map_k);
						if($_info){
							$this->error('此手机号已注册');
						}
					}
				}else{
					$this->error('修改失败');
				}
				
				
				if(I('post.password')){
					$_POST['password']=md5(md5(I('post.password')));
				}else{
					unset($_POST['password']);
				}
				
				if(I('post.deal_password')){
					$_POST['deal_password']=md5(md5(I('post.deal_password')));
				}else{
					unset($_POST['deal_password']);
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
