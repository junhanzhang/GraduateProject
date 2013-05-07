<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Style-Type" content="text/css" />
		<title>DOM</title>
	</head>
	<body style="margin:0 0 0 0;">
	<?php

	//判断是否转义字符
	function isEscapeCharacter($str) {
		if ($str[0] == 't' || $str[0] == 'n') {
			return true;
		} else {
			return false;
		}
	}
	function deleteTag($node) {
		//删除font、style、script、备注标签
		if ($node->tag == 'font' || $node->tag == 'style' || $node->tag == 'script' || $node->tag == 'comment' || $node->tag == 'param' || $node->tag == 'img') {
			return true;
		} else {
			return false;
		}
	}
	
	function cleanTag($node) {
		if ($node->tag == 's' || $node->tag == 'b') {
			return true;
		} else {
			return false;
		}

	}
	
//	================================我是分割线================================	
//	下面为清洗html文件
	function cleanHTML($node, $indent) {
		if ($node->children) {
			$indent = $indent + 4;
			foreach ($node->children as $temp) {
				cleanHTML($temp, $indent);
			}
			//判断是否垃圾标签，如style、script等
			if (deleteTag($node)) {
				$node->outertext = '';
				return ;
			}
//			$node->innertext = rtrim($node->innertext);
			$node->innertext = str_replace("&nbsp;",'',$node->innertext);
			$node->innertext = str_replace("&lt;",'',$node->innertext);
			$node->innertext = str_replace("&gt;",'',$node->innertext);
			//删除空白内容标签
			if (trim($node->innertext) == '') {
				$node->outertext = '';
				return;
			}
			//SimpleXML解析有多个属性的a会有问题要清洗
			//判断是否要去掉标签<a><em>，只留下<a><em>中内容
			if (cleanTag($node)) {
				$node->outertext = $node->innertext;
				return;
			}
/*  			if ($node->tag == 'a') {
				$node->outertext = '
'.str_repeat(" ", $indent).'<a>
'.$node->innertext.'
'.str_repeat(" ", $indent).'</a>';
			return;
			}  */
			
			//重建标签有排版和无排版
			/* $node->outertext = '
'.str_repeat(" ", $indent).substr($node->outertext, 0, strpos($node->outertext, '>')+1).'
'.$node->innertext.'
'.str_repeat(" ", $indent).'</'.$node->tag.'>'; */
			$node->outertext = substr($node->outertext, 0, strpos($node->outertext, '>')+1).'
'.$node->innertext.'
'.'</'.$node->tag.'>';
//	echo $node;
		} else {
			$indent = $indent + 4;
			if (deleteTag($node)) {
				$node->outertext = '';
				return ;
			}
//			$node->innertext = rtrim($node->innertext);
			$node->innertext = str_replace("&nbsp;",'',$node->innertext);
			$node->innertext = str_replace("&lt;",'',$node->innertext);
			$node->innertext = str_replace("&gt;",'',$node->innertext);
			if (trim($node->innertext) == '') {
				$node->outertext = '';
				return;
			}
			if (cleanTag($node)) {
				$node->outertext = $node->innertext;
				return;
			}

			//有排版和无排版
			/* $node->outertext = '
'.str_repeat(" ", $indent).substr($node->outertext, 0, strpos($node->outertext, '>')+1).'
'.str_repeat(" ", $indent+4).$node->innertext.'
'.str_repeat(" ", $indent).'</'.$node->tag.'>'.'
'; */
			$node->outertext = '
'.substr($node->outertext, 0, strpos($node->outertext, '>')+1).'
'.$node->innertext.'
'.'</'.$node->tag.'>';
//			echo $node;
		}
	}

//	================================我是分割线================================	
//	下面为生成xml文件
//$html为清洗后的字符串载入的DOM对象，$save_path为生成XML文件保存路径
//$file_only_name为XML文件保存名（不包含扩展名），$conf_path为配置文件路径
	function generateXML($html, $save_path, $file_only_name, $conf_path) {
		
		//".\conf\\netease.ini"	".\conf\\tencent.ini"
		$conf = parse_ini_file($conf_path, true);
		
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
			

		}
		
		
		$xml .= "
</Microblog>";
//		echo $xml;
		if (isEscapeCharacter($file_only_name)) {
			$full_save_path = $save_path.'\\\\'.$file_only_name.'.xml';
		} else {
			$full_save_path = $save_path.'\\'.$file_only_name.'.xml';
		}
		echo $full_save_path;
		$fp = fopen($full_save_path, "w+");
		fputs($fp, $xml);
		fclose($fp);
	}

	if (isset($_POST["microblog"])) {
		$microblog = $_POST["microblog"];
	} else {
		$microblog = NULL;
		alert('请选择抽取的微博');
	}
//	配置文件路径\会转义要多加一个\
	if (isEscapeCharacter($microblog)) {
		$conf_path = '.\conf\\\\'.$microblog.'.ini';
	} else {
		$conf_path = '.\conf\\'.$microblog.'.ini';
	}
//	数据目录
	if (isset($_POST["dir_path"]) && $_POST["dir_path"] != NULL) {
		$file_path = $_POST["dir_path"];
	} else {
		$conf = parse_ini_file($conf_path, true);
		$file_path = $conf['FILE_PATH']['PATH'];
	}

	include('simple_html_dom.php');
	$html = new simple_html_dom();
//	开始循环处理
	$dir = opendir($file_path);
	while (($file_name = readdir($dir)) !== false) {
		//readdir前两个返回的是.和..
		if ($file_name != '.' && $file_name != '..') {

			$path_info = pathinfo($file_name);

			//过滤只抽取指定后缀名文件
			if (isset($path_info['extension']) && ($path_info['extension'] == 'htm' || $path_info['extension'] == 'html' || $path_info['extension'] == 'xml')) {
				//防止转义字符影响
				if (isEscapeCharacter($file_name)) {
					$full_path = $file_path.'\\\\'.$file_name;
				} else {
					$full_path = $file_path.'\\'.$file_name;
				}
//				echo $full_path;
//				echo '<br />';
				$html->load_file($full_path);
		
				$div = $html->find('body',0);
			//	$div->outertext = iconv("GBK", "UTF-8", $div->outertext);
				$indent = 0;
				cleanHTML($div, $indent);
				
			//	通过字符串重新载入清洗后的DOM对象
				$html->load($div);
				generateXML($html, $file_path, $path_info['filename'], $conf_path);
			}
		}
	}

	//	file_put_contents("cleaned.htm", $xml);
	?>
	</body>
</html>
