<?php

$template = <<< EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7 ]> <html xmlns="http://www.w3.org/1999/xhtml" class="ie6" xml:lang="${tpl_lang}" lang="${tpl_lang}"> <![endif]-->
<!--[if IE 7 ]> <html xmlns="http://www.w3.org/1999/xhtml" class="ie7" xml:lang="${tpl_lang}" lang="${tpl_lang}"> <![endif]-->
<!--[if IE 8 ]> <html xmlns="http://www.w3.org/1999/xhtml" class="ie8" xml:lang="${tpl_lang}" lang="${tpl_lang}"> <![endif]-->
<!--[if IE 9 ]> <html xmlns="http://www.w3.org/1999/xhtml" class="ie9" xml:lang="${tpl_lang}" lang="${tpl_lang}"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="${tpl_lang}" lang="${tpl_lang}"> <!--<![endif]-->
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		${tpl_header}
		<title>${tpl_page_title}</title>
		<meta name="robots" content="INDEX,FOLLOW" />
		<meta name="author" content="${tpl_page_author}" />
		<meta name="keywords" content="${tpl_page_keywords}" />
		<meta name="description" content="${tpl_page_description}" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
		<link rel="stylesheet" type="text/css" href="?style_css" />
		<script type="text/javascript" src="?javascript_js"></script>
	</head>
	<body>
		<div id="header">${tpl_page_header}</div>
		<div id="content">${tpl_page_content}</div>
		<div id="footer">${tpl_page_footer}</div>
	</body>
</html>

EOT;

?>
