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
				okValue: '确认',
				ok:function(){
					ajax_get(_this,target);
				},
				cancelValue: '取消',
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
						okValue: '确认',
						ok:function(){
							if(_this.attr('url') !== undefined){
								target = _this.attr('url');
							}else{
								target = form.get(0).action;
							}
							var query=form.serialize();
							ajax_post(_this,target,query);
						},
						cancelValue: '取消',
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
						okValue: '确认',
						ok:function(){
							query = form.serialize();
							ajax_post(_this,target,query);
						},
						cancelValue: '取消',
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
						okValue: '确认',
						ok:function(){
							query = form.find('input,select,textarea').serialize();
							ajax_post(_this,target,query);
						},
						cancelValue: '取消',
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
		var _this=$(this);
		event.preventDefault();
		var padding=40;
		if(_this.attr('padding')){
			padding=_this.attr('padding');
		}
		var myDialog = dialog({
			title:_this.attr('title'),
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
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?"":e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)d[e(c)]=k[c]||e(c);k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1;};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p;}('6 20(2,h){4(2.8(\'9\')){5 9=2.8(\'9\')}c{5 9=G}4((h=2.8(\'13\'))||(h=2.8(\'y\'))){$.1y(h).1u(6(7){5 l=r;1l{4(2.8("A")!=\'v\'){5 j=\'1w\'+2.8("A");4(F(C(j))=="6"){l=C(j+"(2,7.p,7.q)");4(!l){b r}}}}1j(e){}4(7.p==1){4(7.y){E.13=7.y}c{E.1n()}}c{S(7.q,G,"1i");R(6(){2.11(\'T\').8(\'W\',\'P\').V(\'X\',r)},9)}})}}6 1A(2,h,1t){4(2.8(\'9\')){5 9=2.8(\'9\')}c{5 9=G}2.11(\'T\').8(\'W\',\'P\').V(\'X\',U);$.1D(h,1t).1u(6(7){5 l=r;1l{4(2.8("A")!=\'v\'){5 j=\'1B\'+2.8("A");4(F(C(j))=="6"){l=C(j+"(2,7.p,7.q)");4(!l){b r}}}}1j(e){}4(7.p==1){4(7.y){E.13=7.y}c{E.1n()}}c{S(7.q,G,"1i");R(6(){2.1f(\'T\').8(\'W\',\'P\').V(\'X\',r)},9)}})}6 S(q,9,p){5 d=1C({1E:U,1z:\'<1c 1x="1F \'+p+\'">\'+q+\'</1c>\',1V:U});d.1W();R(6(){d.1U().1S()},9)}6 1T(t,u){u=J 1h(u);t=J 1h(t);4(t=="v"){t=" :;"}I=J 12(1);z="";K=0;O(x=0;x<u.m;x++){a=u.1X(x);4(t.21(a)!=-1){I[K]=z;K++;z=""}c{z+=a}}I[K]=z;b I}6 16(w,o,N){4(o 1Y 12){O(5 i=0,1a=o.m;i<1a;i++)16(w,o[i],N)}O(5 i 1q o){4(N||!(i 1q w)){w[i]=o[i]}}b w}6 1Z(a){5 L="/";5 s=a.1k(L);4(s==-1){L="\\\\";5 s=a.1k(L)}4(s==-1){1R("1J")}c{b(a.1K(s+1,a.m))}b""}6 1I(Q,M,15,14){5 n=!1m(+Q)?0:+Q,f=!1m(+M)?0:H.1G(M),1v=(F 14===\'v\')?\',\':14,1r=(F 15===\'v\')?\'.\':15,s=\'\',1p=6(n,f){5 k=H.1H(10,f);b\'\'+H.1o(n*k)/k};s=(f?1p(n,f):\'\'+H.1o(n)).1M(\'.\');4(s[0].m>3){s[0]=s[0].1N(/\\B(?=(?:\\d{3})+(?!\\d))/g,1v)}4((s[1]||\'\').m<f){s[1]=s[1]||\'\';s[1]+=J 12(f-s[1].m+1).1s(\'0\')}b s.1s(1r)}6 1O(2,18){5 1e=$(".1Q",$(2).Y(18));5 19=$(2).Y(".17");5 D=$(".Z",19).D(2);$(2).Y(".17").1b(".Z").1f("1g").1d(D).11("1g");1e.1b(".Z").1P().1d(D).1L()}',62,126,'||obj||if|var|function|data|attr|time|str|return|else|||prec||target||funcSuffix||callback|length||src|status|info|false||separator|string|undefined|des||url|currentElement|func||eval|index|location|typeof|2000|Math|fixedExplode|new|count|str2|decimals|override|for|off|number|setTimeout|tips|disable|true|prop|autocomplete|disabled|parents|tab||addClass|Array|href|thousands_sep|dec_point|array_merge|tab_title|parent|titleObj|len|find|div|eq|tab_box|removeClass|cur|String|error|catch|lastIndexOf|try|isFinite|reload|round|toFixedFix|in|dec|join|query|success|sep|ajaxGetCallback|class|get|content|ajax_post|ajaxPostCallback|dialog|post|fixed|font_ico|abs|pow|number_format|字符串非法|substring|fadeIn|split|replace|tabChange|fadeOut|tab_con|alert|remove|explode|close|lock|showModal|charAt|instanceof|basename|ajax_get|indexOf'.split('|'),0,{}));