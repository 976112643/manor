<?php
include 'config.php';
include 'db.php';
include 'utils.php';
?>
<?php

if (empty ( $_FILES ["mypic"] )) {
	exit ();
}
$names = $_FILES ["mypic"] ["name"];
$tmp_names = $_FILES ["mypic"] ["tmp_name"];
for($i = 0; $i < count ( $names ); $i ++) {
	$picname = $names [$i];
	$tmp_name = $tmp_names [$i];
	$type = strstr ( $picname, '.' );
	$pics = getMillisecond () . $type;
	// 上传路径
	$pic_path = "upload/" . $pics;
	move_uploaded_file ( $tmp_name, $pic_path );
	$result = db::insert ( UPLOAD_FILE_TABLE, array (
			"name" => $picname,
			"localpath" => $pic_path,
			"type" => $type 
	) );
	var_dump ( $result );
}
?> 