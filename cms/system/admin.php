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
$admin_head_bar = '';
$admin_footer = <<< EOT
	</body>
</html>
EOT;

#require_once('cms/config/users.php');
#header('Content-Type: text/plain; charset=utf-8');
#echo var_export(isset($users['users']['index']['user_name']['usera']), true);
#echo preg_replace('/^( +)/me', "str_repeat(\"\t\", (strlen('$1')/2))", var_export($users['users']['index']['user_name']['user'], true));
#die();
$glob_var = array();
$user = false;
$admin_lang = $cms['admin_language']['value'];
#$admin_content = '<h1>Admin</h1>'."\n";
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
	array('var'=>'users', 'index'=>'[user_name=user]', 'key'=>'email', 'value'=>'abc@def.gh'),
	array('var'=>'users', 'index'=>'[user_name=user]', 'key'=>'user_name', 'value'=>'pepp'),
	array('var'=>'users', 'index'=>'[user_name=user]', 'key'=>'real_name', 'value'=>'Pat.Bass'),
	array('var'=>'users', 'index'=>'[user_name=user]', 'key'=>'role', 'value'=>'user'),
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
/*merge_config('cms', array(
	array('var'=>'avail_page_lang', 'sort_order'=>array("abcd1","mno9r","xyz2z")),
	));*/
