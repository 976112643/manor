<?php
namespace User\Controller;
use User\Controller\BaseController;
class MessageController extends BaseController {
	protected $table='message';
	protected $model='MessageView';
	protected $limit=10;
	
	
	/**
	 * 查询用户接收到的所有消息
	 * @author					李东
	 * @date					2015-06-26
	 */
    public function index(){
    	$map['to_status'] = array('gt','-1');
    	$member_id = session('home_member_id');
    	$use = 0; /*用户判断有无搜索条件,如果为0则没有，如果大于0，则有搜索条件*/
//     	if(I('title')){
//     		$map['title']=array('like','%'.I('title').'%');
//     		$use++;
//     	}
//     	if(I('to_username')){
// 			/* 查询接收用户ID */
// 			$member_id_result=get_result('member',array('username|telephone|email'=>I('to_username')),'id');
// 			$member_ids = array();
// 			foreach($member_id_result as $val){
// 				$member_ids['id'][]=$val['id'];
// 			}
// 			if(!empty($member_ids)){
// 				$map['to_member_id'] = array('in',$member_ids['id']);
// 				$use++;
// 			}
// 		}
// 		if(I('from_username')){
// 			/* 查询发送用户ID */
// 			$member_id_result=get_info('member',array('username|telephone|email'=>I('from_username')),'id');
// 			$member_ids = array();
// 			foreach($member_id_result as $val){
// 				$member_ids['id'][]=$val['id'];
// 			}
// 			if(!empty($member_ids)){
// 				$map['from_member_id'] = $member_info['id'];
// 				$use++;
// 			}
// 		}
    	
    	/*默认搜索条件 登录用户ID为接收用户的信息*/
    	$map['to_member_id'] = $member_id;//发件人
    	$message_list = $this->page(D($this->model),$map,$order=' status asc,id desc ',$field=array(),$this->limit);
    	foreach($message_list as $k=>$v){
    		if($v['from_member_id'] == $member_id){
    			$message_list[$k]['message_status'] =3;
    		}
    		if($v['from_member_id']==0){
    			$message_list[$k]['from_username']='系统';
    		}
    	}
    	$message_list=int_to_string($message_list,array("message_status"=>array("0"=>"未读","1"=>"已读","3"=>"已发送")));
    	$data['message_list']=$message_list; 
//     	dump($data);die;
    	$this->assign($data);
        $this->display('list');
    }
    
    
    /**
     * 查询用户已发消息
     * @author					李东
	 * @date					2015-06-26
     */
    public function has_send(){
    	$map['status'] = array('gt','-1');
    	$member_id = session('home_member_id');
//     	$use = 0; /*用户判断有无搜索条件,如果为0则没有，如果大于0，则有搜索条件*/
//     	if(I('title')){
//     		$map['title']=array('like','%'.I('title').'%');
//     		$use++;
//     	}
//     	if(I('to_username')){
// 			/* 查询接收用户ID */
// 			$member_id_result=get_result('member',array('username|telephone|email'=>I('to_username')),'id');
// 			$member_ids = array();
// 			foreach($member_id_result as $val){
// 				$member_ids['id'][]=$val['id'];
// 			}
// 			if(!empty($member_ids)){
// 				$map['to_member_id'] = array('in',$member_ids['id']);
// 				$use++;
// 			}
// 		}
// 		if(I('from_username')){
// 			/* 查询发送用户ID */
// 			$member_id_result=get_info('member',array('username|telephone|email'=>I('from_username')),'id');
// 			$member_ids = array();
// 			foreach($member_id_result as $val){
// 				$member_ids['id'][]=$val['id'];
// 			}
// 			if(!empty($member_ids)){
// 				$map['from_member_id'] = $member_info['id'];
// 				$use++;
// 			}
// 		}
    	
    	/*默认搜索条件 登录用户ID为发送用户的信息*/
    	$map['from_member_id'] = $member_id;
    	 
    	$message_list = $this->page(D($this->model),$map,array('add_time'=>desc),$field=array(),$this->limit);

    	foreach($message_list as $k=>$v){
    		if($v['from_member_id'] == $member_id){
    			$message_list[$k]['message_status'] =3;
    		}
    	}
    	$message_list=int_to_string($message_list,array("message_status"=>array("0"=>"未读","1"=>"已读","3"=>"已发送")));
    	$data['message_list']=$message_list;
		$data['member_id']=$member_id;
		//dump($data['message_list']);
    	$this->assign($data);
    	$this->display('list');
    }
    
