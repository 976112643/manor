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
    weekday[0] = "������"
    weekday[1] = "����һ"
    weekday[2] = "���ڶ�"
    weekday[3] = "������"
    weekday[4] = "������"
    weekday[5] = "������"
    weekday[6] = "������"
    
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
