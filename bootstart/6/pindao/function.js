var getDate = function(){
    var date_string = "";
    var d = new Date();
   // var year = d.getYear();
	var year = d.getFullYear();
    var month = (d.getMonth() + 1).toString();
    var date = d.getDate().toString();
    var hour = d.getHours().toString();
    var minute = d.getMinutes().toString();
    var second = d.getSeconds().toString();
    var weekday = new Array(7)
    weekday[0] = "星期日"
    weekday[1] = "星期一"
    weekday[2] = "星期二"
    weekday[3] = "星期三"
    weekday[4] = "星期四"
    weekday[5] = "星期五"
    weekday[6] = "星期六"
    
    if (month.length == 1) 
        month = "0" + month;
    if (date.length == 1) 
        date = "0" + date;
    date_string = year + "-" + month + "-" + date + "  " + weekday[d.getDay()];
	//alert(year);
    return date_string;
}

var $ = function(_id){
    return document.getElementById(_id);
}