    /**
     * 系统消息
     * 
     * @author					李东
	 * @date					2015-06-26
     */
    public function system_msg(){
    	$map['to_status'] = array('gt','-1');
    	$member_id =session('home_member_id');
//     	$use = 0; /*用户判断有无搜索条件,如果为0则没有，如果大于0，则有搜索条件*/
//     	if(I('title')){
//     		$map['title']=array('like','%'.I('title').'%');
//     		$use++;
//     	}
//     	if(I('to_username')){
// 			/* 查询接收用户ID */
// 			$member_id_result=get_result('member',array('username|telephone|email'=>I('to_username')),'id');
// 			$member_ids = array();
// 			foreach($member_id_result as $val){
// 				$member_ids['id'][]=$val['id'];
// 			}
// 			if(!empty($member_ids)){
// 				$map['to_member_id'] = array('in',$member_ids['id']);
// 				$use++;
// 			}
// 		}
// 		if(I('from_username')){
// 			/* 查询发送用户ID */
// 			$member_id_result=get_info('member',array('username|telephone|email'=>I('from_username')),'id');
// 			$member_ids = array();
// 			foreach($member_id_result as $val){
// 				$member_ids['id'][]=$val['id'];
// 			}
// 			if(!empty($member_ids)){
// 				$map['from_member_id'] = $member_info['id'];
// 				$use++;
// 			}
// 		}
//     	if(!$use>0){
//     		/*默认搜索条件 登录用户ID为接收用户的信息*/
// //      		$map['to_member_id'] = $member_id;
//     	}
    	//$map['from_member_id'] = 2;//发件人或收件人是该用户
    	//$map['type'] = 1; /*查询系统所发信息*/
		$map=null;
	//	$map['from_member_id'] = C('PLATFORM_ID');
		//$system_msg = get_result('message',$map);
		//$member_id = session('home_member_id');
		//foreach($system_msg as $k=>$row){
		//	$status = get_info('sysmsg_status',array('message_id'=>$row['id'],'member_id'=>$member_id),'status');
		//	$system_msg[$k]['to_status']=$status['status'];
	//	}
		//dump($system_msg);
	//	die;
		//$message_list=$system_msg;
    	//$message_list=int_to_string($message_list,array("status"=>array("0"=>"未读","1"=>"已读","3"=>"已发送")));
    	//$data['message_list']=$message_list;
		$sys_results=get_result('sysmsg_status',array('member_id'=>$member_id,'status'=>array('gt',-1)));
		foreach($sys_results as $k=>$v){
			$message_list[$k]= get_info('message',array('id'=>$v['message_id']));
			$message_list[$k]['to_status']=$v['status'];
			$message_list[$k]['sys_id']=$v['id'];
		}
		$message_list=int_to_string($message_list,array('to_status'=>array("0"=>"未读","1"=>"已读","3"=>"已发送")));
		$data['message_list']=$message_list;
    	$this->assign($data);
    	$this->display('list');
    }
    
    
    /**
     * 订单消息
     * @author					李东
     * @date					2015-06-26
     */
    public function order_msg(){
    	$map['to_status'] = array('gt','-1');
    	$member_id = session('home_member_id');
//     	$use = 0; /*用户判断有无搜索条件,如果为0则没有，如果大于0，则有搜索条件*/
//     	if(I('title')){
//     		$map['title']=array('like','%'.I('title').'%');
//     		$use++;
//     	}
//     	if(I('to_username')){
// 			/* 查询接收用户ID */
// 			$member_id_result=get_result('member',array('username|telephone|email'=>I('to_username')),'id');
// 			$member_ids = array();
// 			foreach($member_id_result as $val){
// 				$member_ids['id'][]=$val['id'];
// 			}
// 			if(!empty($member_ids)){
// 				$map['to_member_id'] = array('in',$member_ids['id']);
// 				$use++;
// 			}
// 		}
// 		if(I('from_username')){
// 			/* 查询发送用户ID */
// 			$member_id_result=get_info('member',array('username|telephone|email'=>I('from_username')),'id');
// 			$member_ids = array();
// 			foreach($member_id_result as $val){
// 				$member_ids['id'][]=$val['id'];
// 			}
// 			if(!empty($member_ids)){
// 				$map['from_member_id'] = $member_info['id'];
// 				$use++;
// 			}
// 		}
//     	if(!$use>0){
//     		/*默认搜索条件 登录用户ID为接收用户的信息*/
// //     		$map['to_member_id'] = $member_id;
//     	}
    	$map['to_member_id|from_member_id'] = $member_id;//发件人或收件人是该用户
    	$map['type'] = 2; /*查询订单系统所发信息*/
    	$message_list = $this->page(D($this->model),$map,array('add_time'=>desc),$field=array(),$this->limit);

    	foreach($message_list as $k=>$v){
    		if($v['from_member_id'] == $member_id){
    			$message_list[$k]['message_status'] =3;
    		}
    	}
    	$message_list=int_to_string($message_list,array("message_status"=>array("0"=>"未读","1"=>"已读","3"=>"已发送")));
    	$data['message_list']=$message_list;
    	$this->assign($data);
    	$this->display('list');
    }
    
