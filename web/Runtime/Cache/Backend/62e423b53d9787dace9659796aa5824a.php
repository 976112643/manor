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
<body class="skin-gray sidebar-mini">
	<script type="text/javascript" src="/web/Public/Plugins/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/web/Public/Plugins/ueditor/ueditor.all.min.js"></script>

<script type="text/javascript">
var config={
	serverUrl:"<?=U('ueditor')?>",
};
</script>
	<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="#this" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>N邦</b></span>
      <!-- logo for regular state and mobile devices -->
      <!-- <span class="logo-lg">
        <i>CNSunRun</i>
      </span> -->
	  
	  <!-- <span class="logo_pic">
	  <img src="/web/Public/Home/img/sc_template/logo.png"/>
	  </span> -->
	  <span class="logo-lg">
        <i>N邦翻译</i>
      </span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      
      <a href="#" class="sidebar-toggle topbar-toggle" data-toggle="collapse" data-target=".nav_box" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      
      <!--navbar left menu-->
      <div class="nav_box">
      <?php
 foreach(session('menu_result') as $key=>$row){ $a['title']=$row['title']; $a['url']=$row['_child'][0]['url']; $header_url[]=$a; if(!$need_display){ foreach($row['_child'] as $k=> $val){ if(strstr(trim( '/web/Backend/Base/Config/index.html','.html'),"/web/".$val['url']) && !empty($val['url']) ){ $need_display=$key; $group_class=$val['groups']; $title_class=$val['title']; $page_title=$val['groups']; $page_small=$val['title']; $a=array('添加','启用','禁用','删除'); foreach($a as $aval){ foreach($val['_child'] as $v){ if($aval==$v['title']){ $page_child[]=$v; } } } break; } foreach($val['_child'] as $v){ if(strstr(trim( '/web/Backend/Base/Config/index.html','.html'),"/web/".$v['url']) && !empty($v['url']) ){ $need_display=$key; $group_class=$val['groups']; $title_class=$val['title']; $page_title=$val['title']; $page_small=$v['title']; break; } } if($need_display){ break; } } } } foreach($header_url as $key=>$row){ ?>
      	
      	<a href="<?=U($row['url'])?>" class="<?=$key==$need_display?'cur':''?>"  ><?=$row['title']?></a>
      	
      	<?php
 } ?>
      </div>
      <!--end -->
      
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

		          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <!-- Menu toggle button -->
           
            <ul class="dropdown-menu">
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li><!-- /.messages-menu -->


    
          <!-- Notifications Menu -->
          
          <!-- Tasks Menu -->
         
          <!-- User Account Menu -->
          <li class="dropdown user user-menu111">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle111" data-toggle="dropdown111">
              <!-- The user image in the navbar-->
              <!-- <img src="/web/Public/Backend/img/user.jpg" class="user-image" alt="User Image"/> -->
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?=session('username')?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              
              <!-- Menu Body -->
              
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  
                </div>
                <div class="pull-right">
                  <a href="<?=U('Backend/Base/Public/logout')?>" class="btn btn-default btn-flat ajax-get">安全退出</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
		  <a href="<?=U('Backend/Base/Public/logout')?>" class="ajax-get">安全退出</a>
            <!-- <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a> -->
          </li>
        </ul>
      </div>
    </nav>
</header>
<!-- Control Sidebar -->      
<aside class="control-sidebar control-sidebar-dark">                
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class='control-sidebar-menu'>
          <li>
            <a href='javascript::;'>
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>              
        </ul><!-- /.control-sidebar-menu -->
    
        <h3 class="control-sidebar-heading">Tasks Progress</h3> 
        <ul class='control-sidebar-menu'>
          <li>
            <a href='javascript::;'>               
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>
              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>                                    
            </a>
          </li>                         
        </ul><!-- /.control-sidebar-menu -->         
    
      </div><!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">            
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>
          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked />
            </label>
            <p>
              Some information about this general settings option
            </p>
          </div><!-- /.form-group -->
        </form>
      </div><!-- /.tab-pane -->
    </div>
</aside>
	<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<section class="sidebar">
		<br />
			<!-- Sidebar user panel (optional) -->
			<!-- <div class="user-panel">
				<div class="pull-left image">
					<img src="/web/Public/Backend/img/user.jpg" class="img-circle" alt="User Image" />
				</div>
				<div class="pull-left info">
					<p><?=session('username')?></p> -->
					<!-- Status -->
					<!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
			<!-- 	</div>
			</div> -->
		
			<!-- Sidebar Menu -->
			<ul class="sidebar-menu" >
				<!-- Optionally, you can add icons to the links -->
				<?php
 $group_menu=array(); foreach($menu_result[$need_display]['_child'] as $_row){ if($_row['groups']!=''){ $group_menu[$_row['groups']][]=$_row; } } foreach($group_menu as $k=>$v){ ?>
				<li class="treeview <?=$k==$group_class?'active':''?>"   >
					<a class="one one-hover"><i class='fa fa-th-large'></i> <span><?=$k?></span> <i class="fa fa-angle-left pull-right"></i></a>
					<ul class="treeview-menu" >
						<?php
 foreach($v as $menu){ ?>
						
						<li class="<?=$menu['title']==$title_class?'cur':''?>"><a href="<?=U($menu['url'])?>"><i class="fa  fa-align-left"></i><?=$menu['title']?></a></li>

						<?php
 if($iframe_src==''){ $iframe_src=U($menu['url']); } } ?>
					</ul>
				</li>
				<?php
 } ?>
			</ul>
			<script>
					$(function(){
						$(".treeview-menu li").click(function(){
							$(this).addClass("cur").siblings(this).removeClass("cur");	  
						});
					});
			</script>
			<!-- /.sidebar-menu -->
		</section>
