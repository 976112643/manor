<?php
namespace Common\Controller;
use Think\Controller;
class CommonController extends Controller {
	/**
	 * 前台控制器初始化
	 */
	protected function _initialize(){
		//判断是否手机端访问
		$is_phone = $this->isMobile();
		if ($is_phone){
			C('IS_MOBILE',1);//表示是手机在访问
			//C('DEFAULT_THEME','Mobile');//将默认模板修改为Mobile
		}
		//C('IS_MOBILE',1);//表示是手机在访问
		/*读取配置*/
		if(!F('config')){
			$config = M('config')->getField('name,value');
			F('config',$config);
		}else{
			$config=F('config');
		}
		C($config); // 合并配置参数到全局配置
		$this->__autoload();
	}
	/**
	 * 判断城市是否筛选
	 */
	protected function __autoload(){
		$city = session('cur_city_title');
		if(!$city){
			session('cur_city_title','全国');
		}
	}	
	/*
	 * 分页功能
	 * @time 2014-12-26
	 * @author	郭文龙  <2824682114@qq.com>
	 * */
	function page($model,$map=array(),$order='',$field=array(),$limit=''){
		if(is_string($model)){
			$model  =   M($model);
		}
		if(!$limit){
			$limit=$_REQUEST['r']?$_REQUEST['r']:20;
		}
		$page=intval($_GET['p']);
		// 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
		$list = $model->where($map)->field($field)->order($order)->page("$page,$limit")->select();
		session('sql',$model->getLastSql());
		$data['count']=$count= $model->where($map)->count();// 查询满足要求的总记录数
		$data['page_count'] = ceil($count/$limit); //计算总页码数
		
		$Page       = new \Think\Page($count,$limit);// 实例化分页类 传入总记录数和每页显示的记录数
		$Page->setConfig('header', '条数据');//共有多少条数据
		$Page->setConfig('prev', "上一页");//上一页
		$Page->setConfig('next', '下一页');//下一页
		$Page->setConfig('first', '首页');//第一页
		$Page->setConfig('last', '尾页');//最后一页
		$Page->lastSuffix = false;
		$Page->rollPage = 7;
		$data['page']       = $Page->show();// 分页显示输出
		
		$this->assign($data);// 赋值分页输出
		return $list;
	}
	
	/* 删除临时上传图片 */
	public function delTempFile($id){
		if(!session("home_member_id")){
			$this->error("没有登录不能进行此操作");
		}
		$info=get_info("file",array("id"=>$id));
		$path=ltrim($info['save_path'],'/');
		if(file_exists($path)){
			if(@unlink($path)){
				$info="删除成功";
			}else{
				$info="删除失败";
			}
		}else{
			$info="文件不存在，删除失败";
		}
		delete_data("file",array("id"=>$id));
		$this->success($info);
	}

	/* 文件上传 */
	public function uploadFile(){
		$return  = array('status' => 1, 'info' => '上传成功', 'data' => '');
		/* 调用文件上传组件上传文件 */
		$File = D('File');
		$file_driver = C('FILE_UPLOAD_DRIVER');
		$info = $File->upload(
				$_FILES,
				C('FILE_UPLOAD'),
				C('FILE_UPLOAD_DRIVER'),
				C("UPLOAD_{$file_driver}_CONFIG")
		);
		/* 记录附件信息 */
		if($info){
			$return['status'] = 1;
			$return = array_merge($info['download'], $return);
		} else {
			$return['status'] = 0;
			$return['info']   = $File->getError();
		}
	
		/* 返回JSON数据 */
		$this->ajaxReturn($return);
	}
	
	/* 下载文件 */
	public function download($id = null){
		if(empty($id) || !is_numeric($id)){
			$this->error('参数错误！');
		}
	
		$logic = D('Download', 'Logic');
		if(!$logic->download($id)){
			$this->error($logic->getError());
		}
	}
	
