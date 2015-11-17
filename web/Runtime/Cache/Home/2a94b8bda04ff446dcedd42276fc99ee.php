<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
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
var _JS_='/web/Public/Home/js';
var _IMG_='/web/Public/Home/img';
var _CSS_='/web/Public/Home/css';
</script>
<link rel="stylesheet" type="text/css" href="/web/Public/Home/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/web/Public/Home/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="/web/Public/Home/css/font-awesome-ie7.min.css" />
<link rel="stylesheet" type="text/css" href="/web/Public/Home/css/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/web/Public/Home/css/template-style.css" />
<link rel="stylesheet" type="text/css" href="/web/Public/Plugins/artdialog/css/ui-dialog.css" />
<link rel="stylesheet" type="text/css" href="/web/Public/Home/css/common.css" />
<script type="text/javascript" src="/web/Public/Home/js/jquery.min.js"></script>
<script type="text/javascript" src="/web/Public/Home/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/web/Public/Plugins/artdialog/js/dialog-min.js"></script>
<script type="text/javascript" src="/web/Public/Plugins/Validform_v5.3.2_min.js"></script>
<script type="text/javascript" src="/web/Public/Home/js/common.js"></script>
<script type="text/javascript" src="/web/Public/Static/js/common.js"></script>
	<!--[if lt IE 9]>
	  <script type="text/javascript" src="/web/Public/Home/js/html5shiv.min.js"></script>
	  <script type="text/javascript" src="/web/Public/Home/js/respond.min.js"></script>
	<![endif]-->
	<!--[if lte IE 6]>
	<link rel="stylesheet" type="text/css" href="/web/Public/Home/css/fix-ie.css" />
	<script type="text/javascript" src="/web/Public/Home/js/bootstrap-ie.js"></script>
	<link rel="stylesheet" type="text/css" href="/web/Public/Home/css/bootstrap-ie6.min.css" />
	<![endif]-->
 
</head>
<body>
<div class="top_box_ZH">
	<div class="container p_lr">
		<ul class="f_l">
			<li style="margin-left: 0;" class="m_r_20">您好，欢迎来到<b class="c_ff7402">N邦翻译</b> !</li>
			<?php
 if(!session('home_member_id')){ ?>
			<li>请<a href="<?=U('User/Login/index')?>">登录</a></li>
			<li><a href="<?=U('User/Register/index')?>">注册</a></li>
			<?php
 }else{ ?>
			<li>你好<a href="<?=U('User/Index/index')?>" class="m_r_5"><?=session('nickname')?session('nickname'):session('username')?></a><a class="m_lr_5 f_14" href="<?=U('User/Message/index')?>"><i class="fa fa-envelope org"></i></a>
				<a href="<?=U('User/Message/index')?>">您有<strong class="org m_lr_5"><?=(session('news_num'))?></strong>条新消息</a>
			</li>
			<li><a href="<?=U('User/Login/loginout')?>">退出</a></li>
			<?php
 } ?>
		</ul>
		<ul class="f_r">
			<li><a href="<?=U('User/Index/index')?>"><i class="fa fa-user f_14"></i>我的翻译</a></li>
			<li><a href="<?=C('TOP_APP_LINK')?>"><i class="fa fa-mobile-phone f_18" style="vertical-align: middle;"></i>手机客户端</a></li>
		</ul>
	</div>
