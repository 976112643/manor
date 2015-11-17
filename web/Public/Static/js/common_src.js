$(document).ready(function(){
/* tab选项卡 */
	$(document).on("click",".tab_box .tab_title .tab",function(){
		tabChange(this,".tab_box");
	})
	/* tab选项卡 */
	$(document).on("mouseover mouseout",".tab_box_hover .tab_title .tab",function(){
		tabChange(this,".tab_box_hover");
	})

	$(document).on("click",".setCover",function(){
		if($(this).find("input[name=cache_cover]").prop("checked")){
			$(".setCover").parent().removeClass("cur");
			$(this).parent().addClass("cur");
		}
	})
	$(document).on("click",".ajax-get",function(){
		var target;
		var _this=$(this);
		var ico="ask";
		if(_this.attr("font_ico")){
			var ico=_this.attr("font_ico");
		}
		if ( _this.hasClass('confirm') ) {
			if(_this.attr('tips')){
				var msg=_this.attr('tips');
			}else{
				var msg='确认要执行该操作吗?';
			}
			var d=dialog({
				fixed: true,
				title:"系统提示",
				content:'<div class="font_ico '+ico+'">'+msg+'</div>',
				ok:function(){
					ajax_get(_this,target);
				},
				cancel:function(){
					d.close().remove();
					return false;
				},
				lock:true
			});
			d.showModal();
		}else{
			ajax_get(_this,target);
		}
		return false;
	});
	$(document).on("click",".ajax-post",function(){
		var target,query,form;
		var target_form = $(this).attr('target-form');
		var _this = $(this);
		var nead_confirm=false;
		var text = $(this).html() || $(this).val();

		var ico="ask";
		if(_this.attr("font_ico")){
			var ico=_this.attr("font_ico");
		}
		if( (_this.attr('type')=='submit') || (target = _this.attr('href')) || (target = _this.attr('url')) ){
			form = $('.'+target_form);

			if($(this).attr('time')){
				var time=$(this).attr('time');
			}else{
				var time=2000;
			}
			if ($(this).attr('hide-data') === 'true'){
				form = $('.hide-data');
				query = form.serialize();
				ajax_post(_this,target,query);
			}else if (form.get(0)==undefined){
				return false;
			}else if ( form.get(0).nodeName=='FORM' ){
				if ( $(this).hasClass('confirm') ) {
					if($(this).attr('tips')){
						var msg=$(this).attr('tips');
					}else{
						var msg='确认要执行该操作吗?';
					}
					var d=dialog({
						fixed: true,
						title:"系统提示",
						content:'<div class="font_ico '+ico+'">'+msg+'</div>',
						ok:function(){
							if(_this.attr('url') !== undefined){
								target = _this.attr('url');
							}else{
								target = form.get(0).action;
							}
							var query=form.serialize();
							ajax_post(_this,target,query);
						},
						cancel:function(){
							d.close().remove();
							return false;
						},
						lock:true
					});
					d.showModal();
				}else{
					if(_this.attr('url') !== undefined){
						target = _this.attr('url');
					}else{
						target = form.get(0).action;
					}
					var query=form.serialize();
					ajax_post(_this,target,query);
				}
			}else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {
				form.each(function(k,v){
					if(v.type=='checkbox' && v.checked==true){
						nead_confirm = true;
					}
				})
				if ( nead_confirm && $(this).hasClass('confirm') ) {
					if($(this).attr('tips')){
						var msg=$(this).attr('tips');
					}else{
						var msg='确认要执行该操作吗?';
					}
					var d=dialog({
						fixed: true,
						title:"系统提示",
						content:'<div class="font_ico ask">'+msg+'</div>',
						ok:function(){
							query = form.serialize();
							ajax_post(_this,target,query);
						},
						cancel:function(){
							d.close().remove();
							return false;
						},
						lock:true
					});
					d.showModal();
				}else{
					query = form.serialize();
					ajax_post(_this,target,query);
				}
			}else{
				if ( $(this).hasClass('confirm') ) {
					if($(this).attr('tips')){
						var msg=$(this).attr('tips');
					}else{
						var msg='确认要执行该操作吗?';
					}
					var d=dialog({
						fixed: true,
						title:"系统提示",
						content:'<div class="font_ico ask">'+msg+'</div>',
						ok:function(){
							query = form.find('input,select,textarea').serialize();
							ajax_post(_this,target,query);
						},
						cancel:function(){
							d.close().remove();
							return false;
						},
						lock:true
					});
					d.showModal();
				}else{
					query = form.find('input,select,textarea').serialize();
					ajax_post(_this,target,query);
				}
				
			}
		}
		return false;
	});
	
	//弹窗
	$(document).on("click",".dialog",function(event){
		var c_this=$(this);
		event.preventDefault();
		var padding=40;
		if(c_this.attr('padding')){
			padding=c_this.attr('padding');
		}
		var myDialog = dialog({
			title:c_this.attr('title'),
			padding:padding,
			lock:true
		});
		$.ajax({
			url:$(this).attr('href'),
			success: function(data) {
				myDialog.content(data);// 填充对话框内容
				myDialog.showModal();
			}
		});
	})
	$(document).on("click",".check_all",function(event){
		$(this).parents("table").find(".ids").prop("checked", this.checked);
		if(this.checked){
			$(".ids").parents("tr").find("td").addClass("selected");
		}else{
			$(".ids").parents("tr").find("td").removeClass("selected");
		}
	});
	$(document).on("click",".ids",function(event){
		event.stopPropagation();
		var _this=$(this);
		var option = $(".ids");
		if($(this).prop("checked")){
			$(this).parents('tr').find("td").addClass("selected");
			$(this).prop("checked",true);
		}else{
			$(this).parents('tr').find("td").removeClass("selected");
			$(this).prop("checked",false);
		}
	});
});
function ajax_get(obj, target) {
    if (obj.attr('time')) {
        var time = obj.attr('time')
    } else {
        var time = 2000
    } if ((target = obj.attr('href')) || (target = obj.attr('url'))) {
        $.get(target).success(function(data) {
            var callback = false;
            try {
                if (obj.attr("func") != 'undefined') {
                    var funcSuffix = 'ajaxGetCallback' + obj.attr("func");
                    if (typeof(eval(funcSuffix)) == "function") {
                        callback = eval(funcSuffix + "(obj,data.status,data.info)");
                        if (!callback) {
                            return false
                        }
                    }
                }
            } catch (e) {}
            if (data.status == 1) {
                if (data.url) {
                    location.href = data.url
                } else {
                    location.reload()
                }
            } else {
                tips(data.info, 2000, "error");
                setTimeout(function() {
                    obj.addClass('disable').attr('autocomplete', 'off').prop('disabled', false)
                }, time)
            }
        })
    }
}

