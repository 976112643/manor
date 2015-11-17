<?php
namespace Common\Widget;
use Think\Controller;
class ImgWidget extends Controller {
	public function index($config,$id){
		$default_config=array(
			'index'		=>1,
			'table'		=>'',
			'table_id'	=>'id',
			'name'		=>'',
			'multi'		=>'false',
			'val_key'	=>'image',
			'btn_val'	=>'上传图片',
			'tpl'		=>'index'
		);
		$last_config=array_merge($default_config,$config);
		$data['config']=$last_config;
		if($last_config['table']==''){
			$this->error("请设置参数table");
		}
		if($last_config['name']==''){
			$this->error("请设置参数name");
		}
		if($config['multi']!="true"){
			$info=get_info($last_config['table'],array($last_config['table_id']=>$id),$last_config['val_key']);
			$first=array_keys($info);
			$data['info']=$info[$first[0]];
		}
		$data['id']=$id;
		$this->assign($data);
		$this->display(T("Common@Img/".$last_config['tpl']));
	}
}
