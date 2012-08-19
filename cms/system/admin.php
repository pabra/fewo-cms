<?php

$admin_header = <<< EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7 ]> <html xmlns="http://www.w3.org/1999/xhtml" class="ie6"> <![endif]-->
<!--[if IE 7 ]> <html xmlns="http://www.w3.org/1999/xhtml" class="ie7"> <![endif]-->
<!--[if IE 8 ]> <html xmlns="http://www.w3.org/1999/xhtml" class="ie8"> <![endif]-->
<!--[if IE 9 ]> <html xmlns="http://www.w3.org/1999/xhtml" class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html xmlns="http://www.w3.org/1999/xhtml" > <!--<![endif]-->

	<head>
		<title>Admin</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<link rel="shortcut icon" type="image/x-icon" href="favicon_admin.ico" />
		<link rel="stylesheet" type="text/css" href="?style_admin_css" />
		<script type="text/javascript" src="?javascript_admin_js"></script>
	</head>
	<body>
EOT;

$admin_footer = <<< EOT
	</body>
</html>
EOT;

#require_once('cms/config/users.php');
#header('Content-Type: text/plain; charset=utf-8');
#echo var_export(isset($users['users']['index']['user_name']['usera']), true);
#echo preg_replace('/^( +)/me', "str_repeat(\"\t\", (strlen('$1')/2))", var_export($users['users']['index']['user_name']['user'], true));
#die();
$user = false;
$admin_lang = $cms['admin_language']['value'];
$admin_content = '<h1>Admin</h1>'."\n";
$admin_content .= '<noscript>'.lecho('admin_enable_javascript', $admin_lang).'</noscript>'."\n";
#merge_config('users', 'users|value|[index=user_name="user"]|email', 'email@host.dom');
#merge_config('cms', array('admin_language'=> 'en'));
#merge_config('cms', array('test'=> 'lalula'));
/*merge_config('cms', array(
	array('var'=>'test', 'value'=> '   lalula   '),
	array('var'=>'page_def_lang_prior_browser_accept', 'value'=> '0'),
	));*/
/*merge_config('cms', array( 
	array('var'=>'avail_page_lang', 'index'=>'_new_1', 'key'=>'lang', 'value'=>'fr'),
	array('var'=>'avail_page_lang', 'index'=>'_new_1', 'key'=>'lang_name', 'value'=>'francè'),
	));*/
#merge_config('users', array(array('var'=>'users', 'index'=>'ab12d', 'key'=>'email', 'value'=>'pat.bass@gmx.de')));
/*merge_config('users', array(
	array('var'=>'users', 'index'=>'[user_name==user]', 'key'=>'email', 'value'=>'abc@def.gh'),
	array('var'=>'users', 'index'=>'[user_name==user]', 'key'=>'user_name', 'value'=>'pepp'),
	array('var'=>'users', 'index'=>'[user_name==user]', 'key'=>'real_name', 'value'=>'Pat.Bass'),
	array('var'=>'users', 'index'=>'[user_name==user]', 'key'=>'role', 'value'=>'user'),
	));*/
/*merge_config('users', array(
	array('var'=>'users', 'sort_order'=>array('_new_1','CioR2','mko98','ab12d','LU9Au')),
	array('var'=>'users', 'index'=>'_new_1', 'key'=>'email', 'value'=>'abc@def.gh'),
	#array('var'=>'users', 'index'=>'_new_2', 'key'=>'user_name', 'value'=>'pepper_4'),
	array('var'=>'users', 'index'=>'_new_1', 'key'=>'user_name', 'value'=>'pepp_4'),
	#array('var'=>'users', 'index'=>'_new_2', 'key'=>'email', 'value'=>'abc@def.gh'),
	array('var'=>'users', 'index'=>'_new_1', 'key'=>'role', 'value'=>'user'),
	#array('var'=>'users', 'index'=>'_new_2', 'key'=>'real_name', 'value'=>'Pat.Bass'),
	array('var'=>'users', 'index'=>'_new_1', 'key'=>'real_name', 'value'=>'Pat.Bass'),
	#array('var'=>'users', 'index'=>'_new_2', 'key'=>'role', 'value'=>'user'),
	array('var'=>'users', 'index'=>'_new_1', 'key'=>'password', 'value'=>'123'),
	#array('var'=>'users', 'index'=>'_new_2', 'key'=>'password', 'value'=>'123'),
	));*/
