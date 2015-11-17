<?php
namespace User\Controller;
use User\Controller\BaseController;
class AddressController extends BaseController {
	protected $table = 'address';				/*地址管理表*/
	protected $limit=10;
	/**
	 *收货地址管理
	 *	显示收货地址信息,并允许添加新的收货地址
	 *流程分析
	 *	1、将收货地址信息显示成列表
	 *	2、隐藏前端的提交表单
	 *	3、利用js做新增收货地址判断
	 *	4、书写逻辑
	 *			判断是修改还是添加还是删除
	 **/
	
	public function index(){
		    //手机app接口参数
		    $apptype = I('post.apptype')==C('APP_KEY') ?true:false;//手机app接口密钥 
		    if($apptype){
		    	$app_key=trim(I("post.key"));//md5加密的登录时间
		        $member_id=I("post.home_member_id");	
		        $this->isLoginExpire($app_key,$member_id);//判断登录过期
		    }else{
			    $member_id =  session('home_member_id');
		    }
			//查询地理信息
			$area = get_area_cache();
			$area_id_key = array_id_key($area);
			//查询显示用户的所有地址信息
			$address_info = $this->page($this->table,array('status'=>1,'member_id'=>$member_id));
			$data['area'] = $area;
			//循环地址信息，取得地址中的省、市、区，并拼接到详细地址上
			foreach($address_info as $key=>$row){
				$address_arr = explode('-',$row['area_path']);
				$new_address = $area_id_key[$address_arr['2']]['title'].$area_id_key[$address_arr['3']]['title'].$area_id_key[$address_arr['4']]['title'];
				$address_info[$key]['new_address'] = $new_address;
			}
			/*$map = array(
				'status'=>1,
				'member_id'=>$member_id
			);*/
			
			$data['address'] = $address_info;
			if($apptype){
				$this->ajaxReturn($data['address']);
			}
			$this->assign('data',$data);
			$this->display('address');
	}
		
	/**
	 * 地址信息删除
	 * @author						赵群
	 * @date						2015-07-22
	 */
	public function del(){
			$apptype=$this->appParam();
			if($apptype==-1){
				$apptype=0;
			}else{
				$apptype=1;
			}
			$gets = I();
			$rules = array(
					array('id','require','地址ID不能为空！'),
			);
			//将需要更改的信息字段设置并更新数据表
			$_POST=array(
					'status'=>-1,				//设置状态为不显示信息
					'id'=>	$gets['id']		//数据的ID
			);
			$res = update_data($this->table,$rules);
			if(is_numeric($res)){
				$tips_msg=array('status'=>1,'info'=>'删除成功','url'=>U('User/Address/index'));
				//$this->success('删除成功！',U('User/Address/index'));
			}else{
				$tips_msg=array('status'=>0,'info'=>'删除失败！','url'=>U('User/Address/index'));
				//$this->error('删除失败！',U('User/Address/index'));
			}
			$this->appOut($tips_msg,$apptype,0);
		}

