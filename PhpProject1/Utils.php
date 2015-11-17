<?php
    include './Contacts.php';
    function  tempFile(){ 
        $temp=$_SERVER["PHP_SELF"];
        $temp=str_getcsv($temp,"/");
        $temp=str_getcsv($temp[count($temp)-1],".")[0];
        return TEMP_DIR."/".$temp;
    }


    function get($key) {
        if (filter_has_var(INPUT_GET, $key)){
            return trim($_GET[$key]);
        }
        if (filter_has_var(INPUT_POST, $key)) {
            return trim($_POST[$key]);
        }
        return "";
    }

    /* 打印数组 */

    function printArr($arr) {

        if (!isset($arr)) {
            echo '----------------------------';
        } else {
            echo "<pre>";
            echo var_export($arr, TRUE);
            echo "</pre>";
        }
    }

    /* 打印文件中内容 */

    function printFile($fileName) {
        $fp = fopen($fileName, "rb");
        if ($fp) {
            while (!feof($fp)) {
                $content = fgets($fp);
                echo $content . "<br />";
            }
        }
    }

    /* 删除文件 */

    function delete($fileName) {
        if (file_exists($fileName)) {
            return @unlink($fileName);
        } else {
            return TRUE;
        }
    }

    /* 写入内容到文件中 */

    function writeFile($fileName, $content, $append = FALSE) {
        $mode = $append ? "ab" : "b";
        $fp = fopen($fileName, $mode);
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                if (is_numeric($key)) {
                    fwrite($fp, $value);
                } else {
                    fwrite($fp, $key . "=" . $value);
                }
            }
        } else {
            fwrite($fp, $content);
        }
        fclose($fp);
    }

    /* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

