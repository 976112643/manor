<?php

include 'core.php';
/*
 * 导航数据缓存
 * @time 2015-01-21
 * @author	郭文龙  <2824682114@qq.com>
 * */
function getNavigation(){
	$cache_name='navigation_result';
	//判断缓存是否存在
	if(!F($cache_name)){
		$cache_result=get_result('navigation',array('status'=>1),'','sort desc,id asc');
		$result=get_cache_data($cache_result,$cache_name,'type');
	}else{
		$result=F($cache_name);
	}
	return $result;
}

/*
 * 获取首页广告数据
 * @time 2015-05-05
 * @author	康利民  <3027788306@qq.com>
 * */
function getBanner($type){
	$cache_name=$type.'_banner';
	//判断缓存是否存在
	if(!F($cache_name)){
		
		$cur_date=date("Y-m-d H:i:s",time());
		$map['page']=$cache_name;
		$map['start_time']=array("elt",$cur_date);
		$map['end_time']=array("egt",$cur_date);
		$map['status']=1;
		$result=get_result("banner",$map,'','sort desc,id asc');
		F($cache_name,$result);
// 		$result=get_cache_data($cache_result,$cache_name);
	}else{
		$result=F($cache_name);
	}
	return $result;
}

/*
 * 获取分类数据
 * $type 	string 	分类类型
 * @time 2015-05-05
 * @author	康利民  <3027788306@qq.com>
 * */
function getCategory($type){
	$cache_name=$type.'_category_result';
	//判断缓存是否存在
	if(!F($cache_name)){
		$map['status']=1;
		$map['type']=$type;
		$cache_result=get_result("category",$map,'','sort desc,id asc');
		$result=get_cache_data($cache_result,$cache_name);
	}else{
		$result=F($cache_name);
	}
	return $result;
}
	/*
	 * CURL get 提交数据
	 * */
	function curl_get($url){
		//初始化
		$ch = curl_init();
		//设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		//执行并获取HTML文档内容
		$output = curl_exec($ch);
		//释放curl句柄
		curl_close($ch);
		//返回获得的数据
		return $output;
	}
/*
 * 短信发送接口
 * */
function sms($mobile,$content,$minute=1){
	//短信供应商提供的代码
	//$sn='SDK-WSS-010-07787';
	//短信供应商提供的密码
//	$password='2554c2]7';
//	$pwd=md5($sn.$password);
//	$pwd=strtoupper($pwd);
//	$content=urlencode($content);
//	$url="http://sdk2.entinfo.cn:8061/mdsmssend.ashx?sn=$sn&pwd=$pwd&mobile=$mobile&content=$content&ext=&stime=&rrid=&msgfmt=";
//	$status=curl_get($url);
//	return $status;

	import('SendScm.Ucpaas');
	$ucpass=new \Ucpaas(C('SMS_CONFIG'));
	$appId =C('SMS_APPID');// "f0b1c14f5a88401297bed6ede4872b08";//应用id	
	$to=$mobile;
	$templateId =C('SMS_TEMPLATE'); //"10199";//短信模板id
	$param="{$content},".$minute;
	$res = $ucpass->templateSMS($appId,$to,$templateId,$param);
	$ret=json_decode($res,true);
	if($ret['resp']['respCode']=='000000'){
		return 1;
	}else{
		return -1;
	}
}

/**
 * 查询分类信息
 * 查询出所有分类信息，并生成缓存
 * @author				李东
 * @date				2015-06-12
 */
/* function get_category(){
	$cache_name = 'category_cache';

	if(!F($cache_name)){
		$result = get_result('category',array('status'=>1),'',' sort desc'); //查询所有正常状态的分类
		F($cache_name,$result);
	}else{
		$result=F($cache_name);
	}
	return $result;
} */
/**
 * 查询语种分类信息
 * 查询出所有语种分类信息，并生成缓存
 * @author				李东
 * @date				2015-06-15
 */
function get_language_cache(){
	$cache_name = 'language_cache';

	if(!F($cache_name)){
		$result = get_result('category',array('status'=>1,'type'=>0),'',' sort desc'); //查询所有正常状态的分类
		F($cache_name,$result);
	}else{
		$result=F($cache_name);
	}
	return $result;
}
/**
 * 查询推荐语种分类信息
 * 查询出所有语种分类信息，并生成缓存
 * @author				李东
 * @date				2015-06-15
 */
function get_language_recommend_cache(){
	$cache_name = 'language_recommend_cache';
	if(!F($cache_name)){
		$result = get_result('category',array('status'=>1,'type'=>0,'level'=>2,'recommend'=>1),'',' sort desc',10); //查询所有正常状态的分类
		F($cache_name,$result);
	}else{
		$result=F($cache_name);
	}
	return $result;
}
/**
 * 查询推荐语种分类信息
 * 查询出所有语种分类信息，并生成缓存
 * @author				李东
 * @date				2015-06-15
 */
function get_bank_cache(){
	$cache_name = 'bank_cache';
	if(!F($cache_name)){
		$result = get_result('bank',array('status'=>0),'',' sort desc'); //查询所有正常状态的分类
		F($cache_name,$result);
	}else{
		$result=F($cache_name);
	}
	return $result;
}
/**
 * 查询行业分类信息
 * 查询出所有行业分类信息，并生成缓存
 * @author				李东
 * @date				2015-06-15
 */
function get_industry_cache(){
	$cache_name = 'industry_cache';
	if(!F($cache_name)){
		$result = get_result('category',array('status'=>1,'type'=>1),'',' sort desc'); //查询所有正常状态的分类
		F($cache_name,$result);
	}else{
		$result=F($cache_name);
	}
	return $result;
}
/**
 * 查询服务分类信息
 * 查询出所有行业分类信息，并生成缓存
 * @author				刘浩
 * @date				2015-06-24
 */
function get_serve_cache(){
	$cache_name = 'serve_cache';
	
	if(!F($cache_name)){
		$result = get_result('category',array('status'=>1,'type'=>2,'pid'=>0,'level'=>1),'',' sort desc'); //查询所有正常状态的分类
		F($cache_name,$result);
	}else{
		$result=F($cache_name);
	}
	return $result;
}
/**
 * 查询业务范围
 * 查询店铺的业务范围
 * @author				刘浩
 * @date				2015-06-24
 */
