<?php

error_reporting(E_ALL ^ E_NOTICE);
setlocale(LC_ALL, 'de_DE.UTF-8');
require_once('cms/config/cms.php');
require_once('cms/system/functions.php');
$admin_lang = $cms['admin_language']['value'];
header('Content-Type: application/json; charset=utf-8');
$return = array();

if($_COOKIE['sess'] && $_GET['do'] === 'check_login' && $_POST['user'] && $_POST['md5_pass'])
{
	$return['status'] = 200;
	$return['msg'] = 'You asked for '+var_export($_POST['user'], true);
	$login_checked = check_login($_POST['user'], $_POST['md5_pass'], 'md5');
	if(true === $login_checked['status'])
	{
		$session_register = session_my_register();
	}
	$return['status'] = $login_checked['status'];
	$return['msg'] = lecho($login_checked['txt'], $admin_lang);
	if($login_checked['txt_2'])
	{
		$return['msg'] .= "\n" . $login_checked['txt_2'];
	}
	echo json_encode($return);
}
if($_POST)
{
	
}

?>