    /**
     * 私人消息
     * @author					李东
     * @date					2015-06-26
     */
    public function personal_msg(){
    	$map['to_status'] = array('gt','-1');
    	$member_id = session('home_member_id');
//     	$use = 0; /*用户判断有无搜索条件,如果为0则没有，如果大于0，则有搜索条件*/
//     	if(I('title')){
//     		$map['title']=array('like','%'.I('title').'%');
//     		$use++;
//     	}
//     	if(I('to_username')){
// 			/* 查询接收用户ID */
// 			$member_id_result=get_result('member',array('username|telephone|email'=>I('to_username')),'id');
// 			$member_ids = array();
// 			foreach($member_id_result as $val){
// 				$member_ids['id'][]=$val['id'];
// 			}
// 			if(!empty($member_ids)){
// 				$map['to_member_id'] = array('in',$member_ids['id']);
// 				$use++;
// 			}
// 		}
// 		if(I('from_username')){
// 			/* 查询发送用户ID */
// 			$member_id_result=get_info('member',array('username|telephone|email'=>I('from_username')),'id');
// 			$member_ids = array();
// 			foreach($member_id_result as $val){
// 				$member_ids['id'][]=$val['id'];
// 			}
// 			if(!empty($member_ids)){
// 				$map['from_member_id'] = $member_info['id'];
// 				$use++;
// 			}
// 		}
//     	if(!$use>0){
//     		/*默认搜索条件 登录用户ID为接收用户的信息*/
// //     		$map['to_member_id'] = $member_id;
// //     			$map['to_member_id|from_member_id'] = $member_id;
//     	}
    	$map['to_member_id'] = $member_id;//发件人或收件人是该用户
    	$map['type'] = 3; /*查询个体用户所发信息*/
    	$message_list = $this->page(D($this->model),$map,array('add_time'=>desc),$field=array(),$this->limit);
//     	dump($message_list);die;
    	foreach($message_list as $k=>$v){
    		if($v['from_member_id'] == $member_id){
    			$message_list[$k]['message_status'] =3;
    		}
    	}
    	$message_list=int_to_string($message_list,array("to_status"=>array("0"=>"未读","1"=>"已读","3"=>"已发送")));
    	$data['message_list']=$message_list;
		$data['member_id']=$member_id;
    	//dump($data);
    	$this->assign($data);
    	$this->display('list');
    }
    
    
    
    /**
     * 查看消息详情 
     * @author					李东
     * @date					2015-06-26  
     */
    public function detail(){
     	$member_id = session('home_member_id');
     	$gets = I('get.');
		$type=I('get.type');
    	$map['message_id']=$gets['ids'];
    	$map['to_member_id'] = $member_id;
    	$info = get_info(D($this->model),$map);
    	/*如果是接受用户查看更改消息状态为已读*/
    	$pid_map['message_id'] =$info['pid'];
		if($type==1){
			$_POST=array(
				'id'=>I('get.sys_id'),
				'status'=>1,
			);
			update_data('sysmsg_status');
		}else{
			$parent_info  = get_info(D($this->model),$pid_map);
			if($info['to_member_id'] == $member_id){
				$_POST=array(
					'to_status'=>1,
				);
				update_data($this->table,array(), array('id'=>$map['message_id']));
			}
			$from_member_id = $info['from_member_id'];
			$to_member_id = $member_id;
			$map = array();
			/* 以两个用户的id为接收或者发出id拼接查询条件，当一方为接收者时另一方必须为接收者 */
			//$map['_string'] = '( from_member_id = '.$from_member_id.' and to_member_id = '.$to_member_id.') or ( to_member_id = '.$from_member_id.' and from_member_id = '.$to_member_id.')';
			if($info){
				$map['id'] = array('in',array($info['id'],$info['pid']));
				/*用于判断是否是自己查询的消息** 1是0否  */
				$self =0;
			}else{
				$map['id']=$gets['ids'];
				/*用于判断是否是自己查询的消息 ** 1是0否 */
				$self =1;
			}
			$result = $this->page(D($this->model),$map,$order=' message_id desc',$field=array(),$this->limit);
			$data['result'] = $result;
			$data['info'] = $info;
			$data['self']=$self;
			$this->assign($data);
		}
    	
    	$this->display();
    }
    
    
    