function get_operate_cache(){
	$cache_name = 'operate_cache';

	if(!F($cache_name)){
		$result = get_result('category',array('status'=>1,'type'=>7),'','sort desc'); //查询所有正常状态的分类
		F($cache_name,$result);
	}else{
		$result=F($cache_name);
	}
	return $result;
}
/**
 * 查询属性分类信息
 * 查询出所有属性分类信息，并生成缓存
 * @author				李东
 * @date				2015-06-15
 */
function get_ability_cache(){
	$cache_name = 'ability_cache';
	if(!F($cache_name)){
		$result = get_result('category',array('status'=>1,'type'=>2),'',' sort desc'); //查询所有正常状态的分类
		F($cache_name,$result);
	}else{
		$result=F($cache_name);
	}
	return $result;
}
/**
*帮助类目和帮助文章关联缓存
*将所有的帮助中心的类目及文章查询出来 生成缓存,如果没有缓存则生成缓存
*@return array 帮助中心数据
*@author               刘浩
*@date				   2015-07-14
**/
function get_help_category_cache(){
	$cach_name = 'help_category_cache';
	if(!F($cach_name)){
		$result = get_result('category',array('status'=>1,'type'=>3),'','',' sort asc');
		F($cach_name,$result);
	}else{
		$result = F($cach_name);
	}
	return $result;
}
/**
 * 地区缓存数据
 * 将所有地区数据读取出来   如果没有缓存则生成缓存
 * @return array 地区数据
 * @author						李东
 * @date						2015-06-16
 */
function get_area_cache(){
	$cache_name='area_data';
	if(!F($cache_name)){
		$cache_result=get_result('area',array('status'=>1),'','sort desc');
		$result=get_cache_data($cache_result,$cache_name);
	}else{
		$result=F($cache_name);
	}
	return $result;
}

/**
 * 获取难度等级
 * @author						李东
 * @date						2015-07-11
 */
function get_grade_cache(){
	$cache_name =  'grade_cache';
	F($cache_name,null);
	if(!F($cache_name)){
		$result=get_result('level',array('status'=>1,'type'=>7),'','id asc');
		F($cache_name,$result);
	}else{
		$result=F($cache_name);
	}
	return $result;
}


/**
 * 将数组类转化成  ID=>Title 模式的数组
 * @param	$array	array			待转换数组
 * @return	array 					转换后数组
 * 
 * @author							李东
 * @date							2015-06-25
 */
function id_and_text($array){
    $id_text = array();
    foreach ($array as $row){
    	$id_text[$row['id']] = $row['title'];
    }
    return $id_text;
}

/**
 * 将ID组成的json转化为英文逗号隔断的中文字符
 * @param array $array				待转换数组
 * @param array $param				转换的参数 格式为 array('需要转换的字段(值为由ID组成的json格式数据)'=>array('要替换的ID'=>'替换ID的文字')
 * @return	array 					转换后数组
 * 
 * @author							李东
 * @date							2015-06-25
 */
function json_to_chars($array,$param=array(''=>array())){
	$temp_array = array();
	foreach ($array as $key => $value){
		$temp_array[$key] = $value;
		foreach ($param as $k=>$val){
			$json_arr = json_decode($value[$k],true);
			$str = '';
			foreach($json_arr as $v){
				$str .= $val[$v].',';
			}
		$temp_array[$key][$k.'_text'] =substr($str, 0,-1);
		}		
	}
	return  $temp_array;

	
}

/**
 * 产品等级获取 
 * 将产品的所有等级获取出来
 * @return array 
 * 
 * @author						李东
 * @date						2015-06-25
 */
function get_product_level_cache(){
	$cache_name = 'product_level_cache';
	if(!F($cache_name)){
		$result = get_result('level',array('status'=>1,'type'=>3),'',' id asc'); //查询所有正常状态的产品等级
		F($cache_name,$result);
	}else{
		$result=F($cache_name);
	}
	return $result;
}
/**
 * 判断是否手机访问
 * @param  $substrs
 * @param  $text
 * @return boolean
 */
function CheckSubstrs($substrs,$text){
	foreach($substrs as $substr){
		if(false!==strpos($text,$substr)){	return true; }
	}
	return false;
}

/**
 * 退款操作
 * 首先判断订单是否已付款
 * 已付款订单才能进行退款处理
 * 退款处理
 * 1.对购买用户的余额账户进行余额增加
 * 2.对店铺拥有者进行扣款
 * 3.对订单状态做修改
 * @param		$id					退款订单ID
 * @param 		$refund_price		退款金额
 * @param		$description		退款备注
 * @return		bool				成功或失败
 *
 * @author						李东
 * @date						2015-06-23
 */

function order_refund($id,$refund_price,$description){
	$sql_arr = array();

	/*查询出订单详情*/
	$order_info = get_info('orders',array('id'=>$id));
	if($order_info['status'] <= 0){
		/* 判断是否已付款 */
		return array('status'=>'0','msg'=>'未付款订单不可退款');
	}elseif($order_info['status'] == 5){
		/* 判断是否已退款 */
		return array('status'=>'0','msg'=>'该订单已经退款成功');
	}
	if($refund_price>$order_info['total_price']){
		return array('status'=>'0','msg'=>'退款金额不能大于订单金额');
	}
	/*查询出店铺信息*/
	$shop_info = get_info(D('ShopView'),array('shop.id'=>$order_info['shop_id']));
	$buyer_id = $order_info['member_id'];/*购买人ID*/
	$seller_id = $shop_info['seller_id'];/*出售人ID*/

	$_POST=array(
			'id'=>$buyer_id,
			'withdrawals' =>$refund_price,
	);
	/* 给客户账户增加余额 */
	$sql_arr[] = "update __PREFIX__member set withdrawals = withdrawals+{$refund_price} where id = {$buyer_id}";
	/* 添加资金记录*/
	$sql_arr[] = "INSERT INTO __PREFIX__money_record (`type`, `frozen`, `member_id`, `money`,`description`, `order_num`, `from_member_id`, `status`) VALUES ('5', '2', '{$buyer_id}', '{$refund_price}', '商家退款', '{$order_info['order_num']}', '{$seller_id}', '3');";
	/* 给商家账户减少余额 */
	$sql_arr[] = "update __PREFIX__member set withdrawals = withdrawals-{$refund_price} where id = {$seller_id}";
	/* 添加资金记录*/
	//$sql_arr[] = "INSERT INTO __PREFIX__money_record (`type`, `frozen`, `member_id`, `money`,`description`, `order_num`, `from_member_id`, `status`) VALUES ('5', '2', '{$seller_id}', -'{$refund_price}', '订单退款', '{$order_info['order_num']}', '{$seller_id}', '3');";
	
	/* 变更订单状态 */
	$sql_arr[] = "update __PREFIX__orders set refund_price={$refund_price},agree_refund=1,status = 5 where id = {$id}";
	/* 将订单操作记录到订单历史表中 */
	$sql_arr[] = "INSERT INTO __PREFIX__order_history (`order_id`,`description`, `order_status`) VALUES ('{$id}', '{$description}', '5')";
	

	$Model = M();
	$Model->startTrans();/*开启事务*/
	
	
	foreach ($sql_arr as $key => $val) {
		$result=$Model->execute($val);
		if(!is_numeric($result)){
			$status=1;
			break;
		}
	}
	/*退款成功之后将剩余尾款支付给商家，并添加商家用户的资金记录*/
	$result = pay_balance_due($id);
	
	if($status==1&&$result){
		$Model->rollback();/*事务回滚*/
		return array('status'=>'0','msg'=>'操作失败');
	}else{
		$Model->commit();/*事务提交*/
		return array('status'=>'1','msg'=>'操作成功');
			
	}
}
/**
 * 把传到API接口中的参数由json格式转换为数组
 * @param string $debug
 * @return array 转换后的结果集
 */
