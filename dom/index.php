<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
		<title>DOM</title>
	</head>
	<body style="margin:0 0 0 0;">
	<?php
		echo '
		<form action="analyse.php" method="post">
			<input type="radio" checked="checked" name="microblog" value="sina" />新浪
			<input type="radio" name="microblog" value="tencent" />腾讯
			<input type="radio" name="microblog" value="netease" />网易
			<input type="radio" name="microblog" value="twitter" />Twitter
			<br />
			<input type="file" name="files" accept="text/html, text/htm, text/xml" />
			<input type="submit" value="开始解析">
		</form>'
	?>
	</body>
</html>

