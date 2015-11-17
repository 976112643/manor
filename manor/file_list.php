<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>文件列表</title>
</head>


<body>
	<h1 align="center">文件列表</h1>
	<hr />
	<script type="text/javascript" src="./js/base/utils.js">
	</script> 
	<?php
	include 'config.php';
	include 'utils.php';
	include 'db.php';
	?>
		<?php
		
		$sql = "select * from  " . UPLOAD_FILE_TABLE;
		if (array_key_exists ( 'user_id', $_GET )) {
			$sql = $sql . " where user_id=" . $_GET ['user_id'];
			$file_list = db::get_all ( $sql );
			if (! empty ( $file_list )) {
				echo "<table width='70%' border='1' align='center'>";
				echo "<tr>";
				echo "<th scope='col'>预览</th>";
				foreach ( $file_list [0] as $key => $value ) {
					echo "<th scope='col'>" . $key . "</th>";
				}
				echo "</tr>";
				for($i = 0, $len = count ( $file_list ); $i < $len; $i ++) {
					echo "<tr>";
					if ($file_list [$i] ['type'] == '.png') {
						echo "<th scope='col'><img width='50px' height='50px' src='" . $file_list [$i] ['localpath'] . "'/></th>";
					}
					foreach ( $file_list [$i] as $key => $value ) {
						echo "<th scope='col'>" . $value . "</th>";
					}
					echo "</tr>";
				}
			}
		} else {
			echo "<h1>暂无数据</h1>";
		}
		
		?>
	</table>
</body>
</html>
