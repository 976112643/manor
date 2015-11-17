<?php
    /*生成验证码*/
    header("Content-Type:image/png");
    $img = getCodeImg(4);
    imagepng($img);
    imagedestroy($img);
     function getCodeImg($num,$type=2){
        $W=$num*20;
        $H=30;
        $code=  getCode($num,$type);
        $img=  imagecreatetruecolor($W, $H);
        $bg=  imagecolorallocate($img, rand(155,255), rand(155,255), rand(155,255));
        $borderColor=  imagecolorallocate($img, 0, 0, 0);
        imagefill($img, 0, 0, $bg);
        imagerectangle($img,0, 0, $W-1, $H-1, $borderColor);
        /*添加干扰点*/
        for ($i = 0; $i < 200; $i++) {
            imagesetpixel($img, rand(0, $W), rand(0, $H), imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255)));
        }
        /*添加干扰线*/
        for ($i = 0,$n=rand(2, 5); $i <$n ; $i++) {
             imageline($img, rand(0, $W), rand(0, $H),rand(0, $W), rand(0, $H),  imagecolorallocate($img, rand(0, 255), rand(0, 255),rand(0, 255)));
        }
        /*绘制文字*/
        for ($i = 0; $i < $num; $i++) {
              $wordColor=  imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
            imagettftext($img, 18, rand(-40, 40), 8+(18*$i), 24, $wordColor, "font\msyh.ttc", $code[$i]);
        }
        return $img;
        
    }
    /*获取num个随机数的字符串*/
    function getCode($num=4,$type=0){
        $chars="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $types=array(9,35,  strlen($chars)-1);
        $code="";
        for ($i = 0; $i < $num; $i++) {
            $code.=$chars[rand(0, $types[$type])];
        }
        return $code;
//        echo strlen($chars);
    }
    /* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