function inputJson(){
	header("content-type:text/html;charset=utf-8");//将提交的数据转换为utf-8编码格式
	//判断是提交数据的方式
	if(IS_GET){
		$postArr = I('get.');//获取浏览器提交个体数据
	}elseif(IS_POST){
		
// 		$inputData = file_get_contents("php://input"); //获取post提交的数据 (php://input可以读取没有处理过的POST数据)
// 		$postArr = json_decode($inputData,true);//将获取到的json数据转为数组
		$postArr = I('post.');
	}

	return $postArr;
}

/**
 * 通过PATH来获取所有上级
 * @param string $table		要查询的表
 * @param string $path		需要切分的path 格式为  -0-1-2-
 * @return array			查询出来的结果数组 所有有数据
 * 
 * @author						李东
 * @date						2015-07-01 
 */

function get_pid_all($table,$path){
	$pid_arr = explode('-', $path);
	$map_arr = array('id'=>array('in'=>$pid_arr));
	$result = get_result($table,$map_arr);
	return  $result;
}


/**
 * 通过PATH来获取所有上级
 * @param string $table		要查询的表
 * @param string $path		需要切分的path 格式为  -0-1-2-
 * @return array			查询出来的结果数组 只有title跟ID
 *
 * @author						李东
 * @date						2015-07-01
 */
function get_pid_title($table,$path){
	$pid_arr = explode('-', $path);
	$map_arr = array('id'=>array('in'=>$pid_arr));
	$result = get_result($table,$map_arr,'title,id');
	return  $result;
}



/**
 * 通过path来获取地址上级信息(缓存中获取)
 * 
 * @param string $path			需要获取的信息path
 * @return array()				返回path中包含的所有ID的信息
 * 
 * @author						李东
 * @date						2015-07-01
 */
function get_area_pid($path){
	$pid_arr = explode('-', $path);
	$area_info = get_area_cache();
	$pid_info = array();
	$i = 0;
	foreach($area_info as $row){
		if(in_array($row['id'],$pid_arr)){
			$pid_info[$i]['id']=$row['id'];
			$pid_info[$i]['title']=$row['title'];
			$i++;
		}
	}
	return $pid_info;
}

/**
 * 补全URL
 * 友情链接判断是否有 http://
 * 有则直接返回链接，没有则在连接前加上 http://
 * 
 * @param string $url			需要处理的url
 * 
 * @author						李东
 * @date						2015-07-01
 */
function completion_url($url){
	$status1 = stristr($url,'http://');
	$status2 = stristr($url,'https://');
	if($status1||$status2){
		$result = $url;
	}else{
		$result = 'http://'.$url;
	}
	return $result;
}

/**
 * 将二维数组的键值转换为子数组的中ID值
 * @param array  $array			待转换的二维数组
 * @return array				键值为数组中ID值的数组
 * 
 * @author						李东
 * @date						2015-07-08
 */
function array_id_key($array){
	$temp_arr = array();
	foreach($array as $val){
		$temp_arr[$val['id']] = $val;
	}
	$array = $temp_arr;
	return $array;
}

/**
 * 将由ID组成的数组转换为 包含ID与title的数组
 * @param array $array			待转换的数组 格式array(
 * 												'0'=>'4',
 * 												'1'=>'7',
 * 												'2'=>'5',
 * 												'key'=>'ID',
 * 											)
 * @param array $param_arr		用于与待转换数组中的ID匹配的数组 格式array(
 * 																'4'=>array(
 * 																		'id'=>'ID',
 * 																		'title'=>'Title',
 * 																	)
 * 															)
 * @return array				键值为数组中ID值的数组
 * 
 * @author						李东
 * @date						2015-07-08
 */
function int_to_arr($array,$param_arr){
	$temp_arr = array();
	foreach($array as $k=>$val){
		if(!empty($param_arr[$val]['title'])){
			$temp_arr[$k]['id'] = $val;
			$temp_arr[$k]['title'] = $param_arr[$val]['title'];
		}
		
	}
	$array = $temp_arr;
	return $array;
}
/**
 * 获取推荐的文章
 * @param			$type	文章类型
 * @param			$limit	显示条数
 * @author			李东
 * @date			2015-07-15
 */
function get_recommend_articles($type = '',$limit=10){
	if($type){
		$map['type']=$type;
	}
	$map['status'] = 1;
	$map['recommend'] = 1;
	
	$order = ' id desc';
	$result = get_result('article',$map,'id,title',$order,$limit);
	return  $result;
}
/**
 * 获取推荐的店铺
 * @param			$type	店铺类型
 * @param			$limit	显示条数
 * @author			李东
 * @date			2015-07-15
 */
