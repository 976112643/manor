<?php
//载入ucpass类
require_once('lib/Ucpaas.class.php');

//初始化必填
$options['accountsid']='38542bb2c01501e1db92b44c8bca8810';
$options['token']='434c3a1cab14d7391828cb52ce3a11bd';


//初始化 $options必填
$ucpass = new Ucpaas($options);

//开发者账号信息查询默认为json或xml

echo $ucpass->getDevinfo('json');

//短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
$appId = "f0b1c14f5a88401297bed6ede4872b08";
//$to = "13026139128";
//$to = "15991431396";
$to = "18210450103";
//$to = "15910813505";
$templateId = "10199";
$code = mt_rand(100000,900000);
$param="{$code},1";
$res = $ucpass->templateSMS($appId,$to,$templateId,$param);
echo "res:\n";
var_dump($res);

