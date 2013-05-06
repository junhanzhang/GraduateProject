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
	$html->load_file('twitter.htm');

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
	
	function func($node, $indent) {
		if ($node->children) {
			$indent = $indent + 4;
			foreach ($node->children as $temp) {
				func($temp, $indent);
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
	$div = $html->find('body',0);
//	$div->outertext = iconv("GBK", "UTF-8", $div->outertext);
	$indent = 0;
	func($div, $indent);
	

	echo $div;
	file_put_contents("cleaned.htm", $div);
	?>
	</body>
</html>