</div>
<div class="search">
	<div class="container p_lr">
		<div class="f_l">
			<div class="f_l">
				<a class="logo" href="<?=U('Home/Index/index')?>" class="ct_nav" style="background-image:url('/web/<?=C('SITE_LOGO')?>'); background-size: cover;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/web/<?=C('SITE_LOGO')?>',sizingMethod='scale');">N邦翻译</a>
			</div>
			<div class="f_l city" >
				<?php $suffix = mb_strlen(session('cur_city_title')) > 4 ? true : false; ?>
				<strong><a class="c_ff7402 f_14" id="ctname" title="<?=session('cur_city_title')?>"><?=msubstr(session('cur_city_title'),0,4,"utf-8",$suffix)?></a></strong>
				<div class="ct_nav">
					<a href="<?=U('Home/City/index')?>"	class="js_check_city">切换城市</a>
				</div>
			</div>	
			<script>
			/*$(function(){
				$('.js_check_city').on('click',function(){
					var url = window.location.href;
					new_url = url.replace(/\//g,'|');	
					to_href = $(this).attr('href');
					window.location.href = to_href.substr(0,to_href.length-5)+'/cur_url/'+new_url;
					return false;
				}); 
			})*/
			</script>
		</div>
		<div class="f_r">
			<div class="search_form f_l">
				<form action='<?=U('Home/Classes/index')?>' method='get'>
				<div class="tab_form">
					<input type="hidden" name="type" value=<?=$param['type']?$param['type']:1?> >
					<ul class="js_search_tab">
						<li class="<?=empty($param['type'])?'searchTab_cur':''?><?=$param['type']==1?'searchTab_cur':''?>" data-type="1">翻译人员</li>
						<li class="<?=$param['type']==2?'searchTab_cur':''?>" data-type="2">翻译公司</li>
						<li class="<?=$param['type']==3?'searchTab_cur':''?>" data-type="3">翻译语言</li>
					</ul>
				</div>
				<div class="search_input clearfix">
					<div class="pull-left search_select">
						<div class="sim-select-box">
							<div class="sim-select-checked">
						    <?php
 if(!isset($param['ability_id']) or $param['ability_id']==0){ ?>  
							    <input type="hidden" name="ability_id" value='0'><span>全部</span><i class="fa fa-caret-down f_18"></i>
							<?php
 }else{ ?>	
							<?php
 $serve_type = get_serve_cache(); $ability1 = array_id_key(get_ability_cache()); foreach($serve_type as $k=>$v){ if(isset($param['ability_id'])){ if($param['ability_id']==$v['id']){ ?>
								<input type="hidden" name="ability_id" value='<?=$v['id']?>'><span><?=$v['title']?></span><i class="fa fa-caret-down f_18"></i>
							<?php
 }else{ if($param['ability_id']==$ability1[$param['ability_id']]['id']){ ?>			  
										    <input type="hidden" name="ability_id" value='<?=$param['ability_id']?>'><span><?=$ability1[$param['ability_id']]['title']?></span><i class="fa fa-caret-down f_18"></i>
							<?php	 }else{ echo '<input type="hidden" name="ability_id" value="0"><span>全部</span><i class="fa fa-caret-down f_18"></i>'; } } }else if($v['pid']==0 && $v['recommend']==1){ ?>
								<input type="hidden" name="ability_id" value='<?=$v['id']?>'><span><?=$v['title']?></span><i  class="fa fa-caret-down f_18"></i>
							<?php
 } } } ?>
							</div>
							<div class="sim-select-list">
							   <span data-type="0">全部</span>
							<?php
 $serve_type = get_ability_cache(); foreach($serve_type as $k=>$v){ if($v['pid']==0){ ?>
								<span data-type="<?=$v['id']?>" ><?=$v['title']?></span>
							<?php
 } } ?>
							</div>
						</div>
					</div>
					<button class="pull-right search_btn" type="submit" >
						<i class="fa fa-search f_18" ></i>
					</button>
					<div class="search_input_box">
						<input type="text" name="search" placeholder="输入您想找的翻译公司，翻译人员，翻译语言" value='<?=$param['search']?>'>
					</div>
				</div>
				</form>
				<div class="tag">
					<?php
 $language = get_language_cache(); $i = 0; foreach($recommend_language as $k=>$v){ if($v['pid'] ==1 && $v['recommend']==1 && $i<=8){ ?>
					<a href="<?=U('Home/Classes/index',array('language_id'=>$v['id']))?>"><?=$v['title']?></a>
					<?php
 $i++; } } ?>
				</div>
			</div>
			<!--右侧图标标语,如需去掉，直接注释此段代码-->
			<div class="feature_box f_r">
				<ul class="clearfix">
					<li>
						<!-- <a href="<?=C('TOP_TEACHER')?>"> -->
						<a href="<?=U('Home/Help/index',array('id'=>46))?>">
							<i class="fc fc-boshi f_24"></i>
							<span>翻译认证</span>
						</a>
					</li>
					<li>
						<!-- <a href="<?=C('TOP_ANY_TIME')?>"> -->
						<a href="<?=U('Home/Help/index',array('id'=>50))?>">
							<i class="fc fc-tui f_24"></i>
							<span>随时退</span>
						</a>
					</li>
					<li>
						<!-- <a href="<?=C('TOP_TRUTH')?>"> -->
						<a href="<?=U('Home/Help/index',array('id'=>51))?>">
							<i class="fc fc-message f_24"></i>
							<span>真实评价</span>
						</a>
					</li>
				</ul>
			</div>
			<!--右侧图标标语 end-->
		</div>
	</div>
</div>
<script>
	$('.js_search_tab li').click(function(){
		$(this).addClass('searchTab_cur').siblings().removeClass('searchTab_cur');
		$(this).parent().prev('input').val($(this).attr('data-type'));
	})