#$admin_content .= edit_config('cms'); # array('test','page_def_lang_prior_browser_accept')
#$admin_content .= edit_config('users'); # array('test','page_def_lang_prior_browser_accept')
if($_COOKIE['sess'])
{
	#check_session();
	if($_GET['do'] == 'check_login' && $_POST['user'] && $_POST['pass'])
	{
		$login_checked = check_login($_POST['user'], $_POST['pass']);
		if(true === $login_checked['status'])
		{
			$admin_content .= "get user data<br/>\n";
			$login_user_data = get_user_data($_POST['user']);
			if(count($login_user_data) && isset($login_user_data['role']))
			{
				unset($login_user_data['password']);
				$session_data = $login_user_data;
				$session_data['sess'] = $_COOKIE['sess'];
				$session_data['time'] = time();
				$session_data['ip'] = $_SERVER['REMOTE_ADDR'];
				$session_data['ua'] = var_export($_SERVER['HTTP_USER_AGENT'], true);
				#write_config('sessions', )
			}
			$admin_content .= print_r($session_data, true)."<br/>\n";
		}
		$admin_content .= lecho($login_checked['txt'], $admin_lang);
		/*require_once('cms/config/users.php');
		#die('user (plain): ' . $_POST['user'] ."<br/>\n" . 'user (export): '.var_export($_POST['user'], true) . "<br/>\n" . var_export($users['users']['index']['user_name'], true) . "<br/>\n" . var_export(isset($users['users']['index']['user_name'][$_POST['user']]), true));
		if(isset($users['users']['index']['user_name'][$_POST['user']])
			&&  ( md5($_POST['pass']) == $users['users']['value'][$users['users']['index']['user_name'][$_POST['user']]]['password']
			 || $_POST['md5_pass'] == $users['users']['value'][$users['users']['index']['user_name'][$_POST['user']]]['password'] ) )
		{
			$admin_content .= 'User gibts SEHRWOHL';
		}
		else 
		{
			$admin_content .= 'User gibts NICH (' . md5($_POST['pass']) . ')';
		}*/
		
		#if($_POST['user'] == 'user' && $_POST['pass'] == 'pass')
		#{
		#	$user = array( 'role' => 'admin', 'name' => 'user', 'sess' => $_COOKIE['sess']);
		#}
		#$admin_content .= 'From: '.$_SERVER['HTTP_REFERER']."\n".'check_login()'."\n";
	}
	else 
	{
		$admin_content .= 'check_session()'."\n";
	}
	setcookie('sess', '', 1);
}
if(false === $user)
{
	setcookie('sess', md5($_SERVER['HTTP_HOST'].microtime(true)));
	$admin_content .= '<form class="login" action="?admin&amp;do=check_login" method="post">'; #enctype="multipart/form-data"
	$admin_content .= '<div><label for="in_user">User:</label> <input id="in_user" type="text" name="user" /></div>';
	$admin_content .= '<div><label for="in_pass">Pass:</label> <input id="in_pass" type="password" name="pass" /></div>';
	#$admin_content .= '<div><label for="in_select">Select:</label> <select id="in_select" name="select" size="1"><option>erster</option><option>zweiter und erster</option></select></div>';
	#$admin_content .= '<div><label for="in_text">Text:</label> <textarea id="in_text" name="pass" /></textarea></div>';
	#$admin_content .= '<div><label for="in_check">Check:</label> <input id="in_check" type="checkbox" name="check" /></div>';
	$admin_content .= '<input type="hidden" name="req_page" value="'.htmlspecialchars($_SERVER['QUERY_STRING']).'" />';
	$admin_content .= '<div><input type="submit"/></div></form>'."\n";
}
else 
{
}

$page_out = $admin_header . $admin_content . $admin_footer;

?>
