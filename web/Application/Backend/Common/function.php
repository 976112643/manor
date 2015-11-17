<?php 
	/*
	 * 后台操作日志
	 * */
	function action_log($table_name,$table_id=0,$action_filed='id,title'){
		$url=strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
		if(is_numeric($table_id)){
			$map['id']=$table_id;
		}else{
			$map['id']=array('in',$table_id);
		}
		
		$result=get_result($table_name, $map,$action_filed);
		
		$_POST=array(
			'member_id'=>session('member_id'),
			'username'=>session('username'),
			'url'=>$url,
			'table_name'=>$table_name,
			'table_id'=>$table_id,
			'description'=>json_encode($result),
			'ip'=>get_client_ip(),
		);
		update_data('action_log');
	} 
?>