		/**
		 * 地址信息修改
		 * @author						赵群
		 * @date						2015-07-22
		 */
	public function edit(){
	    $apptype = (!empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY'))?true:false;//手机app接口密钥
		if($apptype){
		    $app_key=trim(I("key"));
	    	$member_id=I("home_member_id");	
	    	$this->isLoginExpire($app_key,$member_id);
		}
		unset($_POST["apptype"]);
		unset($_POST["key"]);
		unset($_POST["home_member_id"]);

		if (!empty(I("post."))){
		    if($apptype){
			  $_POST["apptype"]=$apptype;
			  $_POST["home_member_id"]=$member_id;
		    }	
			$this->update();
		}else{
			
			$gets = I('get.');
			$map = array(
					'id'=>$gets['id'],
			);
			//获取用户类型
			$member_id =  session('home_member_id');
			$type = get_info('member',array('id'=>$member_id),$field=array('type'));
			//获取用户需要修改的单条信息
			$res = get_info($this->table,$map);
			//获取地址的分类路径并拆分成数组
			$area = get_area_cache();
			$ret = explode('-',$res['area_path']);
			$data['area'] = $area;
			//根据数据表中的字段获取地址信息
			$province_data = array();
			$city_data = array();
			$area_data = array();
			foreach ($area as $value){
				if ($value['id'] == $ret[2]){
					$province_data['province'] = $value['title'];
					$province_data['id'] = $value['id'];
					$province_data['path'] = $value['path'];
				}
				if ($value['id'] == $ret[3]){
					$city_data['city'] = $value['title'];
					$city_data['id'] = $value['id'];
					$city_data['path'] = $value['path'];
				}
				if ($value['id'] == $ret[4]){
					$area_data['area'] = $value['title'];
					$area_data['id'] = $value['id'];
					$area_data['path'] = $value['path'];
				}
			}
			if($apptype){
			  // $appinfo["area"] = getAreaInfo();//地理信息
			   $appinfo["ret"] = (array)$ret;//用户地址的分类路径
			   $appinfo["province_data"] = $province_data;//省
			   $appinfo["city_data"] = $city_data;//市
			   $appinfo["area_data"] = $area_data;//区
			   $appinfo['res'] = (array)$res;
			   $this->ajaxReturn($appinfo);
			}
// 			$this->assign('data',$data)->assign('res',$res)->assign('ret',$ret)->assign('province_data',$province_data)->assign('city_data',$city_data)->assign('area_data',$area_data);
			$this->assign('data',$data)->assign('res',$res)->assign('ret',$ret)->assign('province_data',$province_data)->assign('city_data',$city_data)->assign('area_data',$area_data)->assign('type',$type);
			$this->display();
		}
	}
	
	/**
	 * 地址信息更新
	 * @author						赵群
	 * @date						2015-07-22
	 */
	public function update(){
		$apptype = I('post.apptype');//手机app接口密钥  
		if($apptype){
		  $member_id =  intval(I('home_member_id'));
		  $_POST["addpath"]="-0-";
		  $ajax=true;
		}else{
		  $member_id =  session('home_member_id');
		  $ajax=false;
		}
		if(IS_POST){
			$member_id =  session('home_member_id');
			if (!isset($member_id)){
				$this->error('登录过期，请重新登录！');
			}
			$posts = I('post.');
			if($posts){
				if (!empty($posts['addpath'])||!is_numeric($member_id)){
					//将省、市、区的信息拼接
					if($apptype){
					  $area_list[] = $posts['province'];
					  $area_list[] = $posts['city'];
					  $area_list[] = $posts['area'];
					  $area_list = array_filter($area_list);
					  if(is_array($area_list) and !empty($area_list)){
					  	$posts['addpath']="-0-".implode("-",$area_list)."-";
					  }else{
					  	$this->error("您填写的地址有误",'',true);
					  }
					}else{
					  $posts['addpath'] .= $posts['area'];
					}
					//验证用户输入信息
					$rules = array(
							array('title','require','地址别名不能为空！'),
							array('username','require','收货人不能为空！'),
							array('phone_number','require','手机号不能为空！'),
							array('postalcode','require','邮编不能为空！'),
							array('detailed_address','require','详细地址不能为空！'),
							//array('phone_number','number','手机号必须为数字！'),
							array("phone_number","/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/",'手机号码格式不正确',1,'regex'),
							array('postalcode','zip','邮编格式不正确'),
					);
					//组织将信息修改到数据表中
					$_POST = array(
							'member_id'=>$member_id,											//当前登录用户ID	
							'id'=>$posts['id'],															//数据的ID		
							'title'=>$posts['title'],														//发票抬头
							'username'=>$posts['username'],								//收货人姓名
							'phone_number'=>$posts['phone_number'],			//手机号码
							'postalcode'=>$posts['postalcode'],							//邮政编码
							'detailed_address'=>$posts['detailed_address'],		//详细地址
							'default_address'=>$posts['default_address'],			//默认收货地址
							'area_path'=>$posts['addpath'],									//分类的路径
							'status'=>1,																		//设置状态为显示信息
					);
					$res = update_data($this->table,$rules);
					//如果用户选中为默认地址，就将当前默认地址字段置为1，并将其他数据的默认地址置为0
					if($posts['default_address']==1){
						if(is_numeric($res)){
						   $red2 =M()->execute("UPDATE sr_address SET default_address =if(id={$res},1,0) WHERE member_id={$member_id};");
						}
					}
					if(is_numeric($res)){
						if(!empty($posts['id'])){
						   $this->success('修改成功',U('User/Address/index'),$ajax);
						}else{
						   $this->success('添加成功',U('User/Address/index'),$ajax);
						}
					}else{
						$this->error($res,'',$ajax);
					}
				}else{
					$this->error('缺少收取地址信息','',$ajax);
				}
			}else{
				$this->error('缺少参数','',$ajax);
			}
		}
	}
	
   
	public function setDefault(){
		$gets = I();
		$id = intval($gets['id']);
		if(!empty($id)){
			$member_id = session("home_member_id");
			$res=M()->execute("UPDATE sr_address SET default_address =if(id={$id},1,0) WHERE member_id={$member_id};");
			if($res){
				$this->success('设置成功！',U('User/Address/index'));
			}else{
				$this->error('设置失败！',U('User/Address/index'));
			}
		}else{
			$this->error("地址ID不能为空");
		}
	}
   /*
    * 获取地址分类数据
    * @author  龚双喜
    * @date    2015-07-25
    * */
   public function getArea(){ 	
   	 $apptype = (!empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY'))?true:false;//手机app接口密钥
   	 $pid = I("post.id");//地区父类id
   	 
   	 if($apptype){
   	 	$area_info["area"]=getAreaInfo($pid);//获取地区子类
   	 	$this->ajaxReturn($area_info);//返回json
   	 }else{
   	 	$this->error("非法访问",'',true);
   	 }
   }
//   public function getArea(){ 	
//   	 $apptype = !empty(I('post.apptype')) and I('post.apptype')==C('APP_KEY') ?true:false;//手机app接口密钥
//   	 $pid = I("post.id");//地区父类id
//   	 
//   	 if($apptype){
//   	 	$area_info["area"]=getAreaInfo($pid);//获取地区子类
//   	 	$this->ajaxReturn($area_info);//返回json
//   	 }else{
//   	 	$this->error("非法访问",'',true);
//   	 }
//   }
}