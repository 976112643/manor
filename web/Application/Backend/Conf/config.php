<?php
return array(
	//'配置项'=>'配置值'
	'CONTROLLER_LEVEL'		=> 2,//设置2级目录的控制器层
	'SESSION_PREFIX'=>'admin_',
		
	/* 模板相关配置 */
	'TMPL_PARSE_STRING' => array(
		'__STATIC__'	=> __ROOT__ . '/Public/Static',
		'__PLUGIN__'	=> __ROOT__ . '/Public/Plugins',
		'__IMG__'		=> __ROOT__ . '/Public/Backend/img',
		'__CSS__'		=> __ROOT__ . '/Public/Backend/css',
		'__JS__'		=> __ROOT__ . '/Public/Backend/js',
	),

	//配置类型
	'config_type'=>array(
		'text'		=>'文本框',
		'textarea'	=>'文本域',
		'radio'		=>'单选框',
		'checkbox'	=>'复选框',
		'select'	=>'下拉框',
		'editor'	=>'编辑器',
		'image'		=>'图片'
	),
	
	'TMPL_ACTION_ERROR'=>THINK_PATH . 'Tpl/dispatch_jump.tpl',

	'TMPL_ACTION_SUCCESS'=>THINK_PATH . 'Tpl/dispatch_jump.tpl',
	//'TMPL_ACTION_ERROR'     =>  'Backend/View/Base/Public/error.html', // 默认错误跳转对应的模板文件
	//'TMPL_ACTION_SUCCESS'   =>  'Backend/View/Base/Public/success.html', // 默认成功跳转对应的模板文件
	//'TMPL_EXCEPTION_FILE'   =>  'Backend/View/Base/Public/exception.html',// 异常页面的模板文件
);