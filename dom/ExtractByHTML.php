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
	$html->load_file('cleaned.htm');
	//".\conf\\netease.ini"	".\conf\\tencent.ini"
	$path = ".\conf\\twitter.ini";
	$conf = parse_ini_file($path, true);
	
	//微博作者是统一的
	$str_author = $conf['AUTHOR']['BEGIN'];
	if ($conf['AUTHOR']['START'] != NULL) {
		$str_author .= " ".$conf['AUTHOR']['START'];
	}
	$tauthor = $html->find($str_author);
	$author = trim($tauthor[0]->plaintext);	//trim清洗字符串
	//分别从配置文件读取时间和博文
	$str_time = $conf['TIME']['BEGIN'];
	if ($conf['TIME']['START'] != NULL) {
		$str_time .= " ".$conf['TIME']['START'];
	}
	$time = $html->find($str_time);
	if ($conf['TIME']['ATTRIBUTE'] != NULL) {
		$time_attr = $conf['TIME']['ATTRIBUTE'];
	} else {
		$time_attr = 'plaintext';
	}
	
	//腾讯微博需要清洗转发博文（与正博文相同标签）
	if ($conf['POST']['CLEAN'] != NULL) {
		$clean = $html->find($conf['POST']['CLEAN']);
		foreach ($clean as $item) {
			$item->outertext = '';
		}
	}
	$str_post = $conf['POST']['BEGIN'];
	if ($conf['POST']['START'] != NULL) {
		$str_post .= " ".$conf['POST']['START'];
	}	
	$post = $html->find($str_post);
	
//	$result->outertext = iconv("GBK", "UTF-8", $result->outertext);

//	初始化输出到xml文件的字符串
	$xml = "<Microblog>";
	$indent = 0;
	for ($i = 0; $i < count($time); $i++) {
		$xml .= "
".str_repeat(" ", $indent+4)."<record>";
		//写入作者
		$xml .= "
".str_repeat(" ", $indent+8)."<author>
".str_repeat(" ", $indent+12).$author."
".str_repeat(" ", $indent+8)."</author>";
		//写入时间
		$xml .= "
".str_repeat(" ", $indent+8)."<time>
".str_repeat(" ", $indent+12).trim($time[$i]->$time_attr)."
".str_repeat(" ", $indent+8)."</time>";
		//写入博文
		$xml .= "
".str_repeat(" ", $indent+8)."<post>
".str_repeat(" ", $indent+12).trim($post[$i]->plaintext)."
".str_repeat(" ", $indent+8)."</post>";

		$xml .= "
".str_repeat(" ", $indent+4)."</record>";
		
//		echo "<br /><br /><br /><br /><br /><br />";
	}
	
	
	$xml .= "
</Microblog>";
	echo $xml;
	$fp = fopen("temp.xml", "w+");
	fputs($fp, $xml);
	fclose($fp);
	?>
	</body>
</html>