function get_recommend_shop($type = '',$limit=5){
	if($type){
		$map['type']=$type;
	}
	$map['status'] = 1;
	$map['recommend'] = 1;
	
	$order = ' id desc';
	$map['member.id']=array('gt',0);
	$result = get_result(D('ShopView'),$map,'',$order,$limit);
	foreach ($result as $k=> $row){foreach ($result as $k=> $row){
		
		/*将店铺服务时间转为小时为单位，字数转为万字为单位*/
		$result[$k]['total_translate_time_w'] = sprintf('%.1f',$row['total_translate_time']/3600);
		$result[$k]['total_translate_num_w'] = sprintf('%.1f',$row['total_translate_num']/10000);
	}
		
		/*将店铺服务时间转为小时为单位，字数转为万字为单位*/
		$result[$k]['total_translate_time_w'] = sprintf('%.1f',$row['total_translate_time']/3600);
		$result[$k]['total_translate_num_w'] = sprintf('%.1f',$row['total_translate_num']/10000);
	}
	return  $result;
}

/**
 * 通过用户ID获取用户
 * @param int   $member_id						用户ID
 * @return array								返回的地址结果集
 */
function get_address_info($member_id){
	$map['member_id']=$member_id;
	$map['status'] = 1;
	$result = get_result('address',$map);
	return  $result;
}

/**
 * 获取最新的店铺
 * @param			$type	店铺类型
 * @param			$limit	显示条数
 * @author			李东
 * @date			2015-07-15
 */
function get_new_shop($type = '',$limit=5){
	if($type){
		$map['type']=$type;
	}
/* 	$map['status'] = 1;
	$order = ' id desc';
	$result = get_result('shop',$map,'',$order,$limit); */
	/* $sql = "SELECT * FROM sr_shop,sr_products WHERE sr_shop.id=sr_products.shop_id AND sr_shop.status=1 order by sr_shop.add_time desc limit 5"; */
// 	$result=query_sql($sql);
//获取最新的入驻店铺，且该店铺中要包含有产品
	/* $sql = "SELECT distinct(sr_shop.id),sr_shop.title,sr_shop.translate_year,sr_shop.good_at FROM sr_shop,sr_products WHERE sr_shop.id=sr_products.shop_id AND sr_shop.status=1 order by sr_shop.add_time desc limit 5"; */
	$sql = "SELECT distinct(sr_shop.id),sr_shop.title,sr_shop.translate_year,sr_shop.good_at FROM sr_shop,sr_products WHERE sr_shop.id=sr_products.shop_id AND sr_shop.status=1 AND sr_shop.have_product=1 order by sr_shop.add_time desc limit 5";
	$Model = M();
	$result = $Model->query($sql);
	return  $result;
}


/**
 * ID 左侧补零
 * @param int $id					需要补零的ID
 * @param int $num					补零之后需要的位数 默认为6
 * @return string					返回补零后的字符串
 * 
 * @author							李东
 */
function id_to_encode($id,$num=6){
	$num=str_pad($id,$num,"0",STR_PAD_LEFT);
	return $num;
}

/**
 * 获取订单号
 * @param int $shop_id						被下单店铺ID
 * @param int $member_id					下单用户ID
 * @return string 						返回订单号
 * 
 * @author							李东
 */
function get_order_num($shop_id,$member_id){
	$order_num = date('Ymd',time()).id_to_encode($shop_id).id_to_encode($member_id).rand(1000, 9999);
	return $order_num;
}

/**
 * 自动确认订单
 * 将用户未确认的商家已经翻译完成的并且超过系统设置确认时间的订单支付系统预定的百分比金额
 * 1.将商家已上传，用户未确认，已超过系统设置时间的的订单搜索出来
 *
 * 2.将所有订单查询出来后，循环获取订单金额，并且从平台账户将 比例金额转入商家账户
 * 	①获取每个订单参与的店铺，
 * 	②获取店铺所属人，
 * 	③将金额更新到每个店铺的所属人账户
 * 	④
 * 3.生成金额流动记录
 * 
 * @param		$member_id		登录用户ID(参与订单)
 * @param		$shop_id		店铺ID(参与订单)
 * @author						李东
 * @date						2015-07-14
 */
 function check_complete($member_id=0,$shop_id=0){
	$temp = $_POST;
	/*设置查询条件*/
	$time_limit = C('PAY_TIME');												/*获取系统定义的提交完成稿之后等待时间长度/单位为(天)*/

	$deadline = date('Y-m-d H:i:s',(time()-$time_limit*24*3600));				/*截止时间*/
	$precent = C('EARLY_PERCENT')/100;											/*获取系统定义的比例,并将其转换为百分比*/

	if(intval($member_id)>0){
		/*用户相关的的订单*/
		$map['member_id']=$member_id;
	}
	if(intval($shop_id)>0){
		/*查询店铺相关的订单*/
		$map['shop_id']=$shop_id;
	}
	$map['upload_time'] = array('lt',$deadline);								/*判断等待截止时间是否已经超过*/
	$map['status'] = 6;															/*判断商家上传完成稿用户未确认的订单*/
	
	/*查询订单*/
	$pending_result = get_result('orders',$map);
	foreach($pending_result as $row){
		/* 开启事务 */
		$Model = M();
		$Model->startTrans();
		/* 查询订单涉及的店铺所属的用户信息 */
		$shop_member_info = get_info(D('MemberShopView'),array('shop_id'=>$row['shop_id']));
		/*金额从平台账户转入商家账户*/
		$platform_info = get_info('member',array('id'=>C('PLATFORM_ID')));
		$_POST=array(
				'id'=>trim(C('PLATFORM_ID')),
				'withdrawals'=>$platform_info['withdrawals']-$row['total_price']*$precent,
		);
		/*金额从平台账户转出*/
		$res1 = update_data('member');
		/*金额转入商家账户*/
		$_POST = array(
				'id'=>$shop_member_info['member_id'],
				'withdrawals'=>$shop_member_info['withdrawals']+$row['total_price']*$precent,
		);
		$res = update_data('member');
		/*将订单状态更新为已确认*/
		$_POST = array(
				'id'=>$row['id'],
				'status'=>'7',
				'step'=>5,
				'confirm_time'=>date('Y-m-d H:i:s'),//@liuqiao增加确认时间
		);
		$res2 = update_data('orders');
		/*将订单变更记录记录到订单历史记录表*/
		$_POST = array(
				'order_id'=>$row['id'],
				'order_status'=>'7',
				'description'=>'系统自动确认订单',
		);
		$res3 = update_data('order_history');
		/*更新店铺服务字数等数据*/
		$res4 = update_shop_data($row);
		
		/*添加商家用户的资金记录*/
		$res4 = add_money_record($row['order_num'], trim(C('PLATFORM_ID')), $shop_member_info['member_id'], $row['total_price']*$precent, 4, 2, $row['pay_type']); 
		if(is_numeric($res)&&is_numeric($res1)&&is_numeric($res2)&&is_numeric($res3)&&is_numeric($res4)){
			/*事务提交*/
			$Model->commit();
			F('site_index',null);//清除网站指数缓存
		}else{
			/*事务回滚*/
			$Model->rollback();
		}
	}
	$_POST = $temp;
}


