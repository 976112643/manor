$(document).ready(function(){
	$('.list table tr:even').css('background','#f5f5f5');
	
	$('.js_menu').hover(function(){$(this).addClass('focus');},function(){$(this).removeClass('focus');});
	
	//左边菜单
	$('.one').click(function () {
		$('.one').removeClass('one-hover');
		$(this).addClass('one-hover');
		$(this).parent().parent().find('.child').hide();
		$(this).parent().find('.child').show();
	});
	//左边菜单
	$('.child li a').click(function () {
		$('.child li').removeClass('cur');
		$(this).parent().addClass('cur');
	});
	
	$(".top_ids").click(function(){
		if(this.checked){
			//同设置下的选项选中
			$(this).parents("tr").find("input").prop("checked",true);
			var all=$(this).parents("table");
			if(all.find(".top_ids:checked").length==all.find(".top_ids").length){
				all.find(".check_all").prop("checked",true);
			}
		}else{
			$(this).parents("tr").find("input").prop("checked",false);
			$(this).parents("table").find(".check_all").prop("checked",false);
		}
	})
	$(".child_ids").click(function(){
		if(this.checked){
			$(this).parent().next().find("input").prop("checked",true);
			//同设置下的选项选中
			var tr=$(this).parents("tr");
			if(tr.find(".child_ids:checked").length==tr.find(".child_ids").length){
				tr.find(".top_ids").prop("checked",true);
			}
			var all=$(this).parents("table");
			if(all.find(".top_ids:checked").length==all.find(".top_ids").length){
				all.find(".check_all").prop("checked",true);
			}
		}else{
			$(this).parent().next().find("input").prop("checked",false);
			//$(this).parents("tr").find(".top_ids").prop("checked",false);
			$(this).parents("table").find(".check_all").prop("checked",false);
		}
	})
	$(".last_ids").click(function(){
		if(this.checked){
			//同设置下的选项选中
			var module_box=$(this).parents(".module_box");
			if(module_box.find(".last_ids:checked").length==module_box.find(".last_ids").length){
				module_box.find(".child_ids").prop("checked",true);
			}
			var tr=$(this).parents("tr");
			if(tr.find(".child_ids:checked").length==tr.find(".child_ids").length){
				tr.find(".top_ids").prop("checked",true);
			}
			var all=$(this).parents("table");
			if(all.find(".top_ids:checked").length==all.find(".top_ids").length){
				all.find(".check_all").prop("checked",true);
			}
		}else{
			$(this).parents(".module_box").find(".child_ids").prop("checked",false);
			//$(this).parents("tr").find(".top_ids").prop("checked",false);
			$(this).parents("table").find(".check_all").prop("checked",false);
		}
	})
	$(document).on("click",".setCover",function(){
		if($(this).find("input[name=cache_cover]").prop("checked")){
			$(".setCover").parent().removeClass("cur");
			$(this).parent().addClass("cur");
		}
	})
	$(document).on("click",".js_cancel",function(){
		window.history.go(-1);
	})
})
//主导航效果切换
function menu_func(obj){
	//将菜单的上级对象赋值给一个新变量，做为公共调用
	var menu_box=$(obj).parent();
	//获取当前点击菜单的索引
	var cur_index=$('a',menu_box).index(obj);

	//改变当前点击对象的z-index值，并复原本次点击前的焦点z-index
	$(obj).addClass("cur").siblings('a.cur').removeClass("cur");
	
	$(".sidebar-menu").hide();
	$(".treeview").removeClass("active");
	$(".sidebar-menu").eq(cur_index).show().siblings(".sidebar-menu").hide();
	$(".sidebar-menu").eq(cur_index).find("li:eq(0)").addClass('active').find('ul').show().find("a:eq(0)").click();
	//$("#coniframe").attr("src",$(".treeview-menu li.cur a").attr("href"));
}
//主导航效果切换
//function menu_func(obj){
	//将菜单的上级对象赋值给一个新变量，做为公共调用
//	var menu_box=$(obj).parent();
	//获取当前点击菜单的索引
//	var cur_index=$('a',menu_box).index(obj);

//	$(".main_l .child").hide();
//	$(".main_l .one").removeClass("one-hover");
//	$(".main_l .slide_nav").eq(cur_index).show().siblings(".slide_nav").hide();
//	$(".main_l .slide_nav").eq(cur_index).find("a:eq(0)").addClass('one-hover').next().show().find("a:eq(0)").click();
//	$("#coniframe").attr("src",$(".child li.cur a").attr("href"));
//}