</script>
<?php if(U(CONTROLLER_NAME.'/'.ACTION_NAME) ==U('/Home/Index/index')){ ?>
<div class="nav_ZH">
	<div class="container p_lr clearfix">
		<div class="nav2 pull-left">
			<b  class="title_nav">语言分类</b>
		</div>
		<ul  class="menu_ZH">
			<?php
 $navigation = getNavigation(); $top_navigation = $navigation['top']; foreach($top_navigation as $row){ ?>
			<?php if($row['title'] == 首页){ ?>
			<li><a href="/web<?=$row['url']?>" target='<?=$row['target']?>'><?=$row['title']?></a></li>
			<?php }else{?>
			<li><a href="/web/<?=$row['url']?>" target='<?=$row['target']?>'><?=$row['title']?></a></li>
			<?php
 } } ?>
			
			<!-- <li><a href="javascript:void(0)">首页</a></li>
			<li><a href="javascript:void(0)">笔译</a></li>
			<li><a href="javascript:void(0)">口译</a></li>			
			<li><a href="javascript:void(0)">平台流程</a></li>
			<li><a href="javascript:void(0)">翻译入驻</a></li>
			<li><a href="javascript:void(0)">下载APP</a></li>
			<li><a href="javascript:void(0)">活动专区</a></li> -->
		</ul>
	</div>
</div>
<?php }else{ ?>
<div class="nav_ZH">
	<div class="container p_lr clearfix">
		<div class="nav2 pull-left">
			<b  class="title_nav">语言分类</b>
			<!--下拉样式 S -->
			<div class="home_left_sidebar sidebar2 f_l">
				<ul>
					<?php  foreach($recommend_language as $row){ ?>
					<li>
						<dl class="clearfix">
							<dt><b class="f_14"><?=$row['title']?></b><span>(源语言)</span></dt>
							<?php  $temp_i = 1; foreach($language as $_row){ if($_row['pid']>0 && $_row['id']!=$row['id']){ if($temp_i<5){ ?>
							<dd><a href="<?=U('/Home/Classes/index',array('language_id'=>$row['id'],'to_language_id'=>$_row['id']))?>"><?=$_row['title']?></a></dd>
							<?php  }else{ break; } $temp_i++; } } ?>
						</dl>
						<div class="sidebar_ZH op_ZH">
							<dl class="clearfix">
								<dt><h5 class="c_ff7402"><b>目标语言</b></h5></dt>
								<?php  $temp_i = 1; foreach($language as $_row){ if($_row['pid']>0 && $_row['id']!=$row['id']){ ?>
								<dd><a href="<?=U('/Home/Classes/index',array('language_id'=>$row['id'],'to_language_id'=>$_row['id']))?>"><?=$_row['title']?></a></dd>
								<?php  } } ?>
							</dl>
						</div>
					</li>
					<?php
 } ?>
					<li class="more_btn2"><a class="text-center" href="<?=U('/Home/Classes/index')?>"><b>找好翻译,上N帮</b></a></li>
				</ul>
			</div>
			<!--下拉样式 E -->
		</div>
		<ul class="menu_ZH">
			<?php
 $navigation = getNavigation(); $top_navigation = $navigation['top']; foreach($top_navigation as $row){ ?>
			<?php if($row['title'] == 首页){ ?>
			<li class="list_nav"><a href="/web<?=$row['url']?>" target='<?=$row['target']?>'><?=$row['title']?></a></li>
			<?php }else{?>
			<li class="list_nav"><a href="/web/<?=$row['url']?>" target='<?=$row['target']?>'><?=$row['title']?></a></li>
			<?php
 } } ?>
		</ul>
	</div>
</div>

<?php }?>
<script type="text/javascript" src="/web/Public/Plugins/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/web/Public/Plugins/ueditor/ueditor.all.min.js"></script>