/**
 * 添加资金记录商家用户的尾款资金记录
 * 
 * @param string $order_num												订单号 
 * @param int $from_member_id                                           资金来源用户ID
 * @param int $to_member_id                                             获得金额的会员ID
 * @param double $money                                                 金额 
 * @param int $type                                                     1表示充值 2表示消费 3提现 4订单收入 
 * @param int $frozen                                                   0 余额账户 1 冻结账户 2可提现账户3在线购买 
 * @param int $recharge_type                                            支付方式:0余额支付,1支付宝支付,2微信支付
 * @param int $cards_id                                                 卡包表中信息的ID(提现收款账户信息)
 * @return mixed														返回执行状态结果
 */
function add_money_record($order_num,$from_member_id,$to_member_id,$money,$type,$frozen,$recharge_type=0,$cards_id=0){
	$_POST = array(
		'type'=>$type,											/* 1表示充值 2表示消费3提现 4订单收入 */
		'frozen'=>$frozen,										/* 0 余额账户 1 冻结账户 2可提现账户3在线购买 */
		'member_id'=>$to_member_id,								/* 获得金额的会员ID */
		'cards_id'=>$cards_id,									/* 卡包表中信息的ID(提现收款账户信息) */
		'money'=>$money,										/* 金额 */
		'order_num'=>$order_num,								/* 订单号 */
		'recharge_type'=>$recharge_type,						/* 0表示支付宝  1微信支付 */
		'from_member_id'=>$from_member_id,						/* 资金来源用户ID*/
		'description'=>'尾款支付',								/* 资金记录描述*/
		'status'=>'3'
	);
	$result = update_data('money_record');

	return $result;
} 

/**
 * 支付尾款
 * ①查询订单信息
 * ②查询订单相关店铺所属用户信息
 * ③查询平台账户信息
 * ④从平台账户扣除尾款金额
 * ⑤将尾款转入商家账户
 * ⑥更新订单状态
 * ⑦记录订单状态变更
 * ⑧添加商家资金记录
 * ⑨
 * ⑩
 * @param int $order_id					订单
 * @return boolean						返回执行状态
 * @author								李东
 * @date								2015-08-07
 */
function pay_balance_due($order_id){
	$surplus_precent = (1-C('EARLY_PERCENT')/100);											/*获取系统定义的比例,并将其转换为百分比,计算剩余百分比*/
	
	$order_info = get_info('orders',array('id'=>$order_id));
	$status = false;
	if($order_info){
		/*判断信息是否存在*/
		$shop_member_info = get_info(D('MemberShopView'),array('shop_id'=>$order_info['shop_id']));
		
		/* 开启事务 */
		$Model = M();
		$Model->startTrans();
		/*金额从平台账户转入商家账户*/
		$platform_info = get_info('member',array('id'=>C('PLATFORM_ID')));
		$_POST=array(
				'id'=>trim(C('PLATFORM_ID')),
				'withdrawals'=>$platform_info['withdrawals']-$order_info['total_price']*$surplus_precent,
		);
		/*金额从平台账户转出*/
		$res = update_data('member');

		/*金额转入商家账户*/
		$_POST = array(
				'id'=>$shop_member_info['member_id'],
				'withdrawals'=>$shop_member_info['withdrawals']+$order_info['total_price']*$surplus_precent,
		);
		$res1 = update_data('member');

		/*将订单状态更新为已完成,已支付尾款*/
		$_POST = array(
				'id'=>$order_info['id'],
				'status'=>'3',
				'is_pay_due'=>'1',
		);
		$res2 = update_data('orders');

		/*将订单变更记录记录到订单历史记录表*/
		$_POST = array(
				'order_id'=>$order_info['id'],
				'order_status'=>'3',
				'description'=>'确认支付尾款',
		);
		$res3 = update_data('order_history');

		/*添加商家用户的资金记录*/
		$res4 = add_money_record($order_info['order_num'], trim(C('PLATFORM_ID')), $shop_member_info['member_id'], $order_info['total_price']*$surplus_precent, 4, 2, $order_info['pay_type']); 

		if(is_numeric($res)&&is_numeric($res1)&&is_numeric($res2)&&is_numeric($res3)&&is_numeric($res4)){
			/*事务提交*/
			$Model->commit();
			$status = true;
		}else{
			/*事务回滚*/
			$Model->rollback();
			$status = false;
		}
	}
	return $status;	
}
/**
 * 确认订单时，店铺数据更新
 * @param array	 $order_info		将本次订单的所有信息（数据库表中的完整信息）
 * @return	mixed 返回更新返回值
 * @author						李东
 * @date						2015-07-24
 */
function update_shop_data($order_info){

	/*查询出店铺信息*/
	$shop_info = get_info('shop',array('id'=>$order_info['shop_id']));
	// 		print_r($shop_info);exit;
	/*判断产品类型，分别统计对应的数量或时长*/
	if($order_info['product_type']==36){
		/*如果是笔译计算字数* 计量单位 /字	*/
		$words = $order_info['qty']*1000;
	}elseif($order_info['product_type']==37){
		/*如果是口译计算时长* 计量单位 /秒	*/
		$times = $order_info['qty']*3600;
	}elseif($order_info['product_type']==56){
		/*如果是音频翻译计算时长* 计量单位 /秒	*/
		$times = $order_info['qty']*60;
	}
	/*店铺总服务次数加一*/
	$service_times = $shop_info['service_times']+1;

	$_POST = array(
			'id'=>$order_info['shop_id'],
			'service_times'=>$service_times,
			'total_translate_time'=>$shop_info['total_translate_time']+intval($times),
			'total_translate_num'=>$shop_info['total_translate_num']+intval($words),
	);
	$res = update_data('shop');
	return $res;
}

/**
 * 获取常见问题
 * 
 * @param number $limit			查询条数
 * @return 查询所得结果集
 * @author						李东
 * @date						2015-07-24
 */
