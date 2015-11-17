<?php

    /* 所有文件存放的路径 */
    define("TEMP_DIR", $_SERVER['DOCUMENT_ROOT'] . "/temp_files");
    define("KEY_ERR_MSG", "err_msg");

    function turnErr($errMess) {
        echo "" . KEY_ERR_MSG . "=" .$errMess;
    }