<script type="text/javascript">
var config={
	serverUrl:"<?=U('ueditor')?>",
};
</script>

	<script type="text/javascript" src="/web/Public/Home/js/superSlide.js"></script>
	<!--第一块-->
	<div class="container p_lr clearfix">
		<div class="home_left_sidebar f_l">
			<ul>
			<?php  foreach($recommend_language as $row){ ?>
				<li>
					<dl class="clearfix">
						<dt><b class="f_14"><?=$row['title']?></b><span>(源语言)</span></dt>
						<?php  $temp_i = 1; foreach($language as $_row){ if($_row['pid']>0 && $_row['id']!=$row['id']){ if($temp_i<5){ ?>
						<dd><a href="<?=U('/Home/Classes/index',array('language_id'=>$row['id'],'to_language_id'=>$_row['id']))?>"><?=$_row['title']?></a></dd>
						<?php  }else{ break; } $temp_i++; } } ?>
					</dl>
					<div class="sidebar_ZH">
						<dl class="clearfix">
							<dt><h5 class="c_ff7402"><b>目标语言</b></h5></dt>
						<?php  $temp_i = 1; foreach($language as $_row){ if($_row['pid']>0 && $_row['id']!=$row['id']){ ?>
						<dd><a href="<?=U('/Home/Classes/index',array('language_id'=>$row['id'],'to_language_id'=>$_row['id']))?>"><?=$_row['title']?></a></dd>
						<?php  } } ?>
						</dl>
					</div>
				</li>
			<?php
 } ?>
				<li class="more_btn2"><a class="text-center" href="<?=U('/Home/Classes/index')?>">更多语言 >></a></li>
			</ul>
		</div>
		<div class="home_right_mainbox f_r">
			<div class="hot_box text-left">
				<strong class="f_l">网站指数:</strong>
				<div class="f_l hot_ZH">
					<ul>
						<li class="ellipsis">
							交易总额: ￥<?=$site_index['price_sum']?> 　 需求总量: <?=$site_index['demand']?> 　商家总量: <?=$site_index['total_num']?>
						</li>
					</ul>
				</div>
			</div>	
			<div class="clearfix">
				<div class="home_slide_box f_l">
					<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
						<!-- Indicators -->
						<ol class="carousel-indicators">
						<?php  foreach($home_banner as $k=>$row){ ?>
							<li data-target="#carousel-example-generic" data-slide-to="<?=$k?>" class="<?php if($k==0){echo 'active';} ?>"></li>
							
						<?php  } ?>
						</ol>

						<!-- Wrapper for slides -->
						<div class="carousel-inner" role="listbox">
						<?php  foreach($home_banner as $k=>$row){ ?>
							<div class="item <?php if($k==0){echo 'active';} ?>">
								<img src="/web/<?=$row['image']?>" alt="<?=$row['title']?>">
							</div>
						<?php  } ?>
						</div>
						<!-- Controls -->
						<a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</a>
					</div>
					<!--小幻灯片-->
					<div class="small_box js_loop_slide">
						<div class="small_banner_ZH slide">
							<?php
 foreach($advert as $k=>$row){ ?>
							<ul class="piclist mainlist">
								<li><a href="<?=completion_url($row['url'])?>" target="_blank" ><img src="/web/<?=$row['image']?>" alt="<?=$row['title']?>"></a></li>
							</ul>
							<?php
 } ?>
						</div>
						<div class="sb_btn og_prev fa fa-angle-left prev"></div>
						<div class="sb_btn og_next fa fa-angle-right next"></div>
					</div>
					<script>
						$(function(){
							$(".js_loop_slide").slide({ mainCell:".slide",effect:"leftLoop",autoPlay:false});
							$(".small_box").hover(function(){
								$(".sb_btn").show()
							},function(){
									$(".sb_btn").hide();
							});
						})
					</script>
				</div>
				<div class="f_r ">
					<div class="right_box_ZH">
						<div class="tab_list">
							<ul>
								<li class="tab_cur"><a href="<?=U('Home/Notice/index')?>"><b class="f_14">公告</b></a></li>
								<!-- <li><b class="f_14">活动</b></li> -->
								<li><a href="<?=U('Home/News/index')?>"><b class="f_14">新闻</b></a></li>
							</ul>
						</div>
						<div class="tab_list2">
						<!--  -->
							
							<ul>
								<?php
 foreach($notice as $k=>$row){ ?>
								<li class="ellipsis"><a href="<?=U('Home/Notice/detail',array('id'=>$row['id']))?>"><?=$row['title']?></a></li>
								<?php
 } ?>
							</ul>
							<ul style="display: none">
								<?php
 foreach($news as $k=>$row){ ?>
									<li class="ellipsis"><a href="<?=U('Home/News/detail',array('id'=>$row['id']))?>"><?=$row['title']?></a></li>
								<?php
 } ?>
							</ul>
						</div>	
						<div class="login_home_ZH">
							<div class="userpic_ZH clearfix">
								<div class="f_l">
									<div class="pto_box_ZH">
										<img src="/web/icon/<?=session('home_member_id')?>_avatar_big.jpg" onerror="this.src='/web/Public/Home/img/sc_template/onerror_pic.png'">
									</div>
								</div>
								<div class="f_l m_l_15">
									<?php if(session('home_member_id')){?>
									hi，<a href="<?=U('User/Index/index')?>"><?=session('nickname')?session('nickname'):session('username')?></a></br>欢迎来到N邦翻译</br><a href="<?=U('/User/Login/loginout')?>">退出</a>
									<?php }else{?>
									欢迎来到N邦翻译</br>
									<a class="f_14" href="<?=U('/User/Login/index')?>"><b>登录</b></a>
									<?php }?>
								</div>
							</div>
							<div class="register_btn_ZH m_t_10">
							<?php if(!session('home_member_id')){?>
								<a href="<?=U('User/Register/index',array('type'=>'1'))?>">用户注册</a><a href="<?=U('User/Register/index',array('type'=>'2'))?>">个译注册</a><a style="margin-right:0px;" href="<?=U('User/Register/index',array('type'=>'3'))?>">公司注册</a>
							<?php }else{?>
								<p>上N邦翻译，找最好的翻译</p> 
							<?php }?>
							</div>	
						</div>						
					</div>
					<div class="right_box_ZH wx_box_ZH clearfix">
						<!-- <img class="f_l" src="/web/Public/Home/img/sc_template/wx_pic.jpg" width="90" height="90"> -->
						<img class="f_l" src="/web/<?=C('QR_CODE')?>" width="90" height="90">
						<div class="f_r wx_ZH">
							<div class="wx_title_ZH f_14"><i class="c_68CC33 f_24 fa fa-weixin"></i>官方微信</div>
							<div class="c_666">N邦翻译为翻译公司和个人译者提供一个宣传自己的平台。</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--列表第二块-->
	<div>
		<div class="container p_lr">
			<div class="title_box">
				<span class="f_l c_ff7402 f_18"><strong>推荐翻译</strong></span>
				<div class="tab_title f_l m_l_15">	
					<ul>
						<li><a href="<?=U('/Home/Classes/index',array('type'=>1))?>">个人译者</a></li>
						<li><a href="<?=U('/Home/Classes/index',array('type'=>2))?>">公司翻译</a></li>
					</ul>
				</div>
				<a class="f_r more_btn" href="<?=U('/Home/Classes/index',array('ability_id'=>36))?>">更多+</a>
			</div>
			<div class="list_box hover_li">
			<?php if(empty($shop_company)){?>
		 		<div class="jump_ZH ">
	<table class="table">
		<tr>
			<td>
				<img src="/web/Public/Home/img/jump_bg_s.png">暂无数据
			</td>
		</tr>
	</table>
</div>
			<?php }?>
				<ul class="text-center">
					<?php
 foreach($shop_company as $k=>$row){ $length = count($shop_company); ?>
					<li <?php if($k==$length-1){ ?> style="margin: 0 0 15px 0" <?php } ?> >
					<a href="<?=U('/Home/Classes/detail',array('shop_id'=>$row['id']))?>">
						<div class="w_204 img_ZH">
							<div class="img_pic" style="background-image: url('/web/<?=$row['logo']?>');"></div>
							<!-- <img onerror="this.src='/web/Public/Home/img/sc_template/onerror_pic.png'" src="/web/<?=$row['logo']?>"> -->	
						</div>
						<div class="w_204 summary_ZH bp_ZH text-left">
							<span><strong class="f_14 c_3d86b0"><?=$row['title']?></strong>[ <?=$row['translate_year']?>年译龄]</span>
							<div class="m_tb_8">
								<span><i class="f_24 fa fa-microphone"></i><?=$row['total_translate_time_w']?>小时</span>
								<span class="m_l_15"><i class="f_24 fa fa-edit (alias)"></i><?=$row['total_translate_num_w']?>万字</span>
							</div>
							<span class="w_185 ellipsis c_ff7402">共提供过<?=$row['service_times']?>次服务</span>
							<dl>
								<dt>擅长：</dt>
								<?php  $language_id_key = array_id_key($language); $good_at = json_decode($row['good_at'],true); foreach($good_at as $k=>$val){ if($k<2){ ?>
								<dd><?=$language_id_key[$val]['title']?></dd>
								<?php  } } ?>
							</dl>
						</div>
						</a>
					</li>
					<?php
 } ?>
				</ul>
			</div>
		</div>
	</div>	
	<!--第二列-->
	<div class="b_f8f8f8">
		<div class="container p_lr">
			<div class="title_box">
				<span class="f_l c_ff7402 f_18"><strong>最新入驻</strong></span>
				<!--
				<div class="tab_title f_l m_l_15">	
					<ul>
						<li><a href="javascript:void(0)">个人译者</a></li>
						<li><a href="javascript:void(0)">公司翻译</a></li>
					</ul>
				</div>-->
				<a class="f_r more_btn" href="<?=U('/Home/Classes/index',array('ability_id'=>37))?>">更多+</a>
			</div>
			<div class="list_box hover2_li">
			<?php if(empty($shop_new)){?>
		 		<div class="jump_ZH ">
	<table class="table">
		<tr>
			<td>
				<img src="/web/Public/Home/img/jump_bg_s.png">暂无数据
			</td>
		</tr>
	</table>
</div>
			<?php }?>
				<ul class="text-center">
					<?php
 foreach($shop_new as $k=>$row){ $length = count($shop_new); ?>
					<li <?php if($k==$length-1){ ?> style="margin: 0 0 15px 0" <?php } ?> >
					<a href="<?=U('/Home/Classes/detail',array('shop_id'=>$row['id']))?>">
						<div class="img2_ZH">
							<div class="img_pic" style="background-image: url('/web/<?=$row['logo']?>');"></div>
						</div>
						<div class="text-left summary_ZH bp2_ZH">
						
							<span><strong class="f_14 c_3d86b0"><?=$row['title']?></strong>[ <?=$row['translate_year']?>年译龄 ]</span>
							<dl>
								<dt>擅长：</dt>
								<?php  $language_id_key = array_id_key($language); $good_at = json_decode($row['good_at'],true); foreach($good_at as $k=>$val){ if($k<2){ ?>
								<dd><?=$language_id_key[$val]['title']?></dd>
								<?php  } } ?>
							</dl>
						</div>
						<div class="shadow_ZH"></div>
						</a>
					</li>
					<?php
 } ?>
				</ul>
			</div>
		</div>
	</div>
	<div>
		<div class="container p_lr">
			<div class="title_box">
				<span class="f_l c_ff7402 f_18"><strong>笔译</strong></span>
				<div class="tab_title f_l m_l_15">	
					<!-- <ul>
						<li><a href="javascript:void(0)">中译法</a></li>
						<li><a href="javascript:void(0)">英译德</a></li>
						<li><a href="javascript:void(0)">韩译日</a></li>
						<li><a href="javascript:void(0)">德译中</a></li>
						<li><a href="javascript:void(0)">法译韩</a></li>
					</ul> -->
				</div>
				<a class="f_r more_btn" href="<?=U('/Home/Classes/index',array('ability_id'=>36))?>">更多+</a>
			</div>
			<div class="list_box hover_li">
			<?php if(empty($written_shop)){?>
		 		<div class="jump_ZH ">
	<table class="table">
		<tr>
			<td>
				<img src="/web/Public/Home/img/jump_bg_s.png">暂无数据
			</td>
		</tr>
	</table>
</div>
			<?php }?>
				<ul class="text-center">
					<?php
 foreach($written_shop as $k=>$row){ $length = count($shop_company); ?>
					<li <?php if($k==$length-1){ ?> style="margin: 0 0 15px 0" <?php } ?> >
					<a href="<?=U('/Home/Classes/detail',array('shop_id'=>$row['id']))?>">
						<div class="w_204 img_ZH">
							<div class="img_pic" style="background-image: url('/web/<?=$row['logo']?>')"></div>
						</div>
						<div class="w_204 summary_ZH bp_ZH text-left">
							<span><strong class="f_14 c_3d86b0"><?=$row['title']?></strong>[ <?=$row['translate_year']?>年译龄 ]</span>
							<div class="m_tb_8">
								<span><i class="f_24 fa fa-edit (alias)"></i><?=$row['total_translate_num_w']?>万字</span>
							</div>
							<h5 class="w_185 ellipsis">共提供过<strong class="c_ff7402"> <?=$row['service_times']?> </strong>次服务</h5>
							<!-- <h5 class="w_185 ellipsis">共翻译过<strong class="c_ff7402"> <?=$row['total_translate_num_w']?> </strong>万字</h5> -->
							<dl>
								<dt>擅长：</dt>
								<?php  $language_id_key = array_id_key($language); $good_at = json_decode($row['good_at'],true); foreach($good_at as $k=>$val){ if($k<2){ ?>
								<dd><?=$language_id_key[$val]['title']?></dd>
								<?php  } } ?>
							</dl>
						</div>
						</a>
					</li>
					<?php
 } ?>
				</ul>
			</div>
		</div>
	</div>	
	<!--第二列-->
	<div class="b_f8f8f8">
		<div class="container p_lr">
			<div class="title_box">
				<span class="f_l c_ff7402 f_18"><strong>口译</strong></span>
				<!--
				<div class="tab_title f_l m_l_15">	
					<ul>
						<li><a href="javascript:void(0)">个人译者</a></li>
						<li><a href="javascript:void(0)">公司翻译</a></li>
					</ul>
				</div>-->
				<a class="f_r more_btn" href="<?=U('/Home/Classes/index',array('ability_id'=>37))?>">更多+</a>
			</div>
			<div class="list_box hover2_li">
			<?php if(empty($written_shop)){?>
		 		<div class="jump_ZH ">
	<table class="table">
		<tr>
			<td>
				<img src="/web/Public/Home/img/jump_bg_s.png">暂无数据
			</td>
		</tr>
	</table>
</div>
			<?php }?>
				<ul class="text-center">
					<?php
 foreach($written_shop as $k=>$row){ $length = count($shop_company); ?>
					<li <?php if($k==$length-1){ ?> style="margin: 0 0 15px 0" <?php } ?> >
					<a href="<?=U('/Home/Classes/detail',array('shop_id'=>$row['id']))?>">
						<div class="img2_ZH">
							<div class="img_pic" style="background-image: url('/web/<?=$row['logo']?>');"></div>
						</div>
						<div class="text-left summary_ZH bp2_ZH">
							<span><strong class="f_14 c_3d86b0"><?=$row['title']?></strong>[ <?=$row['translate_year']?>年译龄 ]</span>
							<div class="m_tb_8">
								<span><i class="f_24 fa fa-microphone"></i><?=$row['total_translate_time_w']?>小时</span>
							</div>
							<!-- <h5>口译服务<strong class="c_ff7402"> <?=$row['service_times']?> </strong>小时</h5> -->
							<h5>共提供<strong class="c_ff7402"> <?=$row['service_times']?> </strong>次服务</h5>
							
							<dl>
								<dt>擅长：</dt>
								<?php  $language_id_key = array_id_key($language); $good_at = json_decode($row['good_at'],true); foreach($good_at as $k=>$val){ if($k<2){ ?>
								<dd><?=$language_id_key[$val]['title']?></dd>
								<?php  } } ?>
							</dl>
						</div>
						<div class="shadow_ZH"></div>
					</a>
					</li>
					<?php
 } ?>
				</ul>
			</div>
		</div>
	</div>

	<!--平台流程-->
	<div class="container p_lr">
		<div class="flow_ZH">
			<p>
				<span class="f_24"><strong>平台流程</strong></span>
				<span class="tab_title m_l_15">轻松掌握N邦翻译平台，让翻译得心应手！</span>
			</p>
			<div class="text-center c_666">
				<ul>
					<li>
						<div class="icon_box_ZH">
							<i class="fa fa-search b_icon"></i>
							<hr/>
							<span class="f_18 fa fa-chevron-right c_ff7402"></span>
						</div>
						<h4 class="c_ff7402">搜索译者</h4>
						<p>通过瓶套，浏览和搜索到合适</br>的翻译公司或个人译者，总有一个适合您。</p>
					</li>
					<li>
						<div class="icon_box_ZH">
							<i class="fa fa-shopping-cart b_icon"></i>
							<hr/>
							<span class="f_18 fa fa-chevron-right c_ff7402"></span>
						</div>
						<h4 class="c_ff7402">购买服务</h4>
						<p>通过查看商家的信息和过往记录</br>选择合适的商家购买服务，翻译结束，有用</br>户确认再由平台将费用支付给译者</p>
					</li>
					<li>
						<div class="icon_box_ZH">
							<i class="fa  fa-newspaper-o b_icon"></i>
							<hr/>
							<span class="f_18 fa fa-chevron-right c_ff7402"></span>
						</div>
						<h4 class="c_ff7402">线下翻译</h4>
						<p>通购买服务之后，商家会在约定</br>完成的时间内完成翻译任务，用户和商家可以</br>在线下沟通交流，不满意可以全额退款。</p>
					</li>
					<li style="margin-right: 0px">
						<div class="icon_box_ZH">
							<i class="fa fa-star b_icon"></i>
							<hr/>
							<span class="f_24 fa fa-check-circle c_ff7402"></span>
						</div>
						<h4 class="c_ff7402">服务评价</h4>
						<p>购买服务之后，商家会在约定完成</br>的时间内完成翻译任务，用户和商家可以在</br>线实时沟通交流，不满意可以全额退款。</p>
					</li>
				</ul>
			</div>
		</div>
	</div>
