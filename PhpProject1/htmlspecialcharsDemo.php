<html>
    <title>htmlspecialchars使用示例</title>
    <body>
        <?php
            include './Utils.php';
            $value = get("content");
            if (!empty($value)) {
                file_put_contents(tempFile(), $value);
                echo '处理前:[' . $_POST['content'] . "]<br/>";
                echo '处理后:[' . htmlspecialchars($_POST['content']) . "]<br/>";
            }
        ?>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">

            <textarea name="content"
                      cols="100"
                      rows="20"
                      >
                          <?php
                              if (empty($value)) {
                                  $value = @file_get_contents(tempFile());
                                  if (empty($value)) {
                                      $value = get("content");
                                      if (empty($value)) {
                                          $value = "输入一些东西,来比较htmlspecialchars方法过滤前后的区别";
                                      }
                                  }
                              }
                              echo$value;
                          ?>

            </textarea>
            <br/>
            <input type="submit" value="确定"                   width="100px"
                   height="40px"
                   />
        </form>
    </body>
    <?php ?>
</html>


