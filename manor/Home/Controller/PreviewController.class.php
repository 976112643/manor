<?php

namespace Home\Controller;

use Think\Controller;

class PreviewController extends Controller {
	public function qrcimg() {
		$value = I ( 'content' ); // 二维码内容
		if (! $value) {
			die ( "操作非法" );
		}
		Vendor ( 'phpqrc.Qrcutils' );
		$logo = './images/icon.png'; // 准备好的logo图片
		$QR = './images/qrcode.png'; // 已经生成的原始二维码图
		$errorCorrectionLevel = I ( 'qrc_level', 'H' ); // 容错级别
		$matrixPointSize = I ( 'qrc_size', 10 ); // 生成图片大小
		//创建二维码
		$img = \Qrctils::createImg ( $value, $errorCorrectionLevel, $matrixPointSize, $logo, $QR );
		// 输出图片
		Header ( "Content-type: image/png" );
		ImagePng ( $img );
	}
	// public function qrcimg(){
	// $value = I('content'); // 二维码内容
	// if(!$value){
	// die("操作非法");
	
	// }
	// Vendor('phpqrc.phpqrcode');
	// $errorCorrectionLevel = I('qrc_level','H'); // 容错级别
	// $matrixPointSize = I('qrc_size',10); // 生成图片大小
	// // 生成二维码图片
	// \QRcode::png ( $value, './images/qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2 );
	// $logo = './images/icon.png'; // 准备好的logo图片
	// $QR = './images/qrcode.png'; // 已经生成的原始二维码图
	
	// if ($logo !== FALSE) {
	// $QR = imagecreatefromstring ( file_get_contents ( $QR ) );
	// $logo = imagecreatefromstring ( file_get_contents ( $logo ) );
	// $QR_width = imagesx ( $QR ); // 二维码图片宽度
	// $QR_height = imagesy ( $QR ); // 二维码图片高度
	// $logo_width = imagesx ( $logo ); // logo图片宽度
	// $logo_height = imagesy ( $logo ); // logo图片高度
	// $logo_qr_width = $QR_width / 5;
	// $scale = $logo_width / $logo_qr_width;
	// $logo_qr_height = $logo_height / $scale;
	// $from_width = ($QR_width - $logo_qr_width) / 2;
	// // 重新组合图片并调整大小
	// imagecopyresampled ( $QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
	// }
	// // 输出图片
	// Header ( "Content-type: image/png" );
	// ImagePng ( $QR );
	// }
	public function index() {
		$DIR = 'preview';
		// Create target dir
		if (! file_exists ( $DIR )) {
			@mkdir ( $DIR );
		}
		
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds
		
		if ($cleanupTargetDir) {
			if (! is_dir ( $DIR ) || ! $dir = opendir ( $DIR )) {
				die ( '{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}' );
			}
			
			while ( ($file = readdir ( $dir )) !== false ) {
				$tmpfilePath = $DIR . DIRECTORY_SEPARATOR . $file;
				
				// Remove temp file if it is older than the max age and is not the current file
				if (@filemtime ( $tmpfilePath ) < time () - $maxFileAge) {
					@unlink ( $tmpfilePath );
				}
			}
			closedir ( $dir );
		}
		
		$src = file_get_contents ( 'php://input' );
		
		if (preg_match ( "#^data:image/(\w+);base64,(.*)$#", $src, $matches )) {
			
			$previewUrl = sprintf ( "%s://%s%s", isset ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER ['HTTP_HOST'], $_SERVER ['REQUEST_URI'] );
			$previewUrl = str_replace ( "preview.php", "", $previewUrl );
			
			$base64 = $matches [2];
			$type = $matches [1];
			if ($type === 'jpeg') {
				$type = 'jpg';
			}
			
			$filename = md5 ( $base64 ) . ".$type";
			$filePath = $DIR . DIRECTORY_SEPARATOR . $filename;
			
			if (file_exists ( $filePath )) {
				die ( '{"jsonrpc" : "2.0", "result" : "' . $previewUrl . 'preview/' . $filename . '", "id" : "id"}' );
			} else {
				$data = base64_decode ( $base64 );
				file_put_contents ( $filePath, $data );
				die ( '{"jsonrpc" : "2.0", "result" : "' . $previewUrl . 'preview/' . $filename . '", "id" : "id"}' );
			}
		} else {
			die ( '{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "un recoginized source"}}' );
		}
	}
}