<script type="text/javascript">
	function autoScroll(obj){
		$(obj).find("ul:first").animate(
			{marginTop:"-20px"},500,function(){
					$(this).css({marginTop:"0"}).find("li:first").appendTo(this);
			});
		}
		//setInterval('autoScroll(".hot_ZH")',2000);
		$(".tab_list ul li").hover(function(){
			$(this).addClass('tab_cur').siblings(this).removeClass('tab_cur')
			var index=$(".tab_list ul li").index(this)
			$(".tab_list2 ul").eq(index).show().siblings(index).hide();
		});
	</script>	

<!--footer start-->
<div class="partner">
	<div class="container">
	  	<h5>
	  		<strong>合作伙伴</strong>
	  	</h5>
	  	<div>
			<?php
 $navigation = getNavigation(); $link_navigation = $navigation['links']; foreach($link_navigation as $key => $row){ ?>
				<?php if($key){ ?>
				<i></i>
				<?php }?>
			<a href="<?=completion_url($row['url'])?>" target='<?=$row['target']?>'><?=$row['title']?></a>
			<?php
 } ?>
			
	  		<!-- <a href="javascript:void(0)">法语之友乐园</a><i></i> 
	  		<a href="javascript:void(0)">中译法研讨会博客</a><i></i>
	  		<a href="javascript:void(0)">蒙特雷国际研究学院（MIIS）</a><i></i>
	  		<a href="javascript:void(0)">国际会议口译员协会（AIIC）</a><i></i>
	  		<a href="javascript:void(0)">中国对外翻译出版有限公司</a><i></i>
	  		<a href="javascript:void(0)">随你译翻译中国</a><i></i>
	  		<a href="javascript:void(0)">译网Proz.com</a><i></i>
	  		<a href="javascript:void(0)">本地化世界网</a><i></i>
	  		<a href="javascript:void(0)">全球化与本地化协会（GALA）</a><i></i>
	  		<a href="javascript:void(0)">Multilingual</a><i></i>
	  		<a href="javascript:void(0)">美国翻译协会（ATA）</a><i></i>
	  		<a href="javascript:void(0)">Common Sense Advisory </a><i></i>
	  		<a href="javascript:void(0)">国际翻译与跨文化研究协会（IATIS）</a><i></i>
	  		<a href="javascript:void(0)">国际术语联盟（TermNet）</a><i></i>
	  		<a href="javascript:void(0)">国际术语信息中心（Infoterm）</a><i></i>
	  		<a href="javascript:void(0)">国际医疗口译员协会（IMIA）</a><i></i>
	  		<a href="javascript:void(0)">翻译自动化用户协会（TAUS）</a><i></i>
	  		<a href="javascript:void(0)">深圳市台电实业有限公司</a><i></i>
	  		<a href="javascript:void(0)">思迪软件科技（深圳）有限公司</a><i></i>
	  		<a href="javascript:void(0)">21英语教师网</a><i></i>
	  		<a href="javascript:void(0)">传神联合（北京）信息技术有限公司</a> -->
	  	</div>
	</div> 
