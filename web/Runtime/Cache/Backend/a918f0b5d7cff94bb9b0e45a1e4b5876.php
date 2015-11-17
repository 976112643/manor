<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta name="renderer" content="webkit">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	
<?php  ?>
<?php
 if($title){ $title=$title.' | '.C('SITE_TITLE'); }else{ $title=C('SITE_TITLE'); } if(!$keywords){ $keywords=C('SITE_KEYWORD'); } if(!$description){ $description=C('SITE_DESCRIPTION'); } $get_param=$_GET; $_GET=null; foreach ($get_param as $key => $val) { $_GET[strtolower($key)]=$val; } if(I("get.extension")){ cookie("register_extension_link",I("get.extension")); } ?>
<title><?=$title?></title>
<meta name="keywords" content="<?=$keywords?>" /> 
<meta name="description" content="<?=$description?>" />
<script>
var _ROOT_='/web';
var _STATIC_='/web/Public/Static';
var _PLUGIN_='/web/Public/Plugins';
var _JS_='/web/Public/Backend/js';
var _IMG_='/web/Public/Backend/img';
var _CSS_='/web/Public/Backend/css';
</script>
<link rel="stylesheet" type="text/css" href="/web/Public/Backend/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/web/Public/Backend/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="/web/Public/Backend/css/admin.min.css" />
<link rel="stylesheet" type="text/css" href="/web/Public/Backend/css/backend.css" />
<link rel="stylesheet" type="text/css" href="/web/Public/Plugins/uploadify/uploadify.css" />
<link rel="stylesheet" type="text/css" href="/web/Public/Plugins/artdialog/css/ui-dialog.css" />
<script type="text/javascript" src="/web/Public/Backend/js/jquery.min.js"></script>
<script type="text/javascript" src="/web/Public/Backend/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/web/Public/Backend/js/app.min.js"></script>
<script type="text/javascript" src="/web/Public/Static/js/common.js"></script>
<script type="text/javascript" src="/web/Public/Backend/js/common.js"></script>
<script type="text/javascript" src="/web/Public/Plugins/uploadify/jquery.uploadify.min.js"></script>
<script type="text/javascript" src="/web/Public/Plugins/artdialog/js/dialog-plus-min.js"></script>
<script type="text/javascript" src="/web/Public/Plugins/my97date/WdatePicker.js"></script>
<!--[if lt IE 9]>
  <script type="text/javascript" src="/web/Public/Backend/js/html5shiv.min.js"></script>
  <script type="text/javascript" src="/web/Public/Backend/js/respond.min.js"></script>
<![endif]-->
<!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="/web/Public/Backend/css/fix-ie.css" />
<script type="text/javascript" src="/web/Public/Backend/js/bootstrap-ie.js"></script>
<link rel="stylesheet" type="text/css" href="/web/Public/Backend/css/bootstrap-ie6.min.css" />
<![endif]-->
 
</head>
<body  class="login-page">
<div class="wrapper">
	<div class="login-box">
		<div class="login-logo">
			<a href="#this"><b>SunRun</b>.com</a>
		</div><!-- /.login-logo -->
		<div class="login-box-body">
			<p class="login-box-msg">Sign in to start your session</p>
			<form action="<?=U('Backend/Base/Public/login')?>" class="login_form" method="post">
				<div class="form-group has-feedback">
					<input type="text" name="username" class="form-control" placeholder="请输入您的账号">
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="password" name="password" class="form-control" placeholder="请输入您的密码">
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="row">
					<div class="col-xs-5">
						<input class="form-control" type="text" name="code" placeholder="输入验证码" />
					</div>
					<div class="col-xs-5">    
						<div class="code">
							<img class="verifyimg" src="<?=U('Backend/Base/Public/verify')?>">
						</div>                        
					</div><!-- /.col -->
					<div class="col-xs-2">
						<a class="btn btn-default btn-sm btn-flat pull-right reloadverify" href="javascript:void(0)">换一换</a>
					</div><!-- /.col -->
				</div>
				<div class="social-auth-links">
				<div class="js_tips alert alert-danger alert-dismissable" style="display:none;">
                    <div class="js_tipsContent"></div>
                </div>
				<input type="submit" func="Login" target-form="login_form" class="ajax-post btn btn-block btn-primary btn-flat text-center"  value="登录" />
			</div>
			</form>

			<!--  <div class="social-auth-links">
				<div class="js_tips alert alert-danger alert-dismissable" style="display:none;">
                    <div class="js_tipsContent"></div>
                </div>
				<input type="submit" func="Login" target-form="login_form" class="ajax-post btn btn-block btn-primary btn-flat text-center"  value="登录" />
			</div>--><!-- /.social-auth-links -->

		</div><!-- /.login-box-body -->
	</div><!-- /.login-box -->
</div>

<script>
	var verifyimg = $(".verifyimg").attr("src");
	$(".verifyimg").click(function(){
		if( verifyimg.indexOf('?')>0){
			$(".verifyimg").attr("src", verifyimg+'&random='+Math.random());
		}else{
			$(".verifyimg").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
		}
	});
	$(".reloadverify").click(function(){
		if( verifyimg.indexOf('?')>0){
			$(".verifyimg").attr("src", verifyimg+'&random='+Math.random());
		}else{
			$(".verifyimg").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
		}
	});
	function ajaxPostCallbackLogin(obj,status,info){
		if(status!=1){
			$(".verifyimg").click();
			$(document).keydown(function(event){
				if(event.keyCode == 13){
					$(".js_tipsContent").text(info);
				}
			})
			//tips(info,1000,'error');
			$(".js_tipsContent").text(info);
			$(".js_tips").slideDown();
			setTimeout(function(){
				obj.removeClass('disable').attr('autocomplete','off').prop('disabled',false);
				$(".js_tips").slideUp();
			},1500);
			return false;
		}else{
			$(".js_tips").removeClass("alert-danger").addClass("alert-success");
			return true;
		}
	}
	//后台添加键盘点击登录事件
	/*$(function(){
		$(document).keydown(function(event){
			 if(event.keyCode ==13){
				   $("form").submit();
			 }
		})
	})*/
</script>
</body>
</html>