<?php

error_reporting(E_ALL ^ E_NOTICE);
setlocale(LC_ALL, 'de_DE.UTF-8');
require_once('cms/config/cms.php');
require_once('cms/system/functions.php');
$admin_lang = $cms['admin_language']['value'];
header('Content-Type: application/json; charset=utf-8');
$return = array();

if($_COOKIE['sess'])
{
	if($_GET['do'] === 'check_login' && $_POST['user'] && $_POST['md5_pass'])
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
		die(json_encode($return));
	}
	else 
	{
		check_session();
	}
	if(!isset($sess_data['role']))
	{
		setcookie('sess', '', 1);
	}
}
if(isset($sess_data['role']))
{
	if($_GET['do'] == 'send_config' && $_POST)
	{
		$merge = array();
		foreach($_POST as $k => $v)
		{
			$keys = explode(':', $k);
			if(2 === count($keys))
			{
				$merge[$keys[0]][] = array('var'=>$keys[1], 'value'=>$v);
			}
			elseif(4 === count($keys))
			{
				$merge[$keys[0]][] = array('var'=>$keys[1], 'index'=>$keys[2], 'key'=>$keys[3], 'value'=>$v);
			}
			elseif(3 === count($keys))
			{
				# sort_order & delete_index
				$v = explode(':', $v);
				if($keys[0] === 'sort_order')
				{
					$merge[$keys[1]][] = array('var'=>$keys[2], 'sort_order'=>$v);
				}
				elseif($keys[0] === 'delete_index')
				{
					foreach($v as $delk => $delv)
					{
						$merge[$keys[1]][] = array('var'=>$keys[2], 'index'=>$delv, 'delete'=>'1');
					}
				}
				#print_r($keys);
			}
			#print_r($keys);
		}
		#echo json_encode($merge);
		#die();
		$merge_return = '';
		foreach($merge as $k => $v)
		{
			$merge_return .= merge_config($k, $v);
		}
		if($merge_return)
		{
			echo json_encode(array('status'=>false, 'txt'=>$merge_return));
		}
		else 
		{
			echo json_encode(array('status'=>true, 'txt'=>'ok'));
		}
	}
}

?>