</div>

<div class="footer_box_ZH">
	<div class="container">
		<p>	
			<?php
 $navigation = getNavigation(); $bottom_navigation = $navigation['bottom']; foreach($bottom_navigation as $key => $row){ ?>
				<?php if($key){ ?>
				|
				<?php }?>
			<a href="/web/<?=$row['url']?>" target='<?=$row['target']?>'><?=$row['title']?></a>
			<?php
 } ?>
			<!-- <a href="javascript:void(0)">帮助中心</a> | 
			<a href="javascript:void(0)">支付方式</a> | 
			<a href="javascript:void(0)">联系方式</a> | 
			<a href="javascript:void(0)">客服中心</a> | 
			<a href="javascript:void(0)">服务支持</a> | 
			<a href="javascript:void(0)">网站地图</a> -->
		</p>
		<p><?=C('COPY_RIGHT')?></p>
		<!-- <p>Copyright © 2010-2015 www.88888888.com N邦翻译版权所有 备案：闽ICP备888888888号</p> -->
		<ul class="clearfix">
			<li><img src="/web/Public/Home/img/sc_template/footer_icon_1.png"></li>
			<li><img src="/web/Public/Home/img/sc_template/footer_icon_2.png"></li>
			<li><img src="/web/Public/Home/img/sc_template/footer_icon_3.png"></li>
			<li><img src="/web/Public/Home/img/sc_template/footer_icon_4.png"></li>
			<li><img src="/web/Public/Home/img/sc_template/footer_icon_5.png"></li>
			<li><img src="/web/Public/Home/img/sc_template/footer_icon_6.png"></li>
			<li><img src="/web/Public/Home/img/sc_template/footer_icon_7.png"></li>
			<li><img src="/web/Public/Home/img/sc_template/footer_icon_8.png"></li>
		</ul>
	</div>
</div>

</body>
</html>