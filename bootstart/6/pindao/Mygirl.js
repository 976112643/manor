/*
*JS实现addClass,removeClass and togggleClass
*addClass 为dom添加class样式
*removeClass 为dom删除class样式
*toggleClass 存在删除样式，不存在添加样式
*hasClass 判断样式十分存在
*/


function hasClass(obj,cls){
	return obj.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));
}

function addClass(obj,cls){
	if (!this.hasClass(obj, cls)) obj.className += " " + cls;
}

function removeClass(obj,cls){
	if (hasClass(obj, cls)) {
		var reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
		obj.className = obj.className.replace(reg, ' ');
	}
}

function toggleClass(obj,cls){
	if(hasClass(obj,cls)){ 
		removeClass(obj, cls);
	}else{
		addClass(obj, cls);
	}
}

function testClass(num,title){
	//for()
	//alert("function in testClass");
	//alert(num);
	var obj = document.getElementById('channel'+num);
	//alert(title);
	if(title.indexOf("体验")>0){
		//alert("fun in if");
		addClass(obj,"mian");
	}else{
		//alert("fun in else");
		if(hasClass(obj,"mian")){
			removeClass(obj, "mian");
		}
	}
}