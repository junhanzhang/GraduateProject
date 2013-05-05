<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
		<title>DOM</title>
	</head>
	<body style="margin:0 0 0 0;">
	<?php

	$xml = simplexml_load_file('temp.xml');
	//sina
	$result = $xml->xpath('//div[@class="WB_detail"]');
	print_r($result);
/*	foreach ($result as $item) {
//		print_r($item);
		foreach ($item->div as $detail)
			echo $detail;
		echo "<br /><br />";
	}
	
/*	tencent
	$result = $xml->xpath('//ul[@id="talkList"]/li');
	foreach ($result as $item) {
		echo $item->div->div[1]."<br /><br />=========================我是分割线=========================<br /><br />";
	}
	*/
	
/*	netease
	$result = $xml->xpath('//ul[@id="tweetList"]/li');
	foreach ($result as $item) {
		echo $item->div->div->p."<br /><br />=========================我是分割线=========================<br /><br />";
	}
	
	$string = '<?xml version="1.0" encoding="UTF-8"?>';
	$result = $xml->xpath('//ol[@class="stream-items"]/li');
	foreach ($result as $item) {
		$string .= "
<Post>
".trim($item->div->div->p)."
</Post>";
	}
	//echo $string;
	//$xml = simplexml_load_string($string);
	//$xml->asXML($string);
	$fp = fopen("temp.xml", "w+");
	fputs($fp, $string);
	fclose($fp);*/
	?>
	</body>
</html>