function get_common_problem($limit=9){
	$map['type'] = 'help';
	$map['status'] = '1';
	$field = ' title,id ';
	$order = 'view desc';
	$result = get_result('article',$map,$field,$order,$limit);
	return $result;
}

/**
 * 获取成功分享、过往经历
 * @param int $shop_id						店铺Id
 * @param int $type							需要获取的信息类型（1/过往经历，2/成功分享）
 * @return array
 * 
 * @author						李东
 * @date						2015-07-24
 */
function get_experience($shop_id,$type){
	if(!$shop_id){
		return array('status'=>'0','msg'=>'参数错误');
	}
	if(!$type){
		return array('status'=>'0','msg'=>'参数错误');
	}
	$map['shop_id'] = $shop_id;
	$map['type']=$type;
	$shop_info = get_result('shop_undergo',$map,'','start_time desc');
	return  array('status'=>'1','msg'=>'获取成功','shop_info'=>$shop_info);
}

/**
 * 获取热销商品
 * @param int $shop_id						店铺ID
 * @return array 							查询处理后的结果集
 * @author									李东
 * @date									2015-07-25
 */
function get_hot_product($shop_id){
	$map['shop_id']=$shop_id;
	$map['status'] = 1;
	$limit = 9;
	$order = ' id desc ';
	$result = get_result('products',$map,'',$order,$limit);
	
	/*获取格式为 id=>title 的语言分类数组*/
	$language_text = id_and_text(get_language_cache());
	/*获取格式为 id=>title 的等级分类数组*/
	$level_text = id_and_text(get_product_level_cache());
	/*获取格式为 id=>title 的产品分类数组*/
	$product_type=id_and_text(list_to_tree(get_ability_cache()));/*获取缓存的分类属性转换为树状*/
	
	$result = int_to_string($result,array('level_id'=>$level_text,"type"=>$product_type,"language_id"=>$language_text,"to_language_id"=>$language_text,));
	/*将json格式ID转为逗号分隔的Title*/
	
	/*获取格式为 id=>title 的领域分类数组*/
	$industry_text = id_and_text(get_industry_cache());
	/*获取格式为 id=>title 的属性分类数组*/
	$ability_text = id_and_text(get_ability_cache());
	/*将Json中所有ID转换成文字字符串*/
	$result = json_to_chars($result,array('industry_id'=>$industry_text,'ability_id'=>$ability_text,));
	
	return $result;
}

/**
 * 获取网站指数
 * 
 * 返回的统计数量，
 * @author						李东
 * @date						2015-07-27
 */
function get_site_index(){
	$cache_name = 'site_index';
	if(!F($cache_name)){
		/*统计orders表中的字段*/
		$sql1 = 'SELECT SUM(1) as demand,SUM(total_price) as price_sum FROM __ORDERS__ WHERE `status`>1';
		$count1 = M()->query($sql1);
		/*统计shop表中数据已入驻商家*/
		$sql2 = 'SELECT COUNT(id) as total_num FROM sr_shop WHERE `status`=1;';
		$count2 = M()->query($sql2);
		$result = array_merge($count1[0],$count2[0]);
		F($cache_name,$result);
	}else{
		$result = F($cache_name);
	}
	
	return  $result;
}


/**
 * 新建文件并写入内容，
 * 功能说明，
 * 		判断一个文件是否存在，不存在则创建，存在则创建文件名为定义的文件名后面加1，
 * 		如(文件名为：file，如果该文件存在，则创建file1,如果file1存在则创建file2...以此类推)
 * @param string $a				需要写入文件中的内容
 * @param string $b				文件名(不带后缀)
 * @param int $c				创建的文件后面的数字后缀从哪一个数字开始
 * @param string $d				文件的后缀名(不需要带"."，默认为'txt'后缀)
 * @author						李东
 * @date						2015-06-03
 */
function open_file($a,$b,$c='',$d='txt'){
	@$open = fopen($b.$c.'.'.$d,'r');
	if($open){
		if(intval($c)){
			$c++;
		}else{
			$c=1;
		}
		open_file($a,$b,$c,$d);
	}else{
		$file=fopen($b.$c.'.'.$d,'w');
		$str = fwrite($file,"<?php \n".var_export($a, TRUE)."\n ?>");
		fclose($file);
	}
}
/**
 * 通过文件存储地址获取文件的文件名与文件类型
 * @param array $files					需要获取的地址数组(一维数组)
 * @param string $field					存储文件地址的字段名(数组键值)
 * @return array						处理后的数组
 */
function get_file_type($files,$field='save_path'){
	foreach($files as $key=>$rows){
		$file_arr = explode('/',$rows[$field]);
		$file_name_full = end($file_arr);
		$files[$key]['filetype'] = $file_type = end(explode('.', $file_name_full));
		$files[$key]['filename'] = substr($file_name_full,0,strlen($file_name_full)-strlen($file_type)-1);
	}
	return $files;
}

/**
 * 通过店铺ID获取评论统计
 * @param int $shop_id				店铺ID
 * @return array					数组格式 //Array ( [good_count] => 11/*好评数量/ [medium_count] => 22/*中评数量/ [bad_count] => 56 /*差评数量/)
 * 
 * @author							李东
 * @date							2015-08-02	
 */
function get_shops_comment_count($shop_id){
	$cache_name='shop_comment_count_'.$shop_id;
	if(!F($cache_name)){
		$count_arr = M()->query("SELECT type,count(*) as times FROM __COMMENT__ where shop_id = ".$shop_id." group  BY type");
		$result=array();
		foreach ($count_arr as $k=>$row){
			if($row['type'] == 1){
				$result['good_count'] = $row['times'];
			}elseif($row['type'] == 2){
				$result['medium_count'] = $row['times'];
			}elseif($row['type'] == 3){
				$result['bad_count'] = $row['times'];
			}
		}
		F($cache_name,$result);
	}else{
		$result = F($cache_name);
	}
	return $result;
}

/**
 * 获取地址详情
 *
 * @param int $id
 * @return array					处理后的地址信息
 */
function get_address_detail($id){
	$info= get_info('address',array('id'=>$id));
	/*获取地址信息，并将地址ID转换成文字形式*/
	$invoice_address = get_area_pid($info['area_path']);
	$address_text = '';
	foreach($invoice_address as $row){
		$address_text .=$row['title'].' ';
	}
	$info['area_text']=$address_text.$info['detailed_address'];
	return $info;
}

/**
 * 获取所有的主题缓存
 * 
 * @author								李东
 * @date								2015-08-03
 * @return array						所有主题数据
 */