#$admin_content .= edit_config('cms'); # array('test','page_def_lang_prior_browser_accept')
#$admin_content .= edit_config('users'); # array('test','page_def_lang_prior_browser_accept')
#die();
if($_COOKIE['sess'])
{
	#check_session();
	if($_GET['do'] == 'check_login' && $_POST['user'] && $_POST['pass'])
	{
		$login_checked = check_login($_POST['user'], $_POST['pass']);
		if(true === $login_checked['status'])
		{
			$session_register = session_my_register();
			if(isset($_POST['req_page']) && 'admin&' == substr($_POST['req_page'], 0, 6))
			{
				header('Location: ?'.$_POST['req_page']);
				die();
			}
		}
		$admin_content .= lecho($login_checked['txt'], $admin_lang);
	}
	elseif($_GET['do'] == 'logout')
	{
		check_logout();
		$admin_content .= '<script type="text/javascript">window.location=\'?admin\';</script>'."\n";
	}
	else 
	{
		check_session();
	}
	if(!isset($sess_data['role']))
	{
		setcookie('sess', '', 1);
		#$admin_content .= 'cookie cleared'."<br/>\n";
	}
}
if(!isset($sess_data['role']))
{
	setcookie('sess', md5($_SERVER['HTTP_HOST'].microtime(true)));
	$admin_content .= '<div id="login_form_wrap"><form class="login" action="?admin&amp;do=check_login" method="post">'; #enctype="multipart/form-data"
	$admin_content .= '<div class="form_row"><label for="in_user" class="main">'.lecho('admin_field_user', $admin_lang).':</label> <input id="in_user" type="text" name="user" /></div>';
	$admin_content .= '<div class="form_row"><label for="in_pass" class="main">'.lecho('admin_field_pass', $admin_lang).':</label> <input id="in_pass" type="password" name="pass" /></div>';
	#$admin_content .= '<div><label for="in_select">Select:</label> <select id="in_select" name="select" size="1"><option>erster</option><option>zweiter und erster</option></select></div>';
	#$admin_content .= '<div><label for="in_text">Text:</label> <textarea id="in_text" name="pass" /></textarea></div>';
	#$admin_content .= '<div><label for="in_check">Check:</label> <input id="in_check" type="checkbox" name="check" /></div>';
	$admin_content .= '<input type="hidden" name="req_page" value="'.htmlspecialchars($_SERVER['QUERY_STRING']).'" />';
	$admin_content .= '<div class="form_row"><input type="submit" value="'.lecho('button_submit', $admin_lang).'" /></div></form></div>'."\n";
}
else 
{
	#$glob_var['lang_edit_now'] = 'english';
	$admin_head_bar .= '<script type="text/javascript">var admin_keep_alive='.$sessions['keep_alive']['value'].';admin_lang="'.$admin_lang.'";</script>'."\n";
	$admin_head_bar .= '<div id="head_user_box">'.$sess_data['user_name'].' '.lecho('admin_logged_in_as', $admin_lang).'<br/><a title="'.lecho('admin_user_pref', $admin_lang).'" href="?admin&amp;do=user_pref">'.$sess_data['real_name'].'</a><br/><a title="'.lecho('admin_log_timer_out', $admin_lang).'" class="ajax" id="keep_alive_timer" href="#do=logout">logout</a></div>'."\n";
	$admin_head_bar .= '<a href="?admin" style="margin:5px;font-size:24pt;" title="'.lecho('admin_homepage', $admin_lang).'">A</a> '."\n";
	#$admin_head_bar .= '<a href="?admin&amp;do=pages_order" title="huhu">pages_order</a> '."\n";
	$admin_head_bar .= config2menu('admin_menu', 'menu_user');
	if('superuser' == $sess_data['role'])
	{
		$admin_head_bar .= '<div id="use_role_switch" ';
		if('user' == $sess_data['use_role'])
			$admin_head_bar .= 'class="to_admin"><span class="ui-icon ui-icon-circle-triangle-s"></span><a title="'.lecho('admin_use_role_toadmin', $admin_lang).'" class="ajax" href="#do=use_role_admin">to admin</a>';
		else
			$admin_head_bar .= 'class="to_user"><span class="ui-icon ui-icon-circle-triangle-n"></span><a title="'.lecho('admin_use_role_touser', $admin_lang).'" class="ajax" href="#do=use_role_user">to user</a>';
		$admin_head_bar .= '</div>'."\n";
	}
	if('admin' == $sess_data['use_role'])
	{
		$admin_head_bar .= '<div id="head_adv_admin">'."\n";
		$adm_menu = get_config_data('admin_menu', 'menu_admin');
		$admin_head_bar .= config2menu('admin_menu', 'menu_admin');
		$admin_head_bar .= '</div>'."\n";
		if($_GET['do'] == 'config_select')
		{
			$c_files = glob('cms/config/*.php');
			$admin_content .= '<select name="file_select" onchange="var tval=$(this).val();if(tval!==\'---\'){window.location=\'?admin&amp;do='.$_GET['do'].'&amp;config=\'+tval;}">'."\n";
			$admin_content .= '<option>---</option>'."\n";
			foreach($c_files as $k => $v)
			{
				if(preg_match('#/[^.]+\.php#', $v))
				{
					$v = str_replace('.php', '', basename($v));
					$selected = ($v == $_GET['config'])? ' selected="selected"' : '';
					$admin_content .= '<option'.$selected.'>'.$v.'</option>'."\n";
				}
			}
			$admin_content .= '</select>'."<br/>\n";
			if($_GET['config'] && is_file('cms/config/'.$_GET['config'].'.php'))
			{
				$admin_content .= edit_config($_GET['config']);
			}
		}
		elseif($_GET['do'] == 'clear_cache')
		{
			$count = clear_cache('count');
			$admin_content .= show_warning( lecho('admin_warning_clear_cache', $admin_lang) );
			$admin_content .= '<br/><form id="clear_cache" action="javascript:void(0);" method="get">';
			$admin_content .= '<div class="form_row"><label class="main">'.lecho('admin_clear_cache_pages', $admin_lang).' ('.intval($count['count']['pages']).')</label><input id="clear_cache_pages" type="button" value="'.lecho('button_click', $admin_lang).'" /></div>';
			$admin_content .= '<div class="form_row"><label class="main">'.lecho('admin_clear_cache_js', $admin_lang).' ('.intval($count['count']['js']).')</label><input id="clear_cache_js" type="button" value="'.lecho('button_click', $admin_lang).'" /></div>';
			$admin_content .= '<div class="form_row"><label class="main">'.lecho('admin_clear_cache_css', $admin_lang).' ('.intval($count['count']['css']).')</label><input id="clear_cache_css" type="button" value="'.lecho('button_click', $admin_lang).'" /></div>';
			$admin_content .= '<div class="form_row"><label class="main">'.lecho('admin_clear_cache_all', $admin_lang).' ('.intval($count['count']['all']).')</label><input id="clear_cache_all" type="button" value="'.lecho('button_click', $admin_lang).'" /></div>';
			$admin_content .= '</form>';
			$admin_content .= <<<EOJS
<script type="text/javascript">
function ccbuttons(what){
	if(what === 'enabled'){
		$('#clear_cache input[type=button]').prop({disabled:false});
	} else if(what === 'disabled'){
		$('#clear_cache input[type=button]').prop({disabled:true});
	}
}
function post_clear_cache(what){
	ccbuttons('disabled');
	$.post('ajax.php', {do:'clear_cache',what:what},function(data){
		if(true === data.status){
			show_info(data.txt, undefined, function(){ window.location = window.location; });
			ccbuttons('enabled');
			//window.location = window.location;
		} else {
			show_warning(data.txt, undefined, function(){ window.location = window.location; });
		}
	});
}
$('#clear_cache input[type=button]').click(function(){post_clear_cache($(this).attr('id'));});
</script>
EOJS;
		}
	}
	if($_GET['do'] == 'user_pref')
	{
		$admin_content .= edit_config('users', array('users'), array($sess_data['user_index']), array('real_name', 'password', 'email'), '', array('no_idx_nav'));
	}
	elseif($_GET['do'] == 'pages_order')
	{
		$admin_content .= '<div id="lang_button_set">'."\n";
		$i = 0;
		foreach($cms['avail_page_lang']['value'] as $k => $v)
		{
			$sel = '';
			if(0 === $i && !$_GET['lang'])
			{
				$sel = ' checked="checked"';
				$_GET['lang'] = $v['lang'];
			}
			elseif($v['lang'] == $_GET['lang'])
				$sel = ' checked="checked"';
			$admin_content .= '<input type="radio"'.$sel.' id="'.$k.'" name="fe_lang" value="'.$v['lang'].'" /><label for="'.$k.'">'.$v['lang_name'].'</label>'."\n";
			$i++;
		}
		$glob_var['lang_edit_now'] = $cms['avail_page_lang']['index']['lang'][$_GET['lang']];
		$admin_content .= '</div><script type="text/javascript">$(\'#lang_button_set\').buttonset().change(function(ev){ window.location = \'?admin&do='.$_GET['do'].'&lang=\'+$(this).find(\':checked\').val(); });</script>'."\n";
		$admin_content .= edit_config('pages', array('pages'), array(), array(), 'lang='.$cms['avail_page_lang']['index']['lang'][$_GET['lang']]);
	}
	elseif($_GET['do'] == 'cms_config')
	{
		$admin_content .= '<h1>'.lecho('cms_config_header', $admin_lang).'</h1>'."\n";
		$admin_content .= edit_config('cms', array('admin_language','page_author','avail_page_lang'));
	}
	elseif($_GET['do'] == 'textblock')
	{
		$admin_content .= '<h1>'.lecho('cms_config_textblock', $admin_lang).'</h1>'."\n";
		$admin_content .= edit_config('textblock');
	}
	elseif($_GET['do'] == 'reservations')
	{
		$admin_content .= '<h1>'.lecho('cms_config_reservations', $admin_lang).'</h1>'."\n";
		$admin_content .= '<div id="cal_button_set">'."\n";
		$i = 0;
		$calendars = get_config_data('res_cal', 'calendar');
		foreach($calendars as $k => $v)
		{
			$sel = '';
			if(0 === $i)
			{
				if($_GET['cal_idx'] == 'null' || !$_GET['cal_idx'])
					$sel = ' checked="checked"';
				$admin_content .= '<input type="radio" '.$sel.' id="null" name="cal_idx" value="null" /><label for="null">all</label>'."\n";
				$sel = '';
			}
			if($_GET['cal_idx'] == $k)
				$sel = ' checked="checked"';
			$admin_content .= '<input type="radio" '.$sel.' id="'.$k.'" name="cal_idx" value="'.$k.'" /><label for="'.$k.'">'.$v['name'].'</label>'."\n";
			$i++;
		}
		$admin_content .= '</div><script type="text/javascript">$(\'#cal_button_set\').buttonset().change(function(ev){ window.location = \'?admin&do='.$_GET['do'].'&cal_idx=\'+$(this).find(\':checked\').val(); });</script>'."\n";
		if($_GET['cal_idx'] == 'null' || !$_GET['cal_idx'])
		{
			$admin_content .= edit_config('res_cal', array('calendar'), array(), array('name','type','settings','form_settings','include'));
		}
		else 
		{
			$admin_content .= reservation_calendar($_GET['cal_idx'], $admin_lang);
			$admin_content .= '<div class="add_timespan_buttonset"><span class="add_timespan mark_reserved">'.lecho('cal_admin_add_timespan_reserved', $admin_lang).'</span> <span class="add_timespan mark_free">'.lecho('cal_admin_add_timespan_free', $admin_lang).'</span> <span class="add_timespan mark_unselect">'.lecho('cal_admin_add_timespan_unselect', $admin_lang).'</span></div>'."\n";
			$admin_content .= <<<EOJS
<script type="text/javascript">
var select_begin=0, select_end=0, reserved='{$calendars[$_GET['cal_idx']]['reserved']}'.split('|').sort(), cal_idx='{$_GET['cal_idx']}';
let_select();
</script>
EOJS;
		}
	}
	elseif(!$_GET['do'])
	{
		$admin_content .= '<pre>'.print_r($_POST, true).'</pre>';
		if($_POST && true === captcha(array('answer'=>$_POST['answer'], 'humanfilter'=>$_POST['humanfilter'])))
		{
			$admin_content .= 'is true<br/>'."\n";
			#$admin_content .= 'mail sent: '. var_export(mail('pat.bass@gmx.de', 'betreff', 'nachricht'), true) ."<br/>\n";
		}
		#$admin_content .= 'admin content<br/>'."\n";
		#$admin_content .= gen_captcha_styles();
		$captcha = captcha();
		$target = merge_href();
		$admin_content .= <<<EOT
<form action="{$target}" method="post">
<div class="form_row"><label for="feld1" class="main">irgendwas:</label><input type="text" id="feld1" name="irgendein_feld" /></div>
<div class="form_row"><label class="main">captcha:</label>{$captcha[0]}</div>
<div class="form_row"><label for="answer" class="main">Antwort:</label><input type="text" name="answer" id="answer" /></div>
{$captcha[2]}
<div class="form_row"><input type="submit" /></div>
</form>
EOT;
	}
	#$admin_content .= "You are someone<br/>\n";
	#$admin_content .= '<a href="">huhu</a><pre>'.print_r($sess_data, true).'</pre>'."\n";
	#$admin_content .= print_r($sess_data, true)."<br/>\n";
	#$admin_content .= edit_config('pages', array(), array(), array(), '');
}
$admin_head_bar = '<div id="admin_head_bar" class="'.$sess_data['use_role'].'">'.$admin_head_bar.'</div>'."\n";
$page_out = $admin_header . '<div id="content_wrap" class="'.$sess_data['use_role'].'">' . $admin_content . '</div>' . $admin_head_bar . $admin_footer;

?>
