<?php

namespace Home\Controller;

use Think\Controller;

class DownloadController extends Controller {
	public function index() {
		$PAHT="D:/htdocs/demo/";
		$file_id = I ( 'file_id' );
		$data = M ( 'upload_files' );
		$result = $data->find ( $file_id );
		$file = $result['localpath'];
		// 要用绝对路径，windows下比如d:\tmp\
		header("Content-type: application/octet-stream");
		header('Content-Disposition: attachment; filename="' . basename($result['name']) . '"');
		header("X-Sendfile:".$file);
		//echo json_encode ( $file );
		
		//echo json_encode ( $result );
	}
}
