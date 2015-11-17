<?php
return array(


		//数据库配置信息
	'DB_TYPE'   => 'mysqli', // 数据库类型
	'DB_NAME'   => 'nbfy', // 数据库名
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => 'sr_', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8', // 字符集
	'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增


	//'DB_HOST'   => '59.175.146.174', // 服务器地址
		//'DB_USER'   => 'nbfy', // 用户名
    //'DB_PWD'    => 'nGsyL932ZaHJJSAC', // 密码 
	'DB_HOST'   => 'localhost', // 服务器地址
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => '', // 密码 


	//'配置项'=>'配置值'
	"IS_MOBILE"=>0,//定义判断是否是手机访问
	"APP_KEY"=>'13b965a497aef546f2139003444ee8f1', //app接口访问密钥

	// 加载扩展配置文件
	'LOAD_EXT_CONFIG'=>'db',
	'SESSION_PREFIX'=>'front_',
	
	/*支付接口参数配置*/
	'payment' => array(
			/*支付宝配置*/
			'alipay' => array(
					'email' => 'nbfanyi@nbfanyi.com',									/* 收款账号邮箱*/ 
					'key' => '9mcnd4sjdr9ftjuk6irms4tfb83hr9sm',						/* 加密key，开通支付宝账户后给予*/
					'partner' => '2088911838803030',									/* 合作者ID，支付宝有该配置，开通易宝账户后给予*/
			),
			/*微信支付配置*/
			'wxpay'=>array(
					'appid' => 'wx43f262f06f91762e',													/*这里填开户邮件中的（公众账号APPID或者应用APPID）*/
					'mchid' => '1247341501',															/*这里填开户邮件中的商户号*/
					'key' => 'da19130bf58465cbf91a92fe0ab8a36a',										/*这里请使用商户平台登录账户和密码登录http://pay.weixin.qq.com 平台设置的“API密钥”*/
					'appsecret' => '4d78bf4d7647bb913018fed11645dc89',									/*改参数在JSAPI支付（open平台账户不能进行JSAPI支付）的时候需要用来获取用户openid，可使用APPID对应的公众平台登录http://mp.weixin.qq.com 的开发者中心获取AppSecret。*/
			)
	),
	/* 模板相关配置 */
	'TMPL_PARSE_STRING' => array(
		'__STATIC__'	=> __ROOT__ . '/Public/Static',
		'__IMG__'		=> __ROOT__ . '/Public/Home/img',
		'__CSS__'		=> __ROOT__ . '/Public/Home/css',
		'__JS__'		=> __ROOT__ . '/Public/Home/js',
		'__PLUGIN__'	=> __ROOT__ . '/Public/Plugins',
		'__UPLOADS__'      => __ROOT__ . '/Uploads',
	),
	/* 调试模式开关 */
	'SHOW_PAGE_TRACE'	=>  false,
	
	//时区设置
	//'DEFAULT_TIMEZONE'		=>'America/New_York',
	/* 数据缓存设置 */
	'DATA_CACHE_PREFIX'		=> 'sr_', // 缓存前缀
	'DATA_CACHE_TYPE'		=> 'File',// 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator

	'CONTROLLER_LEVEL'		=> 1,//设置1级目录的控制器层
	
	/* URL配置 */
	'URL_CASE_INSENSITIVE' => false, //默认false 表示URL区分大小写 true则表示不区分大小写
	'URL_MODEL'            => 2, //URL模式
	'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
	'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符

	'PWD_TIME_LIMIT'		=>30,//找回密码时间限制，单位为分钟
	
	//'TMPL_ACTION_ERROR'     =>  'Common/View/Plugins/error.html', // 默认错误跳转对应的模板文件
	//'TMPL_ACTION_SUCCESS'   =>  'Common/View/Plugins/success.html', // 默认成功跳转对应的模板文件
	//'TMPL_EXCEPTION_FILE'   =>  'Common/View/Plugins/exception.html',// 异常页面的模板文件


	/* 图片上传相关配置 */
	'IMG_UPLOAD' => array(
		'mimes'    => '', //允许上传的文件MiMe类型
		'maxSize'  => 2*1024*1024, //上传的文件大小限制 (0-不做限制)
		'exts'     => 'jpg,gif,png,jpeg,bmp', //允许上传的文件后缀
		'autoSub'  => true, //自动子目录保存文件
		'subName'  => '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
		'rootPath' => './Uploads/ImgTemp/', //保存根路径
		'savePath' => '', //保存路径
		'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
		'saveExt'  => '', //文件保存后缀，空则使用原后缀
		'replace'  => false, //存在同名是否覆盖
		'hash'     => true, //是否生成hash编码
		'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
	), //图片上传相关配置（文件上传类配置）

	/* 文件上传相关配置 */
	'FILE_UPLOAD' => array(
		'mimes'    => '', //允许上传的文件MiMe类型
		'exts'     => 'jpg,jpeg,bmp,png,rar,zip,7z,doc,docx,rtf,txt,flv,mp4,f4v', //允许上传的文件后缀
		'autoSub'  => true, //自动子目录保存文件
		'subName'  => '', //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
		'rootPath' => './Uploads/FileTemp/', //保存根路径
		'savePath' => '', //保存路径
		'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
		'saveExt'  => '', //文件保存后缀，空则使用原后缀
		'replace'  => false, //存在同名是否覆盖
		'hash'     => true, //是否生成hash编码
		'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
	), //下载模型上传配置（文件上传类配置）
	

	/*短信配置*/
	'SMS_CONFIG'=>array(
		//初始化必填
		'accountsid'=>'38542bb2c01501e1db92b44c8bca8810',
		'token'=>'434c3a1cab14d7391828cb52ce3a11bd',
	),
	'SMS_APPID'=>'f0b1c14f5a88401297bed6ede4872b08',//发送短信的应用id
	'SMS_TEMPLATE'=>'10199',//发送短信模板id
);