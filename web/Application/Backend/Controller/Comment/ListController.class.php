<?php
namespace Backend\Controller\Comment;
use Backend\Controller\Base\AdminController;
class ListController extends AdminController {
	protected $table='comment';
	protected $action_filed='id,product_id';
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
		$map['status']=array('gt',0);
		/* $keywords=I('keywords');
		if($keywords){
			$map['username|email|telephone|realname']=array('like', "%$keywords%");//这里的username是真实姓名还是平台生成的呢？？这里还可以添加其他的关键词
			$data['keywords'] = $keywords;//将本次的关键词显示到页面中去
		} */
		//按照时间筛选
		$add_time_begin = I('add_time_begin');
		$add_time_end = I('add_time_end');
		if(!empty($add_time_begin) && !empty($add_time_end)){
			$map['add_time'] = array(array('GT',$add_time_begin),array('LT',$add_time_end));
			$data['add_time_begin'] = $add_time_begin;
			$data['add_time_end'] = $add_time_end;
		}
		//按照好评差评筛选
		$type = I('type');
		if($type!=0){
			$map['type'] = $type;
			$data['type'] = $type;
		}
		//将评价按照好评、中评、差评区分
		$comment_array = array(1=>'好评',2=>'中评',3=>'差评');
		
		$data['comment_array'] = $comment_array;
// 		dump(D('Common/CommentView'));die;
		//按照条件将信息显示出来
		$result=$this->page(D('Common/CommentView'), $map,'add_time desc');
		// echo session('sql');exit;
		//print_r(session('sql'));exit;
		//将信息中的int字段转化成文字信息
		$result=int_to_string($result,array('comment_type'=>array(1=>'好评',2=>'中评',3=>'差评'),'product_type'=>array('36'=>文档翻译,'56'=>音频翻译,'37'=>口译服务)));
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
	/* public function add(){
		if(IS_POST){
			//生成用户登录的code数字的形式存入到数据库中
			$username = uniqid();//以微秒计数生成唯一的id
			$_POST['username'] = $username;
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
	} */
	/*
	 * 修改菜单
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	/* public function edit(){
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
	} */
	/*
	 * 添加/修改操作
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	/* public function update(){
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
	} */
	/*
	public function del(){
		$ids = I('ids');
		if(!$ids){
			$this->error('请选择数据！！');
		}
		if(is_array($ids)){
			//查询图片信息
			$pic_result = get_result('resources',array('id'=>array('in'=>$ids)));
			foreach($pic_result as $k=>$v){
				unlink($v['img_path']);
			}
			//删除数据
			$map['id'] = array('in',$ids);
			$_POST = array(
					'id'=>$ids,
					status => -1
			);
			$result = update_data($this->table,$map);
		}else{
			//查询这条信息
			$pic_result  = get_result('resources',array('id'=>$ids));
			foreach($pic_result as $k=>$v){
				unlink($v['img_path']);//删除图片路径
			}	
			$map['id'] = $ids;
			$_POST = array(
					'id'=>$ids,
					status => -1
			);
			$result = update_data($this->table,$map);
		}
		if($result){
			$this->success('删除成功！！',U('index',I('get.')));
		}else{
			$this->error('删除失败！！',U('index',I('get.')));
		}
	}
	*/
	
}