	/**
	 * 上传图片
	 * @author huajie <banhuajie@163.com>
	 */
	public function uploadPicture(){
		//TODO: 用户登录检测
	
		/* 返回标准数据 */
		$return =array('status'=>1,'info'=>'上传成功','data'=>'');
	
		/* 调用文件上传组件上传文件 */
		$Picture = D('File');
		$pic_driver = C('IMG_UPLOAD_DRIVER');
		$info = $Picture->upload(
				$_FILES,
				C('IMG_UPLOAD'),
				C('IMG_UPLOAD_DRIVER'),
				C("UPLOAD_{$pic_driver}_CONFIG")
		); //TODO:上传到远程服务器
	
		/* 记录图片信息 */
		if($info){
			$return['status'] = 1;
			$return = array_merge($info['download'], $return);
		} else {
			$return['status'] = 0;
			$return['info']   = $Picture->getError();
		}
		
		/* 返回JSON数据 */
		$this->ajaxReturn($return);
	}
	/* 
	public function editor(){
		$this->display("Common@Plugins/editor");
	} */
	
	/* 百度编辑器上传调用方法 */
	public function ueditor(){
		$data = new \Ueditor\Ueditor();
		echo $data->output();
	}
	/**
	 * 判断用户是通过电脑访问还是手机访问
	 * @author					李东
	 * @date					2015-04-24
	 * @return boolean
	 */
	function isMobile(){
		$useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';
	
		$mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
		$mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');
	
		$found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) ||
		CheckSubstrs($mobile_token_list,$useragent);
	
		if ($found_mobile){
			return true;
		}else{
			return false;
		}
	}
	
	/*
	 * app访问时，调整session参数
	 * */
	public function appParam(){
		if(IS_POST){
			
			$type = I('apptype');
			$home_member_id=intval(I('home_member_id'));
			$home_shop_id=intval(I('home_shop_id'));
			if($type==C('APP_KEY')){
				unset($_POST['apptype']);
				if($home_member_id){
					$key=I('key');
					$info=get_info('member',array('id'=>$home_member_id,'status'=>1));
					if(!$info){
						return 0;
					}else{
						if($key==md5($info['login_time'])){
							session('home_member_id',$home_member_id);
							unset($_POST['home_member_id']);
							unset($_POST['key']);
						}else{
							return 0;
						}
					}
					
				}
				if($home_shop_id){
					session('home_shop_id',$home_shop_id);
					unset($_POST['home_shop_id']);
				}
				return 1;	
			}else if(!empty($type)){
				return 0;	
			}else{
				return -1;
			}
		}else{
			
			return -1;
		}
	
	}
	
	/*
	 * app和pc的不同输出
	 * $tips_msg 传输的数据，形式array('status'=>1,'info'=>$data);
	 * $apptype  0:网站  1：app访问
	 * type:0:普通输出   1:网页输出
	 * $temp  如果不为空，display的参数
	 * */
	public function appOut($tips_msg,$apptype=0,$type=0,$temp=''){
		foreach ($tips_msg as $key=>$val){
			if($key=='msg'){
				$tips_msg['info']=$val;
				unset($tips_msg['msg']);
			}
		}
		if($apptype==1){//app访问
			$this->ajaxReturn($tips_msg);
		}else{
			if($type==1){//列表输出
				$this->assign($tips_msg['info']);
				if(empty($temp)){
					$this->display();
				}else{
					$this->display($temp);
				}
			}else{//普通输出
				if($tips_msg['status']){
					$this->success($tips_msg['info'],$tips_msg['url']);	
		    	}else{
		    		$this->error($tips_msg['info'],$tips_msg['url']);
		    	}
			}
		
		}
		
	}
	

	
	/*
	 * 判断用户登录时间是否过期
	 * @param     string   $key   md5加密的登录时间
	 * @param     int      $id    数据id
	 * @author    龚双喜
	 * @date      2015-07-22
	 * @return    json
	 * */
	public function isLoginExpire($key,$id){
		
	   if(!empty($key) and is_numeric($id)){
	   	
	   	 $model = "member";//用户表
	   	 $map["id"] = array("eq",$id);
	   	 
         $result=get_info($model,$map,$field="login_time");
         
	   	 if($key!=md5($result["login_time"])){
	   	 	
	   	 	$data["status"]="0";
	   	 	$data["info"]="登录时间已经过期";
	   	 	//返回json数据
	   	 	$this->ajaxReturn($data);
	   	 }
	   }
	}
    
}