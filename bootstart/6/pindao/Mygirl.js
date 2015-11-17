/*
*JSʵ��addClass,removeClass and togggleClass
*addClass Ϊdom���class��ʽ
*removeClass Ϊdomɾ��class��ʽ
*toggleClass ����ɾ����ʽ�������������ʽ
*hasClass �ж���ʽʮ�ִ���
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
	if(title.indexOf("����")>0){
		//alert("fun in if");
		addClass(obj,"mian");
	}else{
		//alert("fun in else");
		if(hasClass(obj,"mian")){
			removeClass(obj, "mian");
		}
	}
}