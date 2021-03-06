<?php
class Qrctils {
	// 创建二维码
	public static function createImg($content, $qrc_level = 'H', $qrc_size = 10, $logo = FALSE, $savePath = null) {
		include_once 'phpqrcode.php';
		// if($savePath)
		$QR = QRcode::png ( $content, $savePath, $qrc_level, $qrc_size, 2 );
		if ($logo !== FALSE) {
			$QR = imagecreatefromstring ( file_get_contents ( $savePath ) );
			$logo = imagecreatefromstring ( file_get_contents ( $logo ) );
			$QR_width = imagesx ( $QR ); // 二维码图片宽度
			$QR_height = imagesy ( $QR ); // 二维码图片高度
			$logo_width = imagesx ( $logo ); // logo图片宽度
			$logo_height = imagesy ( $logo ); // logo图片高度
			$logo_qr_width = $QR_width / 5;
			$scale = $logo_width / $logo_qr_width;
			$logo_qr_height = $logo_height / $scale;
			$from_width = ($QR_width - $logo_qr_width) / 2;
			// 重新组合图片并调整大小
			imagecopyresampled ( $QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
		}
		return $QR;
	}
}
	