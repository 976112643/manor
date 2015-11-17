<?php
    include './Utils.php';
    include './Contacts.php';
    printArr($_FILES);
    if($_FILES['file']['error']>0){
        die;
    }
    echo '文件为空';
    
    
    