function ajax_post(obj, target, query) {
    if (obj.attr('time')) {
        var time = obj.attr('time')
    } else {
        var time = 2000
    }
    obj.addClass('disable').attr('autocomplete', 'off').prop('disabled', true);
    $.post(target, query).success(function(data) {
        var callback = false;
        try {
            if (obj.attr("func") != 'undefined') {
                var funcSuffix = 'ajaxPostCallback' + obj.attr("func");
                if (typeof(eval(funcSuffix)) == "function") {
                    callback = eval(funcSuffix + "(obj,data.status,data.info)");
                    if (!callback) {
                        return false
                    }
                }
            }
        } catch (e) {}
        if (data.status == 1) {
            if (data.url) {
                location.href = data.url
            } else {
                location.reload()
            }
        } else {
            tips(data.info, 2000, "error");
            setTimeout(function() {
                obj.removeClass('disable').attr('autocomplete', 'off').prop('disabled', false)
            }, time)
        }
    })
}

/**
 * js提示弹窗
 * 参数解释
 * 		info	: 提示内容
 * 		time	: 显示时间，单位为毫秒，1000毫秒为1秒
 * 		status	: 显示状态（success,error,warn,ask,tips）
 * 
 * @author MyMelody <1753290024@qq.com>
 */
function tips(info, time, status) {
    var d = dialog({
        fixed: true,
        content: '<div class="font_ico ' + status + '">' + info + '</div>',
        lock: true
    });
    d.showModal();
    setTimeout(function() {
        d.close().remove()
    }, time)
}

