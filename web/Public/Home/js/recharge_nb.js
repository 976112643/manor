window.onload=function(){
	var button=document.getElementById("pic_button_sw");
	var link=button.getElementsByTagName("a");
	for(var i=0;i<link.length;i++){
		link[i].id=i;//为link里的li设置id
		link[i].onclick=function(){
			for (var j = 0; j < link.length; j++) {
				link[j].className="ashyborder_sw";
			}
			this.className="";//避免选中状态出现
			this.className="redborder_sw";//根据鼠标划过位置改变
		}
	}
}