function get_themes_cache(){
	$cache_name = 'themes_cache';
	if(!F($cache_name)){
		$map['status'] = 1; 
		$order = ' sort desc,id asc';
		$result = get_result('theme',$map,$field='',$order='',$limit=0,$group='',$having='');
		F($cache_name,$result);
	}else{
		$result = F($cache_name);
	}
	return $result;
}
/**
 * 发送订单消息
 * 
 * @param int $member_id						收信人id
 * @param int $order_id							订单ID
 * @param string $order_num						订单号
 * @return mixed 								数据更新返回值
 * @author										李东
 * @date										2015-08-03
 */
function order_msg_send($member_id,$order_id,$order_num,$shop_id){
	$content = '您的订单<a href="'.U('/User/Myorder/detail',array('id'=>$order_id)).'" backend_url="'.U('/Backend/Order/Order/detail',array('id'=>$order_id)).'">'.$order_num.'</a>已经生成，点击<a href="'.U('/User/Myorder/detail',array('id'=>$order_id)).'" backend_url="'.U('/Backend/Order/Order/detail',array('id'=>$order_id)).'">查看详情</a>';
	$_POST = array(
			'from_member_id'=>0,
			'to_member_id'=>$member_id,
			'content'=>$content,
			'title'=>'下单通知',
			'type'=>2
	);
	$result = update_data('message');
	/*@liuqiao 重置消息session*/
	if(is_numeric($result)){
		session('news_num',get_news_recode($member_id));
	}
	$shop_info = get_info('shop',array('id'=>$shop_id));
	$content = '您有新的订单，点击<a href="'.U('/User/Order/detail',array('id'=>$order_id)).'" backend_url="'.U('/Backend/Order/Order/detail',array('id'=>$order_id)).'">查看详情</a>';
	$_POST = array(
			'from_member_id'=>0,
			'to_member_id'=>$shop_info['member_id'],
			'content'=>$content,
			'title'=>'订单通知',
			'type'=>2
	);
	$result2 = update_data('message');
	/*@liuqiao 重置消息session*/
	if(is_numeric($result2)){
		session('news_num',get_news_recode($shop_info['member_id']));
	}
	if(is_numeric($result)&&is_numeric($result2)){
		$result3 = 1;
	}else{
		$result3 = '订单消息未发送成功';
	}
	return $result3;
}

/**
 * 平台资金记录
 * 记录每次变动的详细信息
 * 记录每天出入金额统计
 * @param int $member_id									资金的来源用户ID
 * @param double $price										入账金额
 * @param int $type											入账方式:1表示充值 2在线购买3提现
 * @param string $order_num									订单号
 * @param int $recharge_type								用户支付方式:1表示支付宝  2微信支付
 * 
 * @return boolean											 两次插入操作的执行返回结果
 */
function platform_funds($member_id,$price,$type,$order_num,$recharge_type){
	$temp=$_POST;
	/*资金详情表添加记录*/
	$_POST = array(
			'type'=>$type,										/*入账方式:1表示充值 2在线购买3提现*/
			'money'=>$price,									/*入账金额*/
			'order_num'=>$order_num,							/*订单号*/
			'from_member_id'=>$member_id,						/*资金的来源用户ID*/
			'recharge_type'=>$recharge_type,					/*用户支付方式:1表示支付宝  2微信支付*/
			'status'=>'1',										/*状态：-1删除,0禁用,1正常*/
	);
	$res = update_data('funds_detail');
	$data_short = date('Ymd');
	$plat_info = get_info('funds_counts',array('today_date'=>$data_short));
// 	if($plat_info){
// 		/*判断当天的数据是否存在存在则累计统计，不存在则添加数据*/
// 		$_POST = array(
// 				'id'=>$plat_info['id'],
// 				'count_funds'=>$plat_info['count_funds']+$price,	/*平台每天资金的统计*/
// 				'today_date'=>$data_short,							/*当天日期：格式20150707*/
// 				'status'=>'1',
// 				'update_time'=>date('Y-m-d H:i:s'),
// 		);
// 		M('funds_counts')->where(array('today_date'=>$data_short))->save($_POST);
// 	}else{
	$_POST = array(
			'id'=>$plat_info['id'],
			'count_funds'=>$plat_info['count_funds']+$price,	/*平台每天资金的统计*/
			'today_date'=>$data_short,							/*当天日期：格式20150707*/
			'status'=>'1',
			'update_time'=>date('Y-m-d H:i:s'),
	);
	$res2 = update_data('funds_counts');
// 	}
	$status = false;
	if(is_numeric($res)&&is_numeric($res2)){
		$status = true;
	}else{
		$status = false;
	}
	$_POST=$temp;
	return $status;
		
}


/*
 * 获取地区分类信息
 * @author  龚双喜
 * @date    2015-07-25
 * */
function getAreaInfo($pid=0){

	$Model = "area";
	$map=array(
		"pid" =>$pid,
		"status" =>1
	);
	$area_info=get_result($Model,$map);

	return $area_info;

}

/*
 * 获取用户头像
 * $id  用户id  默认为登录用户ID
 * $type 头像大小 默认为big，small middle
 */
function getAvatar($id='',$type='big'){
	if(intval($id)<=0){
		$id=session('home_member_id');
	}
	$avatar='icon/'.$id.'_avatar_'.$type.'.jpg';
	if(!file_exists($avatar)){
		$avatar='icon/'.$id.'_avatar_'.$type.'.png';
	}
	if(!file_exists($avatar)){
		$avatar='icon/'.$id.'_avatar_'.$type.'.gif';
	}
	if(!file_exists($avatar)){
		$avatar='Public/Home/img/sc_template/onerror_pic.png';
	}
	return __ROOT__.'/'.$avatar;
}

/*
 * 用户收藏店铺或商品
 * $id  用户id
 * $goods_id	店铺或商品id
 * $type 收藏类型：1商品，2店铺
 */
function getCollection($member_id,$collect_id,$type){
	$rules = array(
			array('collect_id','','该店铺已收藏过，收藏失败！',0,'unique',1),
			);
	$_POST = array(
			'member_id'=>$member_id,
			'collect_id'=>$collect_id,
			'type'=>$type,
			'status'=>1
	);
	$collection_data = update_data('collect',$rules);
	return $collection_data;
}

