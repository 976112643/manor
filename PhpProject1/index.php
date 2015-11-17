<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            require '.\Utils.php'; 
            $urls = array("文件上传" => "upload.xhtml",
                "htmlspecialchars使用示例" => "htmlspecialcharsDemo.php"
            );
        ?>
        <table>
            <?php
                foreach ($urls as $key => $value) {
                    echo "<tr>";
                    echo "<td><a href='" . $value . "'>" . $key . "</a></td>";
                    echo "</tr>";
                }
            ?>
        </table>
        <?php
            printArr($_SERVER);
            if (isset($_SESSION)) {
                printArr($_SESSION);
            }
            printArr($urls);
            echo TEMP_DIR . "<br />";
            echo __DIR__ . "<br />";
            $fileName = TEMP_DIR . "\\test.txt";
            writeFile($fileName, "哈哈\t很爱很爱你哟\t" . date("Y-m-d H:i:s") . "\n", true);

//        printFile($fileName);
            echo "<br/>文件大小:" . (sprintf("%.2f", filesize($fileName) / 1024.0)) . "kb";
        ?>

    </body>
</html>