    /**
     * 删除消息
     * 备份文件中为你写的原版，这个功能需要改  @刘巧
     * @author					李东
     * @date					2015-06-27 
     */
	 
    public function del(){;
		$ids=I('get.id');
		if(!empty($_GET['status'])){
			$_POST=array(
				'id'=>$ids,
				'status'=>$_GET['status'],
			);
		}else{
			$_POST=array(
				'id'=>$ids,
				'to_status'=>$_GET['to_status'],
			);
		}
		
		$result=update_data('message');
    	if($result){
    		$this->success('操作成功!',U('index'));
    	}else{
    		$this->error('操作失败!');
    	}
    }
    
    
    /**
     * 附件下载
     * 用于防止pdf,png,jpg等类型图片直接在浏览器打开
     * @author					李东
     * @date					2015-06-19
     */
    public function download(){
    	if(!I('get.id')){
    		$this->error('附件不存在');
    	}
    	$id = I('get.id');
    	$info = get_info($this->table,array('id' =>$id));
    	$info['title'] = str_replace(' ','_',trim($info['title']));
    	$filename = 'http://'.I('server.HTTP_HOST').__ROOT__.'/'.$info['accessory_path'];
    	//文件的类型
    
    	$filetype = substr($info['accessory_path'],strrpos($info['accessory_path'],'.')+1);
    	$header ="Content-type: application/".$filetype;		//获取文件类型
    
    	header($header);
    	//下载显示的名字
    	$showname = "Content-Disposition: attachment; filename={$info['title']} - 附件.{$filetype}";
    	header($showname);
    	readfile($filename);
    	exit();
    }
    
    
    /**
     * 消息回复
     * 系统消息不可回复
     *
     * @author					李东
     * @date					2015-06-27
     */
    public function reply(){
//     	print_r(I());exit;
    	if(IS_POST){
    		$posts = I('post.');
    		$member_id = session('home_member_id');
    			
    		/* 定义添加验证规则 */
    		$rules = array(
    				array('title','require','分类名称不能为空'),
    				array('content','require','请填内容'),
    		);
    			
    		$_POST = array(
    				'pid'=>$posts['pid'],
    				'title'=>$posts['title'],
    				'type'=>$posts['type'],
    				'content'=>$posts['content'],
    				'to_member_id'=>$posts['to_member_id'],
    				'from_member_id'=>$member_id,
    		);
    		$result=update_data($this->table, $rules);
    		if(is_numeric($result)){
    			/*将附件地址更新到数据库*/
    			multi_file_upload($posts['accessory_path'],'Uploads/Message/accessory',$this->table,'id',$result,'accessory_path');
    			$this->success('操作成功',U('index'));
    		}else{
    			$this->error($result);
    		}
    	}else{
    		$this->error('错误请求');
    	}
    }
    