</aside>
	<div class="wrapper">
		
    <!-- Content Wrapper. Contains page content -->
  	<div class="content-wrapper" style="min-height: 922px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            	<?=$page_title?>
            <small><?=$page_small?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=U('/Backend')?>"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li class="active"><?=$page_small?></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body">
                  <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                  	<div class="row">
                    	<div class="col-md-2">
                    	<!--
                        	<label><select class="form-control input-sm">
                                <option value="10">10</option>
                                <option value="25">25</option><option value="50">50</option>
                                <option value="100">100</option>
                        	</select></label>
                        	-->
                        </div>
                        <div class="col-md-5">
                        	<!--
                        	<label>搜索:<input type="search" class="form-control input-sm" placeholder="" aria-controls="example1"></label>
                       		-->
                        </div>
                        
                        <div class="col-md-5 text-right">
                        	<?php
 foreach($page_child as $v){ if($v['status']==1){ switch($v['title']){ case '删除': ?>
                        	<a href="<?=U('del')?>" class="btn btn-danger btn-sm ajax-post confirm" target-form="ids">删除</a>
                        	<?php
 break; case '禁用'; ?>
                        	<a href="<?=U('disable',array('status'=>0))?>" class="btn bg-navy btn-sm ajax-post" target-form="ids">禁用</a>
                        	<?php
 break; case '启用'; ?>
							<a href="<?=U('disable',array('status'=>1))?>" class="btn btn-info btn-sm ajax-post" target-form="ids">启用</a>
                        	<?php
 break; case '添加'; ?>
							<a class="btn btn-info btn-sm" href="<?=U('add',array('pid'=>intval(I('pid'))))?>">添加</a>
                        	<?php
 break; } } } ?>
                         </div>
                    </div>
                    <div class="row"><div class="col-sm-12">
						<table id="example2" class="table table-bordered table-hover dataTable text-center" role="grid" aria-describedby="example2_info">
	                  		<thead>
		                      	<tr role="row">
		                      		<th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width:3%"><label><input type="checkbox" class="check_all" value=""></label></th>
		                      		<th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">配置标题</th>
		                      		<th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">配置调用标识</th>
		                      		<th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending">配置类型</th>
		                      		<th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width:30%">备注</th>
		                      		<th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width:6%">状态</th>
		                      		<th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width:12%">操作</th>
		                      	</tr>
	                    	</thead>
		                    <tbody>
							<?php
 foreach($result as $row){ ?>
							<tr>
								<td width="25"><label><input type="checkbox" class="ids" name="ids[]" value="<?=$row['id']?>"></label></td>
								<td><?=$row['title']?></td>
								<td><?=$row['name']?></td>
								<td><?=C('config_type')[$row['type']]?></td>
								<td><?=$row['description']?></td>
								<td>
									<?php
 if($row['status']==0){ ?>
									<a url="<?=U('disable',array('status'=>1,'ids'=>$row['id'],'pid'=>I('pid')))?>" class="btn btn-info btn-sm ajax-get">启用</a>
									<?php
 }else{ ?>
									<a url="<?=U('disable',array('status'=>0,'ids'=>$row['id'],'pid'=>I('pid')))?>" class="btn bg-navy btn-sm ajax-get">禁用</a>
									<?php
 } ?>
								</td>
								<td>
									<a href="<?=U('edit',array('pid'=>I('pid'),'id'=>$row['id']))?>" class="btn btn-warning btn-sm">修改</a>&nbsp<a href="<?=U('del',array('status'=>-1,'ids'=>$row['id']))?>" class="btn btn-danger btn-sm ajax-get confirm">删除</a>
								</td>
							</tr>
							<?php
 } ?>
		                    </tbody>
						</table></div></div>
				   <!-- 需改
		           <div class="row"><div class="col-sm-5"><div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div></div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="example2_paginate"><ul class="pagination"><li class="paginate_button previous disabled" id="example2_previous"><a href="#" aria-controls="example2" data-dt-idx="0" tabindex="0">Previous</a></li><li class="paginate_button active"><a href="#" aria-controls="example2" data-dt-idx="1" tabindex="0">1</a></li><li class="paginate_button "><a href="#" aria-controls="example2" data-dt-idx="2" tabindex="0">2</a></li><li class="paginate_button "><a href="#" aria-controls="example2" data-dt-idx="3" tabindex="0">3</a></li><li class="paginate_button "><a href="#" aria-controls="example2" data-dt-idx="4" tabindex="0">4</a></li><li class="paginate_button "><a href="#" aria-controls="example2" data-dt-idx="5" tabindex="0">5</a></li><li class="paginate_button "><a href="#" aria-controls="example2" data-dt-idx="6" tabindex="0">6</a></li><li class="paginate_button next" id="example2_next"><a href="#" aria-controls="example2" data-dt-idx="7" tabindex="0">Next</a></li></ul></div></div></div></div>
                    -->
                   <div class="pages"><?=$page?></div>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div>

	</div>
	
</body>
</html>