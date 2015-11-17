<?php

namespace Home\Controller;

use Think\Controller;
/*
 * 文件上传
 */
class UploadController extends Controller {
	public function index() {
		// 设置请求错误的响应头,在操作正常结束时修改为请求成功的请求头
		header ( "HTTP/1.0 500 Internal Server Error" );
		header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
		header ( "Last-Modified: " . gmdate ( "D, d M Y H:i:s" ) . " GMT" );
		header ( "Cache-Control: no-store, no-cache, must-revalidate" );
		header ( "Cache-Control: post-check=0, pre-check=0", false );
		header ( "Pragma: no-cache" );
		$requestMethod = I ( 'server.REQUEST_METHOD' );
		$userId = I ( 'userid' );
		if ($requestMethod != 'POST' || ! $userId) {
			die ( "非法操作" );
		}
		if (! empty ( I ( 'debug' ) )) {
			$random = rand ( 0, intval ( I ( 'debug' ) ) );
			if ($random === 0) {
				exit ();
			}
		}
		
		@set_time_limit ( 0 );
		$targetDir = 'upload_tmp'; // 上传的临时目录
		$uploadDir = 'upload'; // 上传文件的存放目录
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
		                        
		// Create target dir
		if (! file_exists ( $targetDir )) {
			@mkdir ( $targetDir );
		}
		
		// Create target dir
		if (! file_exists ( $uploadDir )) {
			@mkdir ( $uploadDir );
		}
		if (I ( "name" )) {
			$fileName = I ( "name" );
		} elseif (! empty ( $_FILES )) {
			$fileName = $_FILES ["file"] ["name"];
		}
		// 使用用户Id+文件名md5加密的方式保存文件,乱码什么的就不好了
		$save_Name = $userId . "_" . md5 ( pathinfo ( $fileName, PATHINFO_BASENAME ) ) . "." . pathinfo ( $fileName, PATHINFO_EXTENSION );
		if (! isset ( $fileName )) {
			$fileName = $save_Name;
		}
		error_log ( json_encode ( $_FILES ) );
		error_log ( json_encode ( $_REQUEST ) );
		$fileName = iconv ( 'UTF-8', 'GB2312', $fileName ); // 转编码
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $save_Name; // 文件缓存路径
		$uploadPath = $uploadDir . DIRECTORY_SEPARATOR . $save_Name; // 文件上传路径
		                                                             // Chunking might be enabled
		$chunk = I ( "chunk" ) ? intval ( I ( "chunk" ) ) : 0;
		$chunks = I ( "chunks" ) ? intval ( I ( "chunks" ) ) : 1;
		
		// Remove old temp files
		if ($cleanupTargetDir) {
			if (! is_dir ( $targetDir ) || ! $dir = opendir ( $targetDir )) {
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}' );
			}
			
			while ( ($file = readdir ( $dir )) !== false ) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
				
				// If temp file is current file proceed to the next
				if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
					continue;
				}
				
				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match ( '/\.(part|parttmp)$/', $file ) && (@filemtime ( $tmpfilePath ) < time () - $maxFileAge)) {
					@unlink ( $tmpfilePath );
				}
			}
			closedir ( $dir );
		}
		
		// Open temp file
		if (! $out = @fopen ( "{$filePath}_{$chunk}.parttmp", "wb" )) {
			die ( '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}' );
		}
		
		if (! empty ( $_FILES )) {
			if ($_FILES ["file"] ["error"] || ! is_uploaded_file ( $_FILES ["file"] ["tmp_name"] )) {
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}' );
			}
			
			// Read binary input stream and append it to temp file
			if (! $in = @fopen ( $_FILES ["file"] ["tmp_name"], "rb" )) {
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}' );
			}
		} else {
			if (! $in = @fopen ( "php://input", "rb" )) {
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}' );
			}
		}
		
		while ( $buff = fread ( $in, 4096 ) ) {
			fwrite ( $out, $buff );
		}
		
		@fclose ( $out );
		@fclose ( $in );
		
		rename ( "{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part" );
		
		$index = 0;
		$done = true;
		for($index = 0; $index < $chunks; $index ++) {
			if (! file_exists ( "{$filePath}_{$index}.part" )) {
				$done = false;
				break;
			}
		}
		if ($done) {
			if (! $out = @fopen ( $uploadPath, "wb" )) {
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}' );
			}
			
			if (flock ( $out, LOCK_EX )) {
				for($index = 0; $index < $chunks; $index ++) {
					if (! $in = @fopen ( "{$filePath}_{$index}.part", "rb" )) {
						break;
					}
					
					while ( $buff = fread ( $in, 4096 ) ) {
						fwrite ( $out, $buff );
					}
					
					fclose ( $in );
					@unlink ( "{$filePath}_{$index}.part" );
				}
				flock ( $out, LOCK_UN );
			}
			@fclose ( $out );
			//保存文件信息到数据库中
			$this->save2sql ( $fileName, $save_Name, $userId, md5_file ( $uploadPath ) );
		}
		
		header ( "HTTP/1.0 200 OK" );
		die ( '{"jsonrpc" : "2.0", "result" : null, "id" : "id"}' );
	}
	protected function save2sql($fileName, $save_Name, $userId, $md5_value) {
		$Form = D ( 'upload_files' );
		$data ['name'] = $fileName;
		$data ['localpath'] = $save_Name;
		$data ['type'] = pathinfo ( $fileName, PATHINFO_EXTENSION );
		$data ['user_id'] = $userId;
		$data ['md5_value'] = $md5_value;
		$Form->add ( $data );
	}
}