    /**
     * 发送信息
     * @author					李东
     * @date					2015-06-29
     */
    function send_msg(){
    	if(IS_POST){
    		$posts = I('post.');
    		$member_id =session('home_member_id');
    		$to_user_info = get_info('member',array('username|telephone'=>trim($posts['username'])));
			//判断收件人是否存在
    		if(!$to_user_info){
    			$this->error('用户不存在');
    		}else{
    			if($to_user_info['id']==$member_id){
    				$this->error('收信人不能为自己');
    			}
    			if(empty($posts["content"])){
    				$posts["content"]=$posts['title'];
    			}
	    		$_POST = array(
	    				'from_member_id'=>$member_id,
	    				'to_member_id'=>$to_user_info['id'],
	    				'title'=>$posts['title'],
	    				'content'=>$posts['content'],
	    				'accessory_path'=>$posts['accessory_path'],
	    				'username'=>trim($posts['username']),
	    				'type'=>3,
						'status'=>1,
	    		);
	    		/* 定义添加验证规则 */
	    		$rules = array(
	    				array('username','require','收件人不能为空'),
	    				array('title','require','标题不能为空'),
	    				array('content','require','请填内容'),
	    		);
	    		$result=update_data($this->table, $rules);
				$get_friend=$this->add_friends($_POST);//增加联系人
	    		if(is_numeric($result)){
	    			/*将附件地址更新到数据库*/
	    			multi_file_upload($posts['accessory_path'],'Uploads/Message/accessory',$this->table,'id',$result,'accessory_path');
	    			$this->success('操作成功',U('index'));
					
	    		}else{
	    			$this->error($result);
	    		}
    		}
    	}else{
    		$gets = I('get.');
    		$to_member_id = $gets['member_id'];
    		$user_name='';
    		if($to_member_id){
    			$map['id'] = $to_member_id;
    			$user_info = get_info('member',$map,'username');
    			$user_name = $user_info['username'];
    		}
    		$data['username'] = $user_name;
    		$this->assign($data);
    		$this->display();
    	}
    }
    
    public function get_nickname(){
    	$username=I('post.username');
    	$map['username']=$username;
    	$user_info = get_info('member',$map);
    	$nickname=$user_info['nickname'];
    	file_put_contents('D:\ab.txt', $username);
    	file_put_contents('D:\aa.txt', $nickname);
    	$data['nickname']=$nickname;
    	$data['username']=$user_info['username'];
    	$this->ajaxReturn($data);
    	//return $nickname;
    	
    }
    
    /**
     * 举报信息
     * @author					赵群
     * @date					2015-07-29
     */
    public function inform(){
    	if(IS_POST){
    		$posts = I('post.');
//     		dump($posts);die;
    		$member_id =session('home_member_id');
    		$rules = array(
    				array('type','require','举报类型不能为空'),
    				array('reason','require','举报原因不能为空'),
    		);
    		$_POST = array(
    				'member_id'=>$member_id,
    				'reporter_id'=>$posts['reporter_id'],
    				'type'=>$posts['type'],
    				'reason'=>$posts['reason'],
    				'status'=>0,
    		);
    		$result=update_data('inform', $rules);
    		if(is_numeric($result)){
    			$this->success('举报成功',U('index'));
    		}else{
    			$this->error($result);
    		}
    	}else{
    		$gets = I('get.');
    		$reporter_id = $gets['ids'];
    		$this->assign("reporter_id",$reporter_id);
    		$this->display();
    	}
    }
	
	/*
	*@liuqiao
	*1.是否需要主动添加好友
	*2.用户发来消息，自动添加到好友列表，用户可以沟通，同意后成为真正好友
	*3.不可以加自己为好友
	*4.用户发消息时，好友的id变成friend_id，并且friend_status变为1
	*5.对方接收消息的时候，发件人id变为friend_id。并且friend_status变为0
	*/
	
	public function friends_list(){	
		$from_id=session('home_member_id');
		$friend_results = $this->page('contacts',array('user_id'=>$from_id,'status'=>array('gt',-1)),'','',5);	//查询登入用户好友
		$this->assign('friend_results',$friend_results);	
		$this->display();
	}
	//增加好友
	public function  add_friends($map){
		$map_post=$map;
		if(!empty($map_post)){//如果不为空则代表用户发送邮件
			$_POST=array(
				'friend_id'=>$map_post['to_member_id'],//接收好友id
				'user_id'=>$map_post['from_member_id'],//接收用户id
				'user_status'=>1,
				'friend_status'=>1,//主动发消息，状态变为1 
				'from_telphone'=>$map_post['username'],
			);
			$info=get_info('contacts',array('friend_id'=>$map_post['to_member_id']));
			if(empty($info)){
				$result=update_data('contacts');
			}
		}
		return $result;
	}
	//删除好友
	public function del_msg(){
		$ids=I('ids');
		$_POST=array(
			'id'=>I('get.id'),
			'status'=>I('get.status'),
		);
		$result=update_data('contacts');
    	if($result){
    		$this->success('操作成功!');
    	}else{
    		$this->error('操作失败!');
    	}	
	}
	

    
    
    
    
}