<?php
namespace Backend\Controller\Message;
use Backend\Controller\Base\AdminController;
class LetterController extends AdminController {
	protected $table='message';
	protected $model='MessageView';
	protected $limit=15;		
	
	/**
	 * 消息列表
	 * 查询出所有给该用户发送的信息或者该用户发出的信息
	 * @author						李东
	 * @date						2015-06-19
	 */
	public function index(){
		$map['status'] = array('gt','-1');
		$member_id = session('member_id');
		$use = 0; /*用户判断有无搜索条件,如果为0则没有，如果大于0，则有搜索条件*/
		if(I('title')){
			$map['title']=array('like','%'.I('title').'%');
			$use++;
		}
		if(I('to_username')){
			/* 查询接收用户ID */
			$member_id_result=get_result('member',array('username|telephone|email'=>I('to_username')),'id');
			$member_ids = array();
			foreach($member_id_result as $val){
				$member_ids['id'][]=$val['id'];
			}
			if(!empty($member_ids)){
				$map['to_member_id'] = array('in',$member_ids['id']);
				$use++;
			}
		}
		
		if(I('from_username')){
			/* 查询发送用户ID */
			$member_id_result=get_result('member',array('username|telephone|email'=>I('from_username')),'id');
			$member_ids = array();
			foreach($member_id_result as $val){
				$member_ids['id'][]=$val['id'];
			}
			if(!empty($member_ids)){
				$map['from_member_id'] = array('in',$member_ids['id']);
				$use++;
			}
		}
		if(!$use>0){
			/*默认搜索条件*/
// 			$map['from_member_id|to_member_id'] = $member_id;
		}
			$result = $this->page(D($this->model),$map,$order=' status asc,id desc ',$field=array(),$this->limit);
		foreach($result as $k=>$v){
			if($v['from_member_id'] == $member_id){
				$result[$k]['message_status'] =3;
			}
		}
		$result=int_to_string($result,array("message_status"=>array("0"=>"未读","1"=>"已读","3"=>"已发送")));
		$data['result']=$result;
		$this->assign($data);
		$this->display();
	}
	
	/**
	 * 查看用户信息详情
	 * 包括两个用户之间所有的交流记录
	 * @author						李东
	 * @date						2015-06-19
	 * 
	 */
	public function detail(){
		//$member_id = session('member_id');
		$map['message_id']=I('ids');
		
		$info = get_info(D($this->model),$map);
		/*更改消息状态为已读*/
		/*$_POST=array('status'=>1);
		if($info['from_member_id'] != $member_id){
			update_data($this->table,array(), array('id'=>$map['message_id']));
		}*/
		
		//$from_member_id = $info['from_member_id'];
		//$to_member_id = $info['to_member_id'];//$member_id;
		//$map = array();
		/* 以两个用户的id为接收或者发出id拼接查询条件，当一方为接收者时另一方必须为接收者 */
		//$map['_string'] = '( from_member_id = '.$from_member_id.' and to_member_id = '.$to_member_id.') or ( to_member_id = '.$from_member_id.' and from_member_id = '.$to_member_id.')';
		
		//$result = $this->page(D($this->model),$map,$order=' id desc',$field=array(),$this->limit);
		$data['result'] = $info;
		$content=$data['result']['content'];
		if($data['result']['message_title']=='订单通知'){
			$data['result']['content']=str_replace('/User/Order/', '/nbfy/web/Backend/Order/Order/', $content);
		}elseif ($data['result']['message_title']=='下单通知'){
			$data['result']['content']=str_replace('/User/Myorder/', '/nbfy/web/Backend/Order/Order/', $content);
		}
		$this->assign($data);
		$this->display('view');
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
	 * @date					2015-06-19
	 */
	public function reply(){
		if(IS_POST){
			$posts = I('post.');
			
			/* 定义添加验证规则 */
			$rules = array(
					array('title','require','分类名称不能为空'),
					array('content','require','请填内容'),
			);
			
			$_POST = array(
				'title'=>$posts['title'],
				'type'=>$posts['type'],
				'content'=>$posts['content'],
				'to_member_id'=>$posts['to_member_id'],
				'from_member_id'=>session('member_id'),
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
	
	public function send_msg(){
    	if(IS_POST){
    		$posts = I('post.');
    		$member_id =C('PLATFORM_ID');
			$Model = M();			
			$Model->startTrans();
			/*type :0普通用户，2个人译者，3翻译公司*/
			/* 定义添加验证规则 */
			$types=$_POST['types'];
			$rules = array(
					array('title','require','标题不能为空'),
					array('content','require','请填内容'),
			);
			$_POST = array(
					'from_member_id'=>$member_id,
					'title'=>$posts['title'],
					'content'=>$posts['content'],
					'accessory_path'=>$posts['accessory_path'],
					'type'=>1,
					'status'=>1,
					//'add_time'=>date(y-m-d);
			);
			$result=update_data('message', $rules);
			/*@liuqiao 将信息存入sysmsg_status*/
			
			if(is_array($type)){//判断type是不是数组
				$type=array('in',$type);
				$member_result=get_result('member',array('type'=>array('in',$types),'status'=>array('eq',1)));
			}else{
				$member_result=get_result('member',array('type'=>array('in',$types),'status'=>array('eq',1)));
			}
			foreach($member_result as $k=>$v){
				$_POST=array(
					'member_id'=>$v['id'],
					'message_id'=>$result,
					'status'=>0,
				);
				$result2=update_data('sysmsg_status');//循环更新sysmsg_status数据
			}
			if(is_numeric($result)&&is_numeric($result2)){
				$Model->commit();
				/*将附件地址更新到数据库*/
				multi_file_upload($posts['accessory_path'],'Uploads/Message/accessory',$this->table,'id',$result,'accessory_path');
				$this->success('操作成功',U('index'));
				
			}else{
				$Model->rollback();
				$this->error($result);
			}
    	}else{
    		$this->display();
    	}
    }
}