/*
 * 视频截图
 * $input  视频路径
 * $output 视频截图生成路径
 * $fromurasec 截取的秒数
 * $ffmpegpath 插件存放路径
 * @author 龚双喜
 * @date   2015-07-31
 * */
function makeVideoImage($input,$output,$fromdurasec="01",$ffmpegpath=""){
	if(empty($ffmpegpath)){
	  //插件路径	
	  $ffmpegpath=$_SERVER["DOCUMENT_ROOT"].__ROOT__."/Public/Plugins/ffmpeg/ffmpeg.exe";
	}
	$command = "$ffmpegpath -i $input -an -ss 00:00:$fromdurasec -r 1 -vframes 1 -f mjpeg -y $output";
	@exec($command, $ret);
	if(is_file($output)){
		return true;
	}else{
		return false;
	}	
}
/*
 * 生成店铺日程表
 * @author 龚双喜
 * @date   2015-08-01
 * */
	function createSchedule($shop_id){
		$i=7;//一周7天//
		for($i=1;$i<=7;$i++){
			unset($_POST);
			$j=3;//上午1，下午2，晚上3
			for($j=1;$j<=3;$j++){
				$_POST["shop_id"]=$shop_id;
				$_POST["type"]=3;
				$_POST['week']=$i;
				$_POST['time']=$j;
				update_data("shop_time");
			}
		}	
	}
	/*
 * 积分
 *1.用户在买入交易成功的时候就会生成积分
 *2.假设100元可以换20积分
 *3.用户可以用积分兑换商品
 *4.如果一个积分可以兑换1元钱
 *5.首先获得交易额（积分需要四舍五入？）
 *6.跟据金额换算成积分。
 *7.怎么换算比例。除数怎么来？
 *8.注册送一百积分
 * @author liuqiao
 * @date   2015-08-01
 * */
	
function  get_coin_points($total_prices,$member_id){
	$temp = $_POST;
		//购买商品换积分
	if(empty($total_prices)&&!empty($member_id)){//用户登入时候送十个积分《没有调用该方法》
		$_POST=array(
			'id'=>$member_id,
			'integration'=>10,
		);
		$results=update_data('member');//更新数据库
	}else{
		$total_prices=$total_prices;//传入消费金额
		$coin = get_info('config',array('name'=>'POINTS'),array('value'));
		$coin_rule = $coin['value'];
		$add_points=intval($total_prices/$coin_rule);//计算积分
		$total_points=get_info('member',array('id'=>$member_id),array('id','integration'));//查询之前积分		
		$total_points=$total_points['integration'];//计算积分
		$_POST=array(
			'id'=>$member_id,
			'integration'=>$add_points+$total_points,//计算总积分
		);
		$results=update_data('member');//更新数据库
		session('points',$add_points+$total_points);
	}
	$_POST = $temp;
	return $results;
}

/*
 * 自动验证验证数组
 * @author 龚双喜
 * @date   2015-08-01
 * */
function checkArray($arr){
	if(!empty($arr) and is_array($arr)){
		return true;
	}else{
		return false;
	}
	
}
/*
 * 多图上传
 * $picture_ids 临时图片ID
 * $folder 上传目录
 * $table 图片保存数据表
 * $data_array 二维数组 
 * 示例: $picture_ids1=>array("字段1"=>"字段1对应的值","字段2"=>"字段2对应的值"),
 *      $picture_ids2=>array("字段1"=>"字段1对应的值","字段2"=>"字段2对应的值")
 * $image_field  文件移动后保存的字段名 
 * return array   result数据更新返回值数组，success数据更新成功的条数
 * @author 龚双喜
 * @date   2015-08-03    
 * */
function multi_file_uploads($picture_ids,$folder,$table,$data_array,$image_field='image'){
	$success=0;//数据更新成功个数
	if($picture_ids!=''){
		$folder_path=mk_dir($folder);
		if($folder_path==true){
			$folder_path=$folder;
		}
		if(is_array($picture_ids)){
			$picture_ids=implode(',', $picture_ids);
		}
		$picture_ids=addslashes($picture_ids);

		if($picture_ids==''){
			$picture_ids='0';
		}
		$result=M("file")->where(array('id'=>array('in',$picture_ids)))->field("id,save_path")->select();
		$msg='';
		foreach($result as $row){
			$path=ltrim($row['save_path'],'/');
			$file_name=basename($path);
			$new_path=$folder_path.'/'.$file_name;
			copy($path,$new_path);
			@unlink($path);
			$_POST=array();
			//$_POST['http_host']=$_SERVER['HTTP_HOST'];
			$_POST[$image_field]=$new_path;
			foreach($data_array[$row["id"]] as $key=>$value){
			    $_POST[$key]=$value;
			}
			$result[$row["id"]]=update_data($table);
			if(is_numeric($result[$row["id"]])){
				$success++;
			}
		}
		@delete_data("file",array('id'=>array('in',$picture_ids)));
		$data_info[$result]=$result;
		$data_info["success"]=$success;
		return $data_info;
	}else{
		return false;
	}
}
/*敏感词
*@刘巧
*2015年8月5
*/
function get_word_search($contents){
	$content=$contents;
	$keyword=get_result('level',array('type'=>6,'status'=>array('gt',-1)),array('title'));//定义敏感词
	foreach($keyword as $k=>$v){
		$keyword[$k]=$v['title'];
	}
	foreach($keyword as $k=>$v) {    //根据数组元素数量执行for循环  
		if (substr_count ( $content, $v ) > 0) {  //应用substr_count检测文章的标题和内容中是否包含敏感词  
			$content=str_replace($v,'***',$content);
		}  
	} 
    return $content;        //返回变量值，根据变量值判断是否存在敏感词  
}

/*@liuqiao  站内信刷新消息*/
function get_news_recode($member_id){
	$news_sysnum_results=get_result('sysmsg_status',array('member_id'=>$member_id,'status'=>0));
	$news_sysnum_results=count($news_sysnum_results);
	$news_num_results=get_result('message',array('to_member_id'=>$member_id,'to_status'=>0));
	if(!empty($news_num_results)){
		$news_num=count($news_num_results)+$news_sysnum_results;
	}
	else{
		$news_num=0+$news_sysnum_results;
	}
	
	return $news_num;
}
/*
*@liuqiao 
*短信功能
*date :2015 8月9号
*/
 function send_code($content,$account){
	if(preg_match('/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/', $account)){
		$status=sms($account, $content);
	}else{
		$status=-1;
	}
	return $status;
}
