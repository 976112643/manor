<!DOCTYPE HTML>
<html>
<head>
<script type="text/javascript" src="./js/jquery.js"></script>
<script type="text/javascript" src="./js/base/utils.js">
	
</script>
<script type="text/javascript">
	
</script>
<meta charset="utf-8">
<title>文件上传</title>
<style type="text/css">
.demo {
	width: 500px;
	margin: 50px auto
}

#main {
	text-align: center;
}

#drop_area {
	width: 100%;
	height: 100px;
	border: 3px dashed silver;
	line-height: 100px;
	text-align: center;
	font-size: 36px;
	color: #d3d3d3
}

#preview {
	width: 500px;
	overflow: hidden
}
</style>
</head>

<body>
	<div id="main">
		<h2 class="top_title">文件拽上传</h2>
		<div class="demo">
			<div id="drop_area">将图片拖拽到此区域</div>
			<input type="file" id="upload_btn"> 上传文件 </input>
			<div id="preview"></div>
		</div>
	</div>
	<script type="text/javascript">
		function setImagePreview(docObj, imgObjPreview) {
			//var docObj = document.getElementById("drop_area");   
			if (docObj.files && docObj.files[0]) {
				//火狐下，直接设img属性 
				imgObjPreview.style.display = 'block';
				imgObjPreview.style.width = '63px';
				imgObjPreview.style.height = '63px';
				//imgObjPreview.src = docObj.files[0].getAsDataURL(); 
				if (isChrome() || isSafari()) {
					imgObjPreview.src = window.webkitURL
							.createObjectURL(docObj.files[0]);
				} else {
					imgObjPreview.src = window.URL
							.createObjectURL(docObj.files[0]);
				}
			} else {
				//IE下，使用滤镜 
				//docObj.select(); 
				//docObj.blur(); 
				var imgSrc = document.selection.createRange().text;
				var localImagId = document.getElementById("localImag");
				//必须设置初始大小 
				localImagId.style.width = "63px";
				localImagId.style.height = "63px";
				//图片异常的捕捉，防止用户修改后缀来伪造图片 
				try {
					localImagId.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
					localImagId.filters
							.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc;
				} catch (e) {
					alert("您上传的图片格式不正确，请重新选择！");
					return false;
				}
				imgObjPreview.style.display = 'none';
				document.selection.empty();
			}
			return true;
		}
		function state_Change() {
			if (xhr.readyState == 4) {// 4 = "loaded"
				if (xhr.status == 200) {
					alert("" + xhr.responseText);
				}
			}
		}
		$(function() {
			//阻止浏览器默认行。
			$(document).on({
				dragleave : function(e) { //拖离
					e.preventDefault();
				},
				drop : function(e) { //拖后放
					e.preventDefault();
				},
				dragenter : function(e) { //拖进
					e.preventDefault();
				},
				dragover : function(e) { //拖来拖去
					e.preventDefault();
				}
			});

			//================上传的实现
			var box = document.getElementById('drop_area'); //拖拽区域
			if (isIE()) {
			}
			box.addEventListener("drop", function(e) {
				e.preventDefault(); //取消默认浏览器拖拽效果

				var fileList = e.dataTransfer.files; //获取文件对象
				//检测是否是拖拽文件到页面的操作
				if (fileList.length == 0) {
					return false;
				}
				//检测文件是不是图片
				alert(fileList[0].type);
				if (fileList[0].type.indexOf('image') === -1) {
					alert("您拖的不是图片！");
					return false;
				}

				//拖拉图片到浏览器，可以实现预览功能

				var filename = fileList[0].name; //图片名称
				var filesize = Math.floor((fileList[0].size) / 1024);
				if (filesize > 1024 * 10) {
					alert("上传大小超过10M.可能需要花费点时间");
					//return false;
				}
				//alert(filesize);
				var str = "<img id='img1'><p>图片名称：" + filename + "</p><p>大小："
						+ filesize + "KB</p>";
				$("#preview").html(str);
				var imageview = document.getElementById("img1");
				setImagePreview(e.dataTransfer, imageview);

				//上传
				xhr = new XMLHttpRequest();
				xhr.onreadystatechange = state_Change;
				xhr.open("post", "upload.php", true);
				xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

				var fd = new FormData();
				alert("====" + fileList.length);
				for (var i = 0; i < fileList.length; i++) {
					//alert("===="+fileList[0]);
					fd.append("mypic[]", fileList[i]);

				}

				xhr.send(fd);

			}, false);
		});
	</script>
</body>
</html>