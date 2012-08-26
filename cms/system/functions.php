<?php

function edit_config($file, $vars=array(), $indizes=array(), $keys=array(), $filter="", $nav_opts=array())
{
	global $$file, $admin_lang;
	$file_name = 'cms/config/'.$file.'.php';
	$out = '';
	require_once($file_name);
	if(0 === count($vars))
	{
		$vars = array_keys($$file);
	}
	#die(print_r($vars, true));
	if(false !== strpos($filter, '='))
	{
		$filter = explode('=', $filter);
	}
	$out .= '<form class="config" action="javascript:void(0)">'."\n";
	#$out .= '<input type="text" class="hidden no_parent changed" name="form_file_name" value="'.$file.'"/>'."\n";
	foreach($$file as $k => $v)
	{
		if(in_array($k, $vars))
		{
			#var_dump($v);
			#die('<pre>'.print_r($$file, true).'</pre>');
			if('array' == $v['type'])
			{
				if(0 === count($indizes))
				{
					$indizes = array_keys($v['value']);
					$indizes['auto'] = true;
				}
				$keep = (isset($v['keep']))? ' keep keep_'.$v['keep'] : '';
				$out .= '<fieldset class="sortable'.$keep.'"><legend>'.lecho('config_'.$k, $admin_lang).'</legend>'."\n";
				$even_odd = 'odd';
				$idx_keys = array_keys($v['value']);
				$idx_keys = implode(':', $idx_keys);
				$idx_name = 'sort_order:'.$file.':'.$k;
				$idx_name_del = 'delete_index:'.$file.':'.$k;
				$out .= '<input type="text" class="hidden no_parent sort_order" name="'.$idx_name.'" id="'.$idx_name.'" value="'.$idx_keys.'" />'."\n";
				$out .= '<input type="text" class="hidden no_parent delete_index" name="'.$idx_name_del.'" id="'.$idx_name_del.'" value="" />'."\n";
				if(0 === count($v['value']))
				{
					$indizes[] = '_new_1';
					foreach($v['model'] as $mod_k => $mod_v)
					{
						$v['value']['_new_1'][$mod_k] = '';
						if('select_more' == $mod_v['type'])
						{
							$v['value']['_new_1'][$mod_k] = array();
						}
					}
				}
				foreach($v['value'] as $idx_k => $idx_v)
				{
					#if($k === 'menu_admin')die('<pre>'.print_r($indizes, true).'</pre>');
					if(in_array($idx_k, $indizes) && (!is_array($filter) || ($idx_v[$filter[0]] == $filter[1])) )
					{
						if(0 === count($keys))
						{
							$keys = array_keys($idx_v);
							$keys['auto'] = true;
						}
						$even_odd = ($even_odd=='odd')? 'even' : 'odd';
						$out .= '<div id="'.$k.':'.$idx_k.'" class="idx_group '.$even_odd.'">'."\n";
						foreach($v['model'] as $mod_k => $mod_v)
						{
							if(in_array($mod_k, $keys))
							{
								if('id' != $mod_k)
								{
									$mod_v['value'] = $idx_v[$mod_k];
									$out .= gen_config_field($mod_v, array('file'=>$file,'val'=>$k,'index'=>$idx_k,'key'=>$mod_k));
								}
							}
						}
						if(true === $keys['auto'])
						{
							$keys = array();
						}
						#$out .= '<div class="idx_nav ui-widget ui-state-default"><a href="#" class="ui-icon ui-icon-arrowthick-1-n ui-state-default ui-corner-all sort_up"></a><a href="#" class="sort_down">down</a></div></div>';
						if(!in_array('no_move_free', $nav_opts) && !in_array('no_idx_nav', $nav_opts))
							$out .= '<ul class="idx_nav move_free ui-widget ui-helper-clearfix"><li class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrow-4"></span></li></ul>'."\n";
						if(!in_array('no_move_down', $nav_opts) && !in_array('no_idx_nav', $nav_opts))
							$out .= '<ul class="idx_nav move_down ui-widget ui-helper-clearfix"><li class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-s"></span></li></ul>'."\n";
						if(!in_array('no_move_up', $nav_opts) && !in_array('no_idx_nav', $nav_opts))
							$out .= '<ul class="idx_nav move_up ui-widget ui-helper-clearfix"><li class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-n"></span></li></ul>'."\n";
						if(!in_array('no_insert_new', $nav_opts) && !in_array('no_idx_nav', $nav_opts))
							$out .= '<ul class="idx_nav insert_new ui-widget ui-helper-clearfix"><li class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-plus"></span></li></ul>'."\n";
						if(!in_array('no_delete', $nav_opts) && !in_array('no_idx_nav', $nav_opts))
							$out .= '<ul class="idx_nav delete ui-widget ui-helper-clearfix"><li class="ui-state-default ui-corner-all"><span class="ui-icon ui-icon-trash"></span></li></ul>'."\n";
						if(1 === count($v['value']) && '_new_1' == $idx_k)
						{
							$out .= '<div class="activate_new_index" title="'.lecho('click_to_activate', $admin_lang).'"></div>'."\n";
						}
						$out .= '</div>'."\n";
					}
				}
				if(true === $indizes['auto'])
				{
					$indizes = array();
				}
				$out .= '</fieldset>'."\n";
			}
			else 
			{
				#$out .= '<fieldset><legend>'.lecho('legend_'.$k, $admin_lang).'</legend>'."\n";
				$out .= gen_config_field($v, array('file'=>$file,'val'=>$k));
				#$out .= '</fieldset>'."\n";
			}
		}
	}
	#$out .= '<div class="form_submit"><input type="submit" value="'.lecho('button_submit', $admin_lang).'"/> <input type="reset" value="'.lecho('button_reset', $admin_lang).'"/></div>'."\n".'</form>'."\n";
	$out .= '<div class="form_submit"><input type="submit" value="'.lecho('button_submit', $admin_lang).'"/></div>'."\n".'</form>'."\n";
	#echo $out;
	#die();
	return $out;
}
function gen_config_field($v, $k)
{
	global $admin_lang, $glob_var;
	$fid = "${k['file']}:${k['val']}";
	$label = 'config_'.$k['val'];
	if(isset($k['index']) && isset($k['key']))
	{
		$fid .= ":${k['index']}:${k['key']}";
		$label .= '_'.$k['key'];
		#var_dump($v);
	}
	$out = '<div class="form_row '.$v['type'].'">'."\n".'<label class="main" for="'.$fid.'">'.lecho($label, $admin_lang).'</label>'."\n";
	$class = '';
	$class .= ($v['must'])? ' must' : '';
	$class .= ($v['match'])? ' match match_'.rawurlencode($v['match']) : '';
	$class .= ($v['notrim'])? ' notrim' : '';
	#$match = ($v['match'])? ' match="'.htmlspecialchars($v['match']).'"' : '';
	switch($v['type'])
	{
		case 'select_one':
			$out .= '<select name="'.$fid.'" id="'.$fid.'">'."\n";
			if(isset($v['options']['from_config']))
			{
				$conf_idx = explode(':', $v['options']['from_config']);
				$conf_opts = get_config_data($conf_idx[0], $conf_idx[1]);
				#die(var_dump($conf_opts));
				unset($filter);
				if(isset($v['options']['filter']))
				{
					$filter = explode('=', $v['options']['filter']);
					if('$' == substr($filter[1], 0, 1))
					{
						$filter[1] = $glob_var[substr($filter[1], 1)];
					}
				}
				$v['options'] = array();
				if(isset($v['allow_none']))
				{
					$v['options'][] = '---';
				}
				foreach($conf_opts as $ok => $ov)
				{
					if(is_array($filter))
					{
						if($ov[$filter[0]] == $filter[1])
						{
							$v['options'][$ok] = $ov[$conf_idx[2]];
						}
					}
					else 
					{
						$v['options'][$ok] = $ov[$conf_idx[2]];
					}
				}
			}
			#else 
			#{
				foreach($v['options'] as $ok => $ov)
				{
					$sel = '';
					if(is_numeric($ok) && $ov == $v['value'])
						$sel = ' selected="selected"';
					elseif(!is_numeric($ok) && $ok == $v['value'])
						$sel = ' selected="selected"';
					$out .= '<option value="'.$ok.'"'.$sel.'>'.htmlspecialchars($ov).'</option>'."\n";
				}
			#}
			$out .= '</select>'."\n";
			break;
		case 'select_more':
			if(isset($v['options']['from_config']))
			{
				$conf_idx = explode(':', $v['options']['from_config']);
				$conf_opts = get_config_data($conf_idx[0], $conf_idx[1]);
				#die(var_dump($conf_opts));
				$v['options'] = array();
				foreach($conf_opts as $ok => $ov)
				{
					$v['options'][$ok] = $ov[$conf_idx[2]];
				}
			}
			$val = '';
			foreach($v['options'] as $ok => $ov)
			{
				$sel = '';
				#die('<pre>'.print_r($v, true).'</pre>');
				if(is_array($v['value']) && is_numeric($ok) && in_array($ov, $v['value']))
					$sel = ' checked="checked"';
				elseif(is_array($v['value']) && !is_numeric($ok) && in_array($ok, $v['value']))
					$sel = ' checked="checked"';
				#$sel = (in_array($ov, $v['value']))? ' checked="checked"' : '';
				$val .= ($sel)? ':'.$ok : '';
				$out .= '<div class="more_row"><input type="checkbox" name="'.$fid.'.'.$ok.'" id="'.$fid.'.'.$ok.'"'.$sel.' value="'.$ok.'"/><label class="each" for="'.$fid.'.'.$ok.'">'.htmlspecialchars($ov).'</label></div>'."\n";
			}
			$out .= '<input type="text" class="hidden select_more_value'.$class.'" name="'.$fid.'" id="'.$fid.'" value="'.substr($val, 1).'" />'."\n";
			break;
		case 'password':
			$out .= '<input type="password" class="'.$class.'" name="'.$fid.'" id="'.$fid.'" value="'.htmlspecialchars($v['value']).'" />'."\n";
			break;
		case 'email':
			$out .= '<input type="text" class="email'.$class.'" name="'.$fid.'" id="'.$fid.'" value="'.htmlspecialchars($v['value']).'" />'."\n";
			break;
		case 'text':
		default:
			$out .= '<input type="text" class="'.$class.'" '.$match.' name="'.$fid.'" id="'.$fid.'" value="'.htmlspecialchars($v['value']).'" />'."\n";
	}
	$out .= '<span class="ui-icon ui-icon-info" title="'.lecho('help_'.$label, $admin_lang).'"></span><span class="ui-icon ui-icon-arrowreturnthick-1-s" title="'.lecho('help_field_reset', $admin_lang).'"></span>'."\n".'</div>'."\n";
	return $out;
}
function merge_config($file, $values, $select_one_more_value='key')
{
	global $$file, $array_sort_keys_list;
	$file_name = 'cms/config/'.$file.'.php';
	require_once($file_name);
	#$conf_orig = $$file;
	$conf_chan = $$file;
	$validate_index = array();
	$resort_vars = array();
	foreach($values as $k => $v)
	{
		#echo "change ${val_val['value']}<br/>\n";
		if(isset($v['sort_order']))
		{
			#echo '<pre>'.print_r($conf_chan, true)."</pre><br/>\n";
			$resort_vars[$v['var']] = $v['sort_order'];
			#die(print_r($resort_vars, true));
		}
		elseif($v['delete'] && 'array' == $conf_chan[$v['var']]['type'])
		{
			if(intval($conf_chan[$v['var']]['keep']) <= count($conf_chan[$v['var']]['value']) -1)
			{
				if('[' === substr($v['index'], 0, 1) && ']' === substr($v['index'], -1))
				{
					$idx = $conf_chan[$v['var']]['index'];
					$idx = substr($v['index'], 1, -1);
					$idx = explode('=', $idx, 2);
					$idx = $conf_chan[$v['var']]['index'][$idx[0]][$idx[1]];
					$v['index'] = $idx;
				}
				unset($conf_chan[$v['var']]['value'][$v['index']]);
			}
		}
		else 
		{
			if(!isset($conf_chan[$v['var']]))
			{
				#die("no key ${v['var']} in $file.");
				return "no key ${v['var']} in $file.";
			}
			if(!'1' == $conf_chan[$v['var']]['model'][$v['key']]['notrim'] && !'1' == $conf_chan[$v['var']]['notrim'])
			{
				$v['value'] = trim($v['value']);
			}
			if('integer' == $conf_chan[$v['var']]['model'][$v['key']]['type'] || 'integer' == $conf_chan[$v['var']]['type'])
			{
				$v['value'] = intval($v['value']);
			}
			if( ('select_one' == $conf_chan[$v['var']]['model'][$v['key']]['type'] || 'select_one' == $conf_chan[$v['var']]['type']) && $select_one_more_value == 'key')
			{
				#print_r($v[ 'value']);
				if('array' == $conf_chan[$v['var']]['type'])
				{
					$opts = $conf_chan[$v['var']]['model'][$v['key']]['options'];
				}
				else 
				{
					$opts = $conf_chan[$v['var']]['options'];
				}
				if(!isset($opts['from_config']))
				{
					$v['value'] = $opts[$v['value']];
				}
				#print_r($opts);
				#print_r($v[ 'value']);
				#die();
			}
			if( ('select_more' == $conf_chan[$v['var']]['model'][$v['key']]['type'] || 'select_more' == $conf_chan[$v['var']]['type']) && $select_one_more_value == 'key')
			{
				if('array' == $conf_chan[$v['var']]['type'])
				{
					$opts = $conf_chan[$v['var']]['model'][$v['key']]['options'];
					#$v['value'] = $conf_chan[$v['var']]['model'][$v['key']]['options'][$v['value']];
				}
				else 
				{
					$opts = $conf_chan[$v['var']]['options'];
					#$v['value'] = $conf_chan[$v['var']]['options'][$v['value']];
				}
				$tmp = explode(':', $v['value']);
				if(1 === count($tmp) && !$tmp[0])
					$tmp = array();
				#die('<pre>'.print_r($tmp, true).'</pre>');
				$v['value'] = array();
				if(!isset($opts['from_config']))
				{
					foreach($tmp as $ok => $ov)
					{
						if(isset($opts[$ov]))
						{
							$v['value'][] = $opts[$ov];
						}
					}
				}
				else 
				{
					$v['value'] = $tmp;
				}
			}
			if($conf_chan[$v['var']]['type'] === 'array' && is_array($v))
			{
				if('[' === substr($v['index'], 0, 1) && ']' === substr($v['index'], -1))
				{
					$idx = $conf_chan[$v['var']]['index'];
					$idx = substr($v['index'], 1, -1);
					$idx = explode('=', $idx, 2);
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
						#die("no index ${v['index']} in ${v['var']} in $file.");
						return "no index ${v['index']} in ${v['var']} in $file.";
					}
				}
				$config_check_types = config_check_types($conf_chan[$v['var']]['model'][$v['key']], $v['value']);
				if(true === $config_check_types || (is_array($config_check_types) && isset($config_check_types['new_val'])) )
				{
					if(is_array($config_check_types) && isset($config_check_types['new_val']))
					{
						$v['value'] = $config_check_types['new_val'];
					}
					if('1' == $conf_chan[$v['var']]['model'][$v['key']]['index'] && isset($conf_chan[$v['var']]['index'][$v['key']][$v['value']]))
					{
						#die('double index field');
						return 'double index field';
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
					#die(var_dump($config_check_types));
					return print_r($config_check_types, true);
				}
			}
			else 
			{
				$config_check_types = config_check_types($conf_chan[$v['var']], $v['value']);
				if(true === $config_check_types)
				{
					$conf_chan[$v['var']]['value'] = $v['value'];
				}
				elseif(is_array($config_check_types) && isset($config_check_types['new_val']))
				{
					$conf_chan[$v['var']]['value'] = $config_check_types['new_val'];
				}
				else 
				{
					#die(var_dump($config_check_types));
					return print_r($config_check_types, true);
				}
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
					#die("$mod_k of ${v['var']} can not be empty.");
					return "$mod_k of ${v['var']} can not be empty.";
				}
			}
			$conf_chan[$v['var']]['value'][$new_index] = $conf_chan[$v['var']]['value'][$v['index']];
			unset($conf_chan[$v['var']]['value'][$v['index']]);
			if(is_array($resort_vars[$v['var']]) && in_array($v['index'], $resort_vars[$v['var']]))
			{
				$resort_key = array_search($v['index'], $resort_vars[$v['var']]);
				$resort_vars[$v['var']][$resort_key] = $new_index;
			}
		}
	}
	#die(var_dump($validate_index));
	if(0 < count($resort_vars))
	{
		#var_dump($resort_vars);
		foreach($resort_vars as $k => $v)
		{
			$array_sort_keys_list = $v;
			uksort($conf_chan[$k]['value'], 'array_sort_keys');
		}
		#die('sorted');
		
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
		$array_sort_keys_list = array('value','type','match','must','keep','notrim','model','index','list','options');
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
	if( ('select_more' === $conf_val['type'] || 'select_one' === $conf_val['type']) && isset($conf_val['options']['from_config']))
	{
		$conf_idx = explode(':', $conf_val['options']['from_config']);
		$conf_opts = get_config_data($conf_idx[0], $conf_idx[1]);
		#die(print_r($conf_opts));
		$conf_val['options'] = array();
		if(isset($conf_val['allow_none']))
		{
			#$conf_val['options'][] = '---';
			$conf_val['options'][] = 0;
		}
		foreach($conf_opts as $ok => $ov)
		{
			#$conf_val['options'][] = $ov[$conf_idx[2]];
			$conf_val['options'][] = $ok;
		}
		#die('<pre>'.print_r($new_val, true).'</pre>');
		/*if(is_array($new_val))
		{
			foreach($new_val as $k => $v)
			{
				if(isset($conf_val['options'][$v]))
				{
					$new_val[$k] = $conf_val['options'][$v];
				}
				else 
				{
					unset($new_val[$k]);
				}
			}
		}
		else 
		{
			if(isset($conf_val['options'][$new_val]))
			{
				$new_val = $conf_val['options'][$new_val];
			}
			else 
			{
				$new_val = '';
			}
		}*/
		#print_r("\nnew\n");
		#print_r($new_val);
		#print_r("\nconf\n");
		#print_r($conf_val);
		#die();
	}
	if($conf_val['type'] === 'select_one')
	{
		if(in_array($new_val, $conf_val['options']))
		{
			return array('new_val'=>$new_val);
		}
		else 
		{
			return 'conf_err_not_in_options';
		}
	}
	if($conf_val['type'] === 'select_more')
	{
		if(!is_array($new_val))
		{
			return 'conf_err_not_array';
		}
		foreach($new_val as $k => $v)
		{
			if($v && !in_array($v, $conf_val['options']))
			{
				return 'conf_err_not_in_options';
			}
		}
		return array('new_val'=>$new_val);
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
	$no_versions = array('sessions', 'bad_logins', 'cache_files');
	if(false === in_array($file, $no_versions))
	{
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
	}
	if(!is_writable($file_name) && !is_writable('cms/config/'))
	{
		die("${file_name} not writeable or present.\n");
	}
	file_put_contents($file_name, '<?php # '.date('r')."\n\n\$$file = ".preg_replace('/^( +)/me', "str_repeat(\"\t\", (strlen('$1')/2))", var_export($value, true)).";\n\n?>");
	@chmod($file_name, 0666);
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
function session_my_register()
{
	global $sessions, $sess_data;
	require_once('cms/config/sessions.php');
	$send_data = array();
	foreach($sessions['sessions']['value'] as $k => $v)
	{
		if(time() - strtotime($v['time']) > 2*$sessions['keep_alive']['value'])
		{
			unset($sessions['sessions']['value'][$k]);
			$send_data[] = array('var'=>'sessions', 'index'=>$k, 'delete'=>$k);
		}
	}
	if(0 < count($send_data))
	{
		$sessions = config_reindex($sessions);
		#merge_config('sessions', $send_data);
	}
	$user_data = get_user_data($_POST['user']);
	unset($user_data['password']);
	$sess_data = array(
		'sess' => $_COOKIE['sess'],
		#'ua' => $_SERVER['HTTP_USER_AGENT'],
		#'ip' => $_SERVER['REMOTE_ADDR'],
		#'time' => 
		'user_name' => $user_data['user_name'],
		'real_name' => $user_data['real_name'],
		'role' => $user_data['role'],
		'email' => $user_data['email'],
	);
	$sess_data['use_role'] = ($user_data['role'] == 'admin')? 'admin' : 'user';
	$send_data[] = array('var'=>'sessions', 'index'=>'_new_1', 'key'=>'sess', 'value'=>$_COOKIE['sess']);
	$send_data[] = array('var'=>'sessions', 'index'=>'_new_1', 'key'=>'ua', 'value'=>$_SERVER['HTTP_USER_AGENT']);
	$send_data[] = array('var'=>'sessions', 'index'=>'_new_1', 'key'=>'ip', 'value'=>$_SERVER['REMOTE_ADDR']);
	$send_data[] = array('var'=>'sessions', 'index'=>'_new_1', 'key'=>'time', 'value'=>date('r'));
	$send_data[] = array('var'=>'sessions', 'index'=>'_new_1', 'key'=>'user_index', 'value'=>$user_data['id']);
	$send_data[] = array('var'=>'sessions', 'index'=>'_new_1', 'key'=>'user_name', 'value'=>$user_data['user_name']);
	$send_data[] = array('var'=>'sessions', 'index'=>'_new_1', 'key'=>'real_name', 'value'=>$user_data['real_name']);
	$send_data[] = array('var'=>'sessions', 'index'=>'_new_1', 'key'=>'email', 'value'=>$user_data['email']);
	$send_data[] = array('var'=>'sessions', 'index'=>'_new_1', 'key'=>'role', 'value'=>$user_data['role']);
	$send_data[] = array('var'=>'sessions', 'index'=>'_new_1', 'key'=>'use_role', 'value'=>$sess_data['use_role']);
	#die('now merge session: '.print_r($send_data, true));
	merge_config('sessions', $send_data);
}
function check_session()
{
	global $sessions, $sess_data;
	require_once('cms/config/sessions.php');
	if(32 != strlen($_COOKIE['sess']))
	{
		return false;
	}
	$sess_key = $sessions['sessions']['index']['sess'][$_COOKIE['sess']];
	if(!isset($sessions['sessions']['value'][$sess_key]))
	{
		return false;
	}
	$sess_data = $sessions['sessions']['value'][$sess_key];
	if($sessions['keep_alive']['value'] < time() - strtotime($sess_data['time']) || $_SERVER['HTTP_USER_AGENT'] !== $sess_data['ua'] || $_SERVER['REMOTE_ADDR'] != $sess_data['ip'])
	{
		merge_config('sessions', array(array('var'=>'sessions', 'index'=>$sess_key, 'delete'=>'1')));
		$sess_data = array();
		return false;
	}
	merge_config('sessions', array(array('var'=>'sessions', 'index'=>$sess_key, 'key'=>'time', 'value'=>date('r'))));
	$sess_data = $sessions['sessions']['value'][$sess_key];
	return true;
}
function check_logout()
{
	global $sessions, $sess_data;
	require_once('cms/config/sessions.php');
	merge_config('sessions', array(array('var'=>'sessions', 'index'=>'[sess='.$_COOKIE['sess'].']', 'delete'=>'1',)));
	$sess_data = array();
}
function check_login($user, $pass, $p_type='plain')
{
	global $users, $bad_logins;
	require_once('cms/config/users.php');
	require_once('cms/config/bad_logins.php');
	$msg = array();
	$merge_delete = array();
	foreach($bad_logins['login']['value'] as $k => $v)
	{
		if(time() - strtotime($v['last_try']) > 3*60*60)
		{
			unset($bad_logins['login']['value'][$k]);
			$merge_delete[] = array('var'=>'login', 'index'=>$k, 'delete' => $k);
		}
	}
	if(0 < count($merge_delete))
	{
		$bad_logins = config_reindex($bad_logins);
		merge_config('bad_logins', $merge_delete);
	}
	$ip_key = $bad_logins['login']['index']['ip'][$_SERVER['REMOTE_ADDR']];
	if($ip_key)
	{
		$last_try = strtotime($bad_logins['login']['value'][$ip_key]['last_try']);
		$count = intval($bad_logins['login']['value'][$ip_key]['count']);
		if($count >= 3 && time()-$last_try < 10*60)
		{
			return array('status' => false, 'txt' => 'too_often_bad_login');
		}
	}
	$user_index = false;
	if(isset($users['users']['index']['user_name'][$_POST['user']]))
	{
		$user_index = $users['users']['index']['user_name'][$_POST['user']];
	}
	elseif(isset($users['users']['list']['email'][$_POST['user']]) && 1 === count($users['users']['list']['email'][$_POST['user']]))
	{
		$user_index = $users['users']['list']['email'][$_POST['user']][0];
		$_POST['user'] = $users['users']['value'][$user_index]['user_name'];
	}
	if(false !== $user_index)
	{
		#$user_val = $users['users']['value'][$user_index];
		$pass = ($p_type == 'plain')? md5($pass) : $pass;
		if($pass === $users['users']['value'][$user_index]['password'])
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
	if(false === $msg['status'])
	{
		#global $bad_logins;
		#require_once('cms/config/bad_logins.php');
		/*foreach($bad_logins['login']['value'] as $k => $v)
		{
		
		}*/
		#$ip_key = $bad_logins['login']['index']['ip'][$_SERVER['REMOTE_ADDR']];
		$send_data = array();
		if(!$ip_key)
		{
			$ip_key = '_new_1';
			$send_data[] = array('var'=>'login', 'index'=>$ip_key, 'key'=>'ip', 'value'=>$_SERVER['REMOTE_ADDR']);
			$count = 0;
		}
		else 
		{
			$count = intval($bad_logins['login']['value'][$ip_key]['count']);
		}
		$send_data[] = array('var'=>'login', 'index'=>$ip_key, 'key'=>'last_try', 'value'=>date('r'));
		$send_data[] = array('var'=>'login', 'index'=>$ip_key, 'key'=>'count', 'value'=>($count+1));
		merge_config('bad_logins', $send_data);
	}
	if(true === $msg['status'] && isset($bad_logins['login']['index']['ip'][$_SERVER['REMOTE_ADDR']]))
	{
		merge_config('bad_logins', array(array('var'=>'login', 'index'=>$bad_logins['login']['index']['ip'][$_SERVER['REMOTE_ADDR']], 'delete'=>'1')));
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
function get_config_data($file, $var, $index = false, $key = false)
{
	global $$file;
	require_once('cms/config/'.$file.'.php');
	$file = $$file;
	if(false !== $index && false !== $key)
	{
		return $file[$var]['value'][$index][$key];
	}
	elseif(false === $key && false !== $index)
	{
		return $file[$var]['value'][$index];
	}
	else 
	{
		return $file[$var]['value'];
	}
}
function config2menu($file, $var)
{
	global $$file, $admin_lang;
	require_once('cms/config/'.$file.'.php');
	$out = '<ul class="menu ui-helper-clearfix">'."\n";
	$file = $$file;
	foreach($file[$var]['value'] as $k => $v)
	{
		if($v['is_sub_of'] == '0')
		{
			$out .= '<li><a title="'.lecho('help_menu_'.$v['name'], $admin_lang).'" href="'.htmlspecialchars($v['link']).'">'.htmlspecialchars($v['name']).'</a>';
			if(is_array($file[$var]['list']['is_sub_of'][$k]) 
				&& 0 < count($file[$var]['list']['is_sub_of'][$k]))
			{
				$out .= '<ul>'."\n";
				foreach($file[$var]['list']['is_sub_of'][$k] as $sk => $sv)
				{
					$out .= '<li><a title="'.lecho('help_menu_'.$file[$var]['value'][$sv]['name'], $admin_lang).'" href="'.htmlspecialchars($file[$var]['value'][$sv]['link']).'">'.htmlspecialchars($file[$var]['value'][$sv]['name']).'</a></li>'."\n";
				}
				$out .= '</ul>'."\n";
			}
			$out .= '</li>'."\n";
		}
	}
	$out .= '</ul>'."\n";
	return $out;
}
function lecho($t, $l, $m='cms')
{
	#global $lang;
	if($m == 'cms')
	{
		if(is_file('cms/lang/'.$l.'.php'))
		{
			require('cms/lang/'.$l.'.php');
			if(isset($lang[$t]))
			{
				return $lang[$t];
			}
		}
		if(is_file('cms/lang/en.php'))
		{
			require('cms/lang/en.php');
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
	global $cms;
	require_once('cms/config/cms.php');
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
				###$tmp_c = preg_replace('#/\*.*?\*/#s', '', $tmp_c);
				###$tmp_c = preg_replace('#(?s)\s|/\*.*?\*/#s', '', $tmp_c);
				if('On' == $cms['compress_css']['value'])
				{
					include_once('cms/system/css_compressor.php');
					$tmp_c = Minify_CSS_Compressor::process($tmp_c);
				}
			}
			elseif($type === 'js' && false === strpos($file, '.min.'))
			{
				if('On' == $cms['compress_js']['value'])
				{
					include_once('cms/system/jsmin.php');
					$tmp_c = JSMin::minify($tmp_c);
				}
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
function captcha($do=4)
{
	$arr = array('Z'=>'bD1','1'=>'fZr','B'=>'H7Q','v'=>'zQw','H'=>'GiR','s'=>'xCy','5'=>'LXl','G'=>'qri','4'=>'wNG','a'=>'opH','p'=>'sXV','x'=>'qyo','O'=>'UGk','F'=>'Ivz','l'=>'Az6','I'=>'L0W','e'=>'cLA','h'=>'nxR','7'=>'nih','V'=>'JsO','R'=>'UgO','k'=>'SDY','S'=>'fP4','0'=>'x6u','r'=>'HP0','D'=>'J9b','c'=>'BRH','L'=>'YcP','q'=>'mb6','3'=>'hX5','K'=>'udY','Y'=>'NSn','Q'=>'nQe','z'=>'XT1','g'=>'uhx','y'=>'e9C','N'=>'nh6','f'=>'wyA','C'=>'Vsk','2'=>'tCY','w'=>'FCb','P'=>'mrq','M'=>'BFh','d'=>'vwJ','i'=>'bsZ','J'=>'rqK','X'=>'E9B','9'=>'lV9','6'=>'nTj','T'=>'fgh','t'=>'KBI','U'=>'FEL','b'=>'AQh','8'=>'YBv','A'=>'iMy','m'=>'n2I','o'=>'Fq5','W'=>'Hjf','E'=>'pcU','n'=>'Es4','j'=>'Q5v','u'=>'h1S',);
	$class = 'humanfilter';
	$md5_salt_prefix = $_SERVER['HTTP_HOST'].'|->';
	if(is_array($do) && isset($do[$class]) && isset($do['answer']))
	{
		if(md5($md5_salt_prefix.$do['answer']) == $do[$class])
		{
			return true;
		}
		return false;
	}
	else 
	{
		$length = intval($do, 10);
		$length = (0 === $length)? 4 : $length;
		$str = mk_rand_str($length, 'a-hkm-z');
		$solution = md5($md5_salt_prefix.$str);
		$out = '<span class="'.$class.'_wrap">';
		for($i=0; $i<$length;$i++)
		{
			$out .= '<span class="'.$class.' '.$arr[substr($str, $i, 1)].'"></span>';
		}
		$out .= '</span>'."\n";
		$solution_input = "<input type=\"hidden\" name=\"$class\" value=\"$solution\" />";
		return array($out, $solution, $solution_input);
	}
}
function gen_captcha_styles()
{
	$str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	$font_color = '#333';
	$bg_color = '#eee';
	$css_class = 'humanfilter';
	$css_img_path = 'cms/include_files/images/humanfilter.png';
	#$css_img_path = 'b64';
	$do_it_with = 'php';
	#$do_it_with = 'google';


	$str = str_shuffle($str);
	$str_arr = str_split($str);
	$str_arr = array_flip($str_arr);
	$str_arr2 = array();
	foreach($str_arr as $k => $v)
	{
		do
			$rand = mk_rand_str(3);
		while(in_array($rand, $str_arr2));
		$str_arr2[$k] = $rand;
	}
	$str_arr = $str_arr2;
	$str_inx_arr = array_flip($str_arr);
	if('google' == $do_it_with)
	{
		$js = '<script type="text/javascript">WebFontConfig={google:{families:["Ubuntu+Mono::latin"]}};(function(){var a=document.createElement("script");a.src=("https:"==document.location.protocol?"https":"http")+"://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js";a.type="text/javascript";a.async="true";var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b)})();</script>';
		$prev = '<div style="font-family: \'Ubuntu Mono\';font-size:20px;text-shadow:none;color:'.$font_color.';background-color:'.$bg_color.';line-height:18px; width:'.(strlen($str)*10).'px; height:20px; border:1px solid #000;">' . $str . '</div>'."\n";
		$out = $js . $prev;
	}
	elseif('php' == $do_it_with)
	{
		$font = 'cms/include_files/UbuntuMono-R.ttf';
		$im = @imagecreate((strlen($str)*10), 20) # imagecreatetruecolor
			or die('Cannot Initialize new GD image stream');
		$bg_color = str_replace('#', '', $bg_color);
		$font_color = str_replace('#', '', $font_color);
		if(3 === strlen($bg_color))
			$bg_color = substr($bg_color, 0, 1).substr($bg_color, 0, 1).substr($bg_color, 1, 1).substr($bg_color, 1, 1).substr($bg_color, 2, 1).substr($bg_color, 2, 1);
		$bg_r = intval(substr($bg_color, 0, 2), 16);
		$bg_g = intval(substr($bg_color, 2, 2), 16);
		$bg_b = intval(substr($bg_color, 4, 2), 16);
		if(3 === strlen($font_color))
			$font_color = substr($font_color, 0, 1).substr($font_color, 0, 1).substr($font_color, 1, 1).substr($font_color, 1, 1).substr($font_color, 2, 1).substr($font_color, 2, 1);
		$font_r = intval(substr($font_color, 0, 2), 16);
		$font_g = intval(substr($font_color, 2, 2), 16);
		$font_b = intval(substr($font_color, 4, 2), 16);
		$col_bg = imagecolorallocate($im, $bg_r, $bg_g, $bg_b);
		$col_font = imagecolorallocate($im, $font_r, $font_g, $font_b);
		imagefill($im, 1, 1, $col_bg);
		for($i=0; $i<strlen($str); $i++)
		{
			imagettftext($im, 15, 0, ($i*10+0), 16, $col_font, $font, substr($str, $i, 1));
		}
		ob_start();
			imagepng($im, null, 9, PNG_ALL_FILTERS);
			$im_data = ob_get_contents();
		ob_end_clean();
		$im_data_b64 = base64_encode($im_data);
		$out = '<img src="data:image/png;base64,'.$im_data_b64.'"/><br/>'."\n";
		if($css_img_path == 'b64')
		{
			$css_img_path = 'data:image/png;base64,'.$im_data_b64;
		}
	}
	$str_style = '.'.$css_class.'_wrap { display:inline-block; border:1px solid #333; border-radius:5px; padding:2px; background-color:#EEE; }'."\n";
	$str_style .= '.'.$css_class.' { width:10px; height:20px; display:inline-block; background-repeat:no-repeat; background-image:url(\''.$css_img_path.'\'); }'."\n";
	$i = 0;
	foreach($str_arr as $k => $v)
	{
		$str_style .= '.'.$css_class.'.'.$v.' { background-position:'.($i*10*(-1)).'px 0px; }'."\n";
		$i++;
	}
	$out .= 'Stylesheet:'."\n<pre>".$str_style."</pre><br/>\n";
	$out .= 'Array for PHP-Function:'."\n".'<pre>array(';
	foreach($str_arr as $k => $v)
	{
		$out .= "'$k'=>'$v',";
	}
	$out .= ');</pre><br/>'."\n";
	return $out;
}
?>
