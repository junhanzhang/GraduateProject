<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
		<title>DOM</title>
	</head>
	<body style="margin:0 0 0 0;">
	<?php

include('simple_html_dom.php');
	$html = new simple_html_dom();
	$html->load_file('temp1.htm');


	$str = 'abcdefg';
	$str = '<XMP>'.str_repeat(" ",6).$str.'</XMP>';
	echo $str;
	?>
	</body>
</html>