<?php

function merge_config($file, $values, $mode='replace')
{
	global $$file;
	$file_name = 'cms/config/'.$file.'.php';
	require_once($file_name);
	#$conf_orig = $$file;
	$conf_chan = $$file;
	$validate_index = array();
	foreach($values as $k => $v)
	{
		#echo "change ${val_val['value']}<br/>\n";
		$val_key = $val_val['var'];
		$var = $val_key;
		$var = $val_val['var'];
		$index = $val_val['index'];
		$key = $val_val['key'];
		$value = $val_val['value'];
		if(!'1' == $conf_chan[$v['var']]['model'][$v['key']]['notrim'] && !'1' == $conf_chan[$v['var']]['notrim'])
		{
			$v['value'] = trim($v['value']);
		}
		if(!isset($conf_chan[$v['var']]))
		{
			die("no key ${v['var']} in $file.");
		}
		if($conf_chan[$v['var']]['type'] === 'array' && is_array($v))
		{
			if('[' === substr($v['index'], 0, 1) && ']' === substr($v['index'], -1))
			{
				$idx = $conf_chan[$v['var']]['index'];
				$idx = substr($v['index'], 1, -1);
				$idx = explode('==', $idx, 2);
				$idx = $conf_chan[$v['var']]['index'][$idx[0]][$idx[1]];
				$v['index'] = $idx;
			}
			if(!isset($conf_chan[$v['var']]['value'][$v['index']]))
			{
				if('_new_' == substr($v['index'], 0, 5))
				{
					$validate_index[] = array('var' => $v['var'], 'index' => $v['index']);
				}
				else 
				{
					die("no index ${v['index']} in ${v['var']} in $file.");
				}
			}
			$config_check_types = config_check_types($conf_chan[$v['var']]['model'][$v['key']], $v['value']);
			if(true === $config_check_types)
			{
				if('1' == $conf_chan[$v['var']]['model'][$v['key']]['index'] && isset($conf_chan[$v['var']]['index'][$v['key']][$v['value']]))
				{
					die('double index field');
				}
				else 
				{
					$conf_chan[$v['var']]['value'][$v['index']][$v['key']] = $v['value'];
					if('1' == $conf_chan[$v['var']]['model'][$v['key']]['index'])
					{
						$conf_chan[$v['var']]['index'][$v['key']][$v['value']] = $v['index'];
					}
				}
			}
			else 
			{
				die(var_dump($config_check_types));
			}
		}
		else 
		{
			$config_check_types = config_check_types($conf_chan[$v['var']], $v['value']);
			if(true === $config_check_types)
			{
				$conf_chan[$v['var']]['value'] = $v['value'];
			}
			else 
			{
				die(var_dump($config_check_types));
			}
		}
	}
	if(0 < count($validate_index))
	{
		foreach($validate_index as $k => $v)
		{
			$new_index = new_config_key(array_keys($conf_chan[$v['var']]['value']));
			#var_dump(array_keys($conf_chan[$v['var']]['value']));
			#echo new_config_key(array_keys($conf_chan[$v['var']]['value']))."<br/>\n";;
			#var_dump(array_keys($conf_chan[$v['var']]['model']));
			foreach($conf_chan[$v['var']]['model'] as $mod_k => $mod_v)
			{
				if(!isset($conf_chan[$v['var']]['value'][$v['index']][$mod_k]))
				{
					$conf_chan[$v['var']]['value'][$v['index']][$mod_k] = '';
				}
				if('id' == $mod_k)
				{
					$conf_chan[$v['var']]['value'][$v['index']][$mod_k] = $new_index;
				}
				if( ( '1' == $mod_v['must'] || '1' == $mod_v['index'] ) && '' === $conf_chan[$v['var']]['value'][$v['index']][$mod_k])
				{
					die("$mod_k of ${v['var']} can not be empty.");
				}
			}
			$conf_chan[$v['var']]['value'][$new_index] = $conf_chan[$v['var']]['value'][$v['index']];
			unset($conf_chan[$v['var']]['value'][$v['index']]);
		}
		#die(var_dump($validate_index));
	}
	$conf_chan = config_reindex($conf_chan);
	#echo "chang_conf: <br/>\n";
	#echo '<pre>'.print_r($conf_chan, true)."</pre><br/>\n";
	#echo "orig: ${k}<br/>\n";
	#echo '<pre>'.print_r($conf_orig, true)."</pre><br/>\n";
	#$diff = array_multi_diff($conf_orig, $conf_chan);
	#echo "diff: ${k}<br/>\n";
	#echo '<pre>'.print_r($diff, true)."</pre><br/>\n";
	#die();
	write_config($file, $conf_chan);
}
function array_multi_diff($a, $b, $path='')
{
	/*if(is_array($a) && is_array($b))
	{
		
		foreach($a as $k => $v)
		{
			$diff = array_diff($a[$k]
			die(print_r(array_diff($a[$k], $b[$k]), true));
		}
	}
	else 
	{
		return (is_array($a))? $a : $b;
	}*/
	return array('ha'=>'hu');
}
function config_reindex($config)
{
	global $array_sort_keys_list;
	foreach($config as $k => $v)
	{
		#echo "check $k<br/>\n";
		if('array' == $v['type'])
		{
			unset($config[$k]['index'], $config[$k]['list']);
			if(is_array($v['model']))
			{
				foreach($v['model'] as $mod_k => $mod_v)
				{
					if('1' == $mod_v['index'] || 'id' == $mod_k)
					{
						$config[$k]['index'][$mod_k] = array();
					}
					if('1' == $mod_v['list'])
					{
						$config[$k]['list'][$mod_k] = array();
					}
				}
				foreach($v['value'] as $val_k => $val_v)
				{
					if(is_array($config[$k]['index']))
					{
						foreach($config[$k]['index'] as $idx_k => $idx_v)
						{
							$config[$k]['index'][$idx_k][$val_v[$idx_k]] = $val_k;
						}
					}
					if(is_array($config[$k]['list']))
					{
						foreach($config[$k]['list'] as $lst_k => $lst_v)
						{
							$config[$k]['list'][$lst_k][$val_v[$lst_k]][] = $val_k;
						}
					}
					$array_sort_keys_list = array_keys($v['model']);
					uksort($config[$k]['value'][$val_k], 'array_sort_keys');
				}
			}
		}
		$array_sort_keys_list = array('value','type','match','model','index','list','must','keep','notrim','options');
		uksort($config[$k], 'array_sort_keys');
	}
	return $config;
}
function array_sort_keys($a, $b)
{
	global $array_sort_keys_list;
	if(false === in_array($a, $array_sort_keys_list) || false === in_array($b, $array_sort_keys_list))
	{
		return 0;
	}
	$i_list = array_flip($array_sort_keys_list);
	#die("a: $a - b: $b");
	return $i_list[$a] - $i_list[$b];
}
function config_check_types($conf_val, $new_val)
{
	if($conf_val['type'] === 'select_one')
	{
		if(in_array($new_val, $conf_val['options']))
		{
			return true;
		}
		else 
		{
			return 'conf_err_not_in_options';
		}
	}
	elseif($conf_val['type'] === 'text')
	{
		if( ( isset($conf_val['match']) && 1 === preg_match('/'.$conf_val['match'].'/', $new_val) ) || !isset($conf_val['match']) )
		{
			return true;
		}
		else 
		{
			return 'conf_err_not_match';
		}
	}
	elseif($conf_val['type'] === 'email')
	{
		if( true === check_email_address($new_val) )
		{
			return true;
		}
		else 
		{
			return 'conf_err_not_email';
		}
	}
	elseif($conf_val['type'] === 'password')
	{
		if(0 < strlen($new_val))
		{
			return true;
		}
		else 
		{
			return 'conf_err_empty_password';
		}
	}
	return 'conf_err_unknown_type_of_conf_var';
}
function write_config($file, $value)
{
	global $$file;
	$file_name = 'cms/config/'.$file.'.php';
	for($i=8; $i>=0; $i--)
	{
		$bak_file_1 = 'cms/config/'.$file.'.'.$i.'.php';
		$bak_file_2 = 'cms/config/'.$file.'.'.($i+1).'.php';
		if($i === 8 && is_file($bak_file_2))
		{
			unlink($bak_file_2);
		}
		if(0 === $i)
		{
			$bak_file_1 = $file_name;
		}
		if(is_file($bak_file_1))
		{
			#copy($bak_file_1, $bak_file_2);
			rename($bak_file_1, $bak_file_2);
		}
	}
	if(!is_writable($file_name) && !is_writable('cms/config/'))
	{
		die("${file_name} not writeable or present.\n");
	}
	file_put_contents($file_name, '<?php # '.date('r')."\n\n\$$file = ".preg_replace('/^( +)/me', "str_repeat(\"\t\", (strlen('$1')/2))", var_export($value, true)).";\n\n?>");
	$$file = $value;
}
function check_email_address($email)
{
	// First, we check that there's one @ symbol, and that the lengths are right
	if (0 === preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email))
	{
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++)
	{
		if (0 === preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i]))
		{
			return false;
		}
	}
	if (0 === preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1]))  // Check if domain is IP. If not, it should be valid domain name
	{
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2)
		{
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++)
		{
			if (0 === preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i]))
			{
				return false;
			}
		}
	}
	return true;
}
function check_login($user, $pass, $p_type='plain')
{
	global $users;
	require_once('cms/config/users.php');
	$msg = array();
	$user_index = false;
	if(isset($users['users']['index']['user_name'][$_POST['user']]))
	{
		$user_index = $users['users']['index']['user_name'][$_POST['user']];
	}
	elseif(isset($users['users']['list']['email'][$_POST['user']]) && 1 === count($users['users']['list']['email'][$_POST['user']]))
	{
		$user_index = $users['users']['list']['email'][$_POST['user']][0];
		$user_val = $users['users']['value'][$user_index];
	}
	if(false !== $user_index)
	{
		$user_val = $users['users']['value'][$user_index];
		$pass = ($p_type == 'plain')? md5($pass) : $pass;
		if($pass === $user_val['password'])
		{
			$msg = array('status'=>true, 'txt'=>'check_login_success');
		}
		else 
		{
			$msg = array('status'=>false, 'txt'=>'check_login_wrong_pass');
		}
	}
	else 
	{
		$msg = array('status'=>false, 'txt'=>'check_login_wrong_user');
	}
	return $msg;
}
function get_user_data($user)
{
	global $users;
	require_once('cms/config/users.php');
	if(isset($users['users']['index']['user_name'][$user]))
	{
		return $users['users']['value'][$users['users']['index']['user_name'][$user]];
	}
	return array();
}
function lecho($t, $l, $m='cms')
{
	if($m == 'cms')
	{
		if(is_file('cms/lang/'.$l.'.php'))
		{
			include_once('cms/lang/'.$l.'.php');
			if(isset($lang[$t]))
			{
				return $lang[$t];
			}
		}
		if(is_file('cms/lang/en.php'))
		{
			include_once('cms/lang/en.php');
			if(isset($lang[$t]))
			{
				return $lang[$t];
			}
		}
	}
	return '[['.$t.']]';
}
function get_include_file($f)
{
	$p = false;
	$path = 'cms/include_files/';
	$c = '';
	$admin = (false !== strpos($f, '_admin_'))? true : false;
	$type = false;
	if(0 === strpos($f, 'style_'))
	{
		$type = 'css';
		$p = $path.'css*.css';
	}
	elseif(0 === strpos($f, 'javascript_'))
	{
		$type = 'js';
		$p = $path.'js*.js';
	}
	if(false === $p)
	{
		return '';
	}
	$files = glob($p);
	sort($files);
	foreach($files as $file)
	{
		if( (true === $admin && false === strpos($file, '_noadmin_')) || (false === $admin && false === strpos($file, '_admin_')) )
		{
			#$c .= $file . "\n";
			$tmp_c = file_get_contents($file);
			if($type === 'css' && false === strpos($file, '.min.'))
			{
				#$tmp_c = preg_replace('#/\*.*?\*/#s', '', $tmp_c);
				#$tmp_c = preg_replace('#(?s)\s|/\*.*?\*/#s', '', $tmp_c);
				include_once('cms/system/css_compressor.php');
				$tmp_c = Minify_CSS_Compressor::process($tmp_c);
			}
			elseif($type === 'js' && false === strpos($file, '.min.'))
			{
				include_once('cms/system/jsmin.php');
				$tmp_c = JSMin::minify($tmp_c);
			}
			$c .= $tmp_c ."\n";
		}
	}
	#return "files for '$f' admin: $admin:\n".print_r($files, true)."\n$c\n";
	return $c;
}
function get_browser_lang($al) # not used
{
	$langs = array();
	if(isset($al))
	{
		preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $al, $lang_parse);
		if(count($lang_parse[1]))
		{
			$langs = array_combine($lang_parse[1], $lang_parse[4]);
			foreach($langs as $lang => $val)
			{
				$langs[$lang] = ($val === '')? 1 : $val;
			}
			arsort($langs, SORT_NUMERIC);
		}
	}
	return $langs;
}
function pwgen($l=8)
{
	do
	{
		$pw = mk_rand_str($l, '2-9a-hjkm-zA-HJ-NP-Z');
	} while( 0 === preg_match('/[a-z]/', $pw) || 0 === preg_match('/[A-Z]/', $pw) || 0 === preg_match('/[0-9]/', $pw) );
	return $pw;
}
function new_config_key($arr = array())
{
	do
	{
		$rand = mk_rand_str();
	} while (in_array($rand, $arr));
	return $rand;
}
function mk_rand_str($len = 5, $regex = '')
{
	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	if('' !== $regex)
	{
	$pool = preg_replace('/[^'.$regex.']/', '', $pool);
	}
	$pool = $pool . $pool;
	do 
	{
	$rand = substr(str_shuffle($pool), 0, $len);
	} while (1===preg_match('/[^0-9]/', $pool) && is_numeric(substr($rand, 0, 1)));
	return $rand;
}

?>