/*
 * 将字符串根据指定字符分隔成为数组，使用方法和php的explode一样
 * 参数解释：
 *     separator：指定用什么字符来分割
 *     string：   指定分割什么字符串
 * 示例：
 *     var str="www.cnsunrun.com";
 *     var str_arr=explode('.',str);
 *     console.log(str_arr);
 *     在调试工具的console里会看到输出的数组：["www", "cnsunrun", "com"]
 *
 * @author MyMelody <1753290024@qq.com>
 */
function explode(separator, string) {
    string = new String(string);
    separator = new String(separator);
    if (separator == "undefined") {
        separator = " :;"
    }
    fixedExplode = new Array(1);
    currentElement = "";
    count = 0;
    for (x = 0; x < string.length; x++) {
        str = string.charAt(x);
        if (separator.indexOf(str) != -1) {
            fixedExplode[count] = currentElement;
            count++;
            currentElement = ""
        } else {
            currentElement += str
        }
    }
    fixedExplode[count] = currentElement;
    return fixedExplode
}

//数组合并
function array_merge(des, src, override) {
    if (src instanceof Array) {
        for (var i = 0, len = src.length; i < len; i++) array_merge(des, src[i], override)
    }
    for (var i in src) {
        if (override || !(i in des)) {
            des[i] = src[i]
        }
    }
    return des
}
/*
 * 常用于传入一个完整的文件路径，返回文件名与后缀
 */
function basename(str) {
    var str2 = "/";
    var s = str.lastIndexOf(str2);
    if (s == -1) {
        str2 = "\\";
        var s = str.lastIndexOf(str2)
    }
    if (s == -1) {
        alert("字符串非法")
    } else {
        return (str.substring(s + 1, str.length))
    }
    return ""
}

/** 
 * number_format 
 * 
 * @param int or float number 
 * @param int          decimals 
 * @param string       dec_point 
 * @param string       thousands_sep 
 * @return string
 * example 1: number_format(1234.56);  
       returns 1: '1,235'  
 * example 2: number_format(1234.56, 2, ',', ' ');  
       returns 2: '1 234,56'  
 * example 3: number_format(1234.5678, 2, '.', '');  
       returns 3: '1234.57'  
 * example 4: number_format(67, 2, ',', '.');      
       returns 4: '67,00'  
 * example 5: number_format(1000);  
       returns 5: '1,000'  
 * example 6: number_format(67.311, 2);  
       returns 6: '67.31'  
 * example 7: number_format(1000.55, 1);  
       returns 7: '1,000.6'  
 * example 8: number_format(67000, 5, ',', '.');  
       returns 8: '67.000,00000'  
 * example 9: number_format(0.9, 0);      
       returns 9: '1'  
 * example 10: number_format('1.20', 2);  
       returns 10: '1.20'  
 * example 11: number_format('1.20', 4);  
       returns 11: '1.2000'      
 * example 12: number_format('1.2000', 3);  
       returns 12: '1.200'  
 */  
function number_format(number, decimals, dec_point, thousands_sep) {
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function(n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k
        };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0')
    }
    return s.join(dec)
}

function tabChange(obj, parent) {
    var tab_box = $(".tab_con", $(obj).parents(parent));
    var titleObj = $(obj).parents(".tab_title");
    var index = $(".tab", titleObj).index(obj);
    $(obj).parents(".tab_title").find(".tab").removeClass("cur").eq(index).addClass("cur");
    tab_box.find(".tab").fadeOut().eq(index).fadeIn()
}
