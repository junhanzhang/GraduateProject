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
	$html->load_file('sina.htm');

	function deleteTag($node) {
		//É¾³ıfont¡¢style¡¢script¡¢±¸×¢±êÇ©
		if ($node->tag == 'font' || $node->tag == 'style' || $node->tag == 'script' || $node->tag == 'comment') {
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
			//ÅĞ¶ÏÊÇ·ñÀ¬»ø±êÇ©£¬Èçstyle¡¢scriptµÈ
			if (deleteTag($node)) {
				$node->outertext = '';
				return ;
			}
			$node->innertext = trim($node->innertext);
			$node->innertext = str_replace("&nbsp;",'',$node->innertext);
			//É¾³ı¿Õ°×ÄÚÈİ±êÇ©
			if ($node->innertext == '') {
				$node->outertext = '';
				return;
			}
			//ÖØ½¨±êÇ©
			$node->outertext = '
'.str_repeat(".", $indent).substr($node->outertext, 0, strpos($node->outertext, '>')+1).'
'.$node->innertext.'
'.str_repeat(".", $indent).'</'.$node->tag.'>';
			

//	echo $node;
		} else {
			$indent = $indent + 4;
			if (deleteTag($node)) {
				$node->outertext = '';
				return ;
			}
			$node->innertext = trim($node->innertext);
			$node->innertext = str_replace("&nbsp;",'',$node->innertext);
			if ($node->innertext == '') {
				$node->outertext = '';
				return;
			}
			$node->outertext = '
'.str_repeat(".", $indent).substr($node->outertext, 0, strpos($node->outertext, '>')+1).'
'.str_repeat(".", $indent+4).$node->innertext.'
'.str_repeat(".", $indent).'</'.$node->tag.'>';
			echo $node;
		}
	}
	$div = $html->find('body',0);
//	$div->outertext = iconv("GBK", "UTF-8", $div->outertext);
	$indent = 0;
	func($div, $indent);
	
/*
	foreach ($div as $d) {
		$d->outertext = '<div>'.$d->innertext.'</div>';
	}
	$a = $html->find('a');
	foreach ($a as $d) {
		$d->outertext = '<a>'.$d->innertext.'</a>';
	}
	$ul = $html->find('ul');
	foreach ($ul as $d) {
		$d->outertext = '<ul>'.$d->innertext.'</ul>';
	}
	$li = $html->find('li');
	foreach ($li as $d) {
		$d->outertext = '<li>'.$d->innertext.'</li>';
	}
	$img = $html->find('img');
	foreach ($img as $d) {
		$d->outertext = '<img>'.$d->innertext.'</img>';
	}
	*/
//	$html->save('test.xml');
	echo $div;
	file_put_contents("test.xml", '<?xml version="1.0" encoding="UTF-8"?>
'.$div);
	?>
	</body>
</html>
