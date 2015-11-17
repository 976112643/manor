<?php
    include './Contacts.php';
    $err_msg = "未知错误!";
    if ($_GET[KEY_ERR_MSG]) {
        $err_msg = $_GET[KEY_ERR_MSG];
    }
?>
<span class="error"><?php echo $err_msg; ?></span>
<?php
	

?>
<?php
  
    /* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

