
function isIE() {
	return navigator.appName.indexOf("Microsoft Internet Explorer") != -1
			&& document.all;
}

function isIE6() {
	return navigator.userAgent.split(";")[1].toLowerCase().indexOf("msie 6.0") == "-1" ? false
			: true;
}

function isIE7() {
	return navigator.userAgent.split(";")[1].toLowerCase().indexOf("msie 7.0") == "-1" ? false
			: true;
}

function isIE8() {
	return navigator.userAgent.split(";")[1].toLowerCase().indexOf("msie 8.0") == "-1" ? false
			: true;
}

function isNN() {
	return navigator.userAgent.indexOf("Netscape") != -1;
}

function isOpera() {
	return navigator.appName.indexOf("Opera") != -1;
}

function isFF() {
	return navigator.userAgent.indexOf("Firefox") != -1;
}

function isChrome() {
	return navigator.userAgent.indexOf("Chrome") > -1;
}
function isSafari() {
	return navigator.userAgent.indexOf("Safari") > -1;
}

function bin2hex(s) {
	var i, l, o = '', n;
	s += '';
	for (i = 0, l = s.length; i < l; i++) {
		n = s.charCodeAt(i).toString(16);
		o += n.length < 2 ? '0' + n : n;
	}
	return o;
}
/*生成一个浏览器识别码*/
function getUid() {
	var canvas = document.createElement('canvas');
	var ctx = canvas.getContext('2d');
	var txt = 'author:WQ';   
	ctx.textBaseline = "top";
	ctx.font = "14px 'Arial'";
	ctx.textBaseline = "WQ";
	ctx.fillStyle = "#f60";
	ctx.fillRect(125, 1, 62, 20);  
	ctx.fillStyle = "#069";
	ctx.fillText(txt, 2, 15);
	ctx.fillStyle = "rgba(102, 204, 0, 0.7)";
	ctx.fillText(txt, 4, 17);
	var b64 = canvas.toDataURL().replace("data:image/png;base64,", "");
	var bin = atob(b64);
	var crc = bin2hex(bin.slice(-16, -12));
	console.log(crc);
	return crc;
}