<?php
function json_result($info,$status=1,$msg='')
/**
 * 获得当前毫秒数表示的时间
 * @return string
 */
 function getMillisecond() {
	$temps = explode ( ' ', microtime () );
	list ( $t1, $t2 ) = $temps;
	// var_dump($temps);
	return "" . ( float ) sprintf ( '%.0f', (floatval ( $t1 ) + floatval ( $t2 )) * 1000 );
}
/**
 * 获取访问者IP
 * */
function getIP() {
	if (! empty ( $_SERVER ["HTTP_CLIENT_IP"] )) {
		$cip = $_SERVER ["HTTP_CLIENT_IP"];
	} elseif (! empty ( $_SERVER ["HTTP_X_FORWARDED_FOR"] )) {
		$cip = $_SERVER ["HTTP_X_FORWARDED_FOR"];
	} elseif (! empty ( $_SERVER ["REMOTE_ADDR"] )) {
		$cip = $_SERVER ["REMOTE_ADDR"];
	} else {
		$cip = "无法获取！";
	}
	return $cip;
}