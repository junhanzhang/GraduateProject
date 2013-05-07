<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
		<title>DOM</title>
	</head>
	<body style="margin:0 0 0 0;">
	<?php
		/* if (isset($_POST['sina'])){
			$microblog = "sina";
		} else if (isset($_POST['tencent'])) {
			$microblog = "tencent";
		} else if (isset($_POST['netease'])) {
			$microblog = "netease";
		} else {
			$microblog = "twitter";
		} */
		
		if (isset($_POST["microblog"])) {
			$microblog = $_POST["microblog"];
			echo $microblog;
			$file_path = $_POST["files"];
			echo $file_path;
		}
	?>
	</body>
</html>