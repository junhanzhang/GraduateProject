<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
		<title>DOM</title>
	</head>
	<body style="margin:0 0 0 0;">
	<script>
		function check_legality()
		{
			
		}
	</script>
	<?php
		echo '
		<form action="extract.php" method="post">
			<input type="radio" checked="checked" name="microblog" value="sina" />新浪
			<input type="radio" name="microblog" value="tencent" />腾讯
			<input type="radio" name="microblog" value="netease" />网易
			<input type="radio" name="microblog" value="twitter" />Twitter
			<br />
			数据目录：<input type="text" name="dir_path" style="width:400px;" />（不填则默认读取ini配置文件中的路径）
			<br />
			输出目录：<input type="text" name="output_path" style="width:400px;" />（不填则默认存在数据目录中生成的extract文件中）
			<br />
			<input type="submit" value="开始解析">
		</form>'
	?>
	</body>
</html>

