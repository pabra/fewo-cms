<?php

#header('Content-Type: text/plain; charset=utf-8');
#print_r($_SERVER);
#die();
error_reporting(E_ALL ^ E_NOTICE);
setlocale(LC_ALL, 'de_DE.UTF-8');
require_once('cms/config/cms.php');
#$use_cache = true;
#$use_cache = false;
$use_cache = ('On' == $cms['use_cache']['value'])? true : false;
$gzip_cache = true;
$cache_expire = 86400;
$req_page = false;
$keys_of_get = array_keys($_GET);
if(isset($keys_of_get[0]))
{
	$req_page = preg_replace('/[^a-zA-Z0-9_-]/', '', $keys_of_get[0]);
	unset($_GET[$keys_of_get[0]]);
	if(0 < count($_GET))
	{
		ksort($_GET);
		#fetch_cache_file_check_get_vars();
		foreach($_GET as $k => $v)
		{
			
		}
	}
}
else 
{
	# get_default_start_page();
	$use_cache = false;
}
if(true === $use_cache && false !== $req_page)
{
	#$cache_hash = substr(preg_replace('/[^a-zA-Z0-9&=_-]/', '', $_SERVER['QUERY_STRING']), 0, 80);
	$cache_hash = $req_page.'|'.$show_lang;
	$cache_file = 'cms/cache/'.$cache_hash.'.php';
	$cache_file_gzip = 'cms/cache/'.$cache_hash.'.gz.php';
	$browser_gzip = (false !== strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))? true : false;
	if(is_file($cache_file))
	{
		$cache_modified = filemtime($cache_file);
		if(strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $cache_modified)
		{
			header('HTTP/1.1 304 Not Modified');
			die();
		}
		header('Cache-Control: max-age='.$cache_expire);
		header('Expires: ' . date('r', time()+$cache_expire));
		header('Last-Modified: ' . date('r', $cache_modified));
		header('Accept-Ranges: none');
		if(true === $gzip_cache && true === $browser_gzip && true === is_file($cache_file_gzip))
		{
			include($cache_file_gzip);
			header('Content-Type: '.$cache_ct);
			header('Content-Encoding: gzip');
			header('Content-Length: '.$cache_length);
			header('X-Cache: GZ HIT');
			die($cache);
		}
		else
		{
			include($cache_file);
			header('Content-Type: '.$cache_ct);
			header('Content-Length: '.$cache_length);
			header('X-Cache: HIT');
			die($cache);
		}
	}
}
#var_dump($_GET);
require_once('cms/system/functions.php');
#var_dump(get_browser_lang($_SERVER['HTTP_ACCEPT_LANGUAGE']));
#die();

$page_content_type = 'text/html; charset=utf-8';
if($req_page == 'style_css' || $req_page == 'style_admin_css'){
	$page_content_type = 'text/css; charset=utf-8';
	$page_out = 'body {background-color:#eee; padding:100px;}';
	$page_out = get_include_file($req_page);
}
elseif($req_page == 'javascript_js' || $req_page == 'javascript_admin_js')
{
	$page_content_type = 'application/x-javascript; charset=utf-8';
	$page_out = "$(function(){alert('huhu');});";
	$page_out = get_include_file($req_page);
}
elseif($req_page == 'admin')
{
	$use_cache = false;
	$cache_expire = 0;
	require_once('cms/system/admin.php');
	#$page_out = 'admin';
}
else 
{
	$conf_default_lang = array();
	foreach($cms['avail_page_lang']['value'] as $k => $v)
	{
		if('On' === $v['visible'])
		{
			$conf_default_lang = array('index'=>$k, 'lang'=>$v['lang']);
			break 1;
		}
	}
	require_once('cms/config/pages.php');
	if(false === $req_page)
	{
		$startpage = $pages['pages']['list']['lang'][$conf_default_lang['index']][0];
		if($startpage)
		{
			$startpage = $pages['pages']['value'][$startpage]['name'];
			header("Location: http://${_SERVER['HTTP_HOST']}".dirname($_SERVER['REQUEST_URI'])."?${startpage}");
			die();
		}
		else 
		{
			header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
			die('Not Found');
		}
	}
	if(!isset($pages['pages']['index']['name'][$req_page]))
	{
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
		die('Not Found');
	}
	else 
	{
		$conf_page = $pages['pages']['value'][$pages['pages']['index']['name'][$req_page]];
	}
	require_once('cms/config/users.php');
	$tpl_lang = $cms['avail_page_lang']['value'][$conf_page['lang']]['lang'];
	$tpl_page_author = $users['users']['value'][$cms['page_author']['value']]['real_name'];
	$tpl_page_title = htmlspecialchars($conf_page['title']);
	$tpl_page_content = var_export($_SERVER['QUERY_STRING'], true) . "<br/>\nHÜhü<br/>\nlang: ${show_lang}<br/>\n";
	$tpl_page_content .= "geforderte Seite: $req_page<br/>\n";
	require_once('cms/template/default/template.php');
	/*$C = array(
		'page_title' => 'Das ist der Titelü',
		'page_content' => var_export($_SERVER['QUERY_STRING'], true) . "\nHÜhü",
	);
	if(!preg_match_all('/\[__\[([^\]]+)\]__\]/', $template, $template_vars))
	{
		die('kein Template');
	}
	foreach($template_vars[1] as $k => $v)
	{
		$template_vars[1][$k] = ($C[$v])? $C[$v] : '';
	}
	$page_out = str_replace($template_vars[0], $template_vars[1], $template);*/
	$page_out = $template;
}
$page_out_length = strlen($page_out);

if(true === $use_cache)
{
	if(!file_put_contents($cache_file, "<?php # ".date('r')."\n\n\$cache_qs = ".var_export($_SERVER['QUERY_STRING'], true).";\n\$cache_length = ".$page_out_length.";\n\$cache_ct = ".var_export($page_content_type, true).";\n\$cache = " . var_export($page_out, true) . ";\n\n?>", LOCK_EX))
	{
		die('Kann Cache-Datei nicht anlegen.');
	}
	@chmod($cache_file, 0666);
	header('Last-Modified: ' . date('r', filemtime($cache_file)));
	if(true === $gzip_cache){
		$page_out_gz = gzencode($page_out);
		$page_out_gz_length = strlen($page_out_gz);
		if(!file_put_contents($cache_file_gzip, "<?php # ".date('r')."\n\n\$cache_qs = ".var_export($_SERVER['QUERY_STRING'], true).";\n\$cache_length = ".$page_out_gz_length.";\n\$cache_ct = ".var_export($page_content_type, true).";\n\$cache = " . var_export($page_out_gz, true) . ";\n\n?>", LOCK_EX))
		{
			die('Kann gzip Cache-Datei nicht anlegen.');
		}
		@chmod($cache_file_gzip, 0666);
		if(true === $browser_gzip)
		{
			header('Content-Encoding: gzip');
			$page_out = $page_out_gz;
			$page_out_length = $page_out_gz_length;
		}
	}
}
header('Content-Type: '.$page_content_type);
header('Content-Length: '.$page_out_length);
header('Accept-Ranges: none');
if($cache_expire === 0)
{
	header('Cache-Control: no-cache');
	header('Expires: ' . date('r', 123));
}
else 
{
	header('Cache-Control: max-age=' . $cache_expire);
	header('Expires: ' . date('r', time()+$cache_expire));
}
echo $page_out;

?>
