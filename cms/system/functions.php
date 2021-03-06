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
						if(is_array($filter) && $mod_k == $filter[0])
						{
							$v['value']['_new_1'][$mod_k] = $filter[1];
						}
					}
				}
				#echo '<pre>'.print_r($indizes, true).'</pre>'."<br/>\n";
				#echo '<pre>'.print_r($filter, true).'</pre>'."<br/>\n";
				#echo '<pre>'.print_r($v, true).'</pre>'."<br/>\n";
				#die();
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
				$out .= gen_config_field($v, array('file'=>$file,'val'=>$k));
			}
		}
	}
	$out .= '<div class="form_submit"><input type="submit" accesskey="s" title="'.lecho('button_submit_title', $admin_lang).'" value="'.lecho('button_submit', $admin_lang).'"/></div>'."\n".'</form>'."\n";
	return $out;
}
function gen_config_field($v, $k)
{
	global $admin_lang, $glob_var;
	//var_dump('$v');
	//var_dump($v);
	//var_dump('$k');
	//var_dump($k);
	$fid = "${k['file']}:${k['val']}";
	$label = 'config_'.$k['val'];
	if(isset($k['index']) && isset($k['key']))
	{
		$fid .= ":${k['index']}:${k['key']}";
		$label .= '_'.$k['key'];
		#var_dump($v);
	}
	$fa = array(
		'type' => $v['type'],
		'value' => $v['value'],
		'help' => lecho('help_'.$label, $admin_lang),
		'label' => lecho($label, $admin_lang),
		'name' => $fid,
		'set' => array(
			'must' => $v['must'],
			'match' => $v['match'],
		),
	);
	if($v['type'] === 'select_one')
	{
		if(isset($v['options']['from_config']))
		{
			$conf_idx = explode(':', $v['options']['from_config']);
			$conf_opts = get_config_data($conf_idx[0], $conf_idx[1]);
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
				$v['options']['_none_'] = '---';
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
		elseif(isset($v['options']['from_file']))
		{
			$files = glob($v['options']['from_file']);
			$filter = ($v['options']['filter'])? $v['options']['filter'] : false;
			$v['options'] = array();
			if(isset($v['allow_none']))
			{
				$v['options']['_none_'] = '---';
			}
			foreach($files as $fk => $fv)
			{
				$bn = basename($fv);
				if(false === $filter || preg_match('/'.$filter.'/', $bn))
				{
					$v['options'][] = $bn;
				}
			}
		}
		$fa['options'] = $v['options'];
	}
	elseif($v['type'] === 'select_more')
	{
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
		elseif(isset($v['options']['from_file']))
		{
			$files = glob($v['options']['from_file']);
			$filter = ($v['options']['filter'])? $v['options']['filter'] : false;
			$v['options'] = array();
			foreach($files as $fk => $fv)
			{
				$bn = basename($fv);
				if(false === $filter || preg_match('/'.$filter.'/', $bn))
				{
					$v['options'][] = $bn;
				}
			}
		}
		$fa['options'] = $v['options'];
	}
	//var_dump($fa);
	return gen_form_field($fa);
	/*
	$out = '<div class="form_row '.$v['type'].'"><div class="label">'."\n".'<label for="'.$fid.'">'.lecho($label, $admin_lang).'</label></div>'."\n".'<div class="input">';
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
					$v['options']['_none_'] = '---';
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
			elseif(isset($v['options']['from_file']))
			{
				$files = glob($v['options']['from_file']);
				$filter = ($v['options']['filter'])? $v['options']['filter'] : false;
				$v['options'] = array();
				foreach($files as $fk => $fv)
				{
					$bn = basename($fv);
					if(false === $filter || preg_match('/'.$filter.'/', $bn))
					{
						$v['options'][] = $bn;
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
			elseif(isset($v['options']['from_file']))
			{
				$files = glob($v['options']['from_file']);
				$filter = ($v['options']['filter'])? $v['options']['filter'] : false;
				$v['options'] = array();
				foreach($files as $fk => $fv)
				{
					$bn = basename($fv);
					if(false === $filter || preg_match('/'.$filter.'/', $bn))
					{
						$v['options'][] = $bn;
					}
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
				$label_val = lecho($label.'_'.$ov, $admin_lang);
				if('[[' == substr($label_val, 0, 2))
					$label_val = $ov;
				$out .= '<div class="more_row"><input type="checkbox" name="'.$fid.'.'.$ok.'" id="'.$fid.'.'.$ok.'"'.$sel.' value="'.$ok.'"/><label class="each" for="'.$fid.'.'.$ok.'">'.htmlspecialchars($label_val).'</label></div>'."\n";
			}
			$out .= '<input type="text" class="hidden select_more_value'.$class.'" name="'.$fid.'" id="'.$fid.'" value="'.substr($val, 1).'" />'."\n";
			break;
		case 'include_code':
			$out .= '<input type="text" readonly="readonly" class="include_code" name="'.$fid.'" id="'.$fid.'" value="" />'."\n";
			break;
		case 'password':
			$out .= '<input type="password" class="'.$class.'" name="'.$fid.'" id="'.$fid.'" value="'.htmlspecialchars($v['value']).'" />'."\n";
			break;
		case 'email':
			$out .= '<input type="text" class="email'.$class.'" name="'.$fid.'" id="'.$fid.'" value="'.htmlspecialchars($v['value']).'" />'."\n";
			break;
		case 'htmlarea':
			$out .= '<textarea rows="10" cols="10" class="tinymce'.$class.'" name="'.$fid.'" id="'.$fid.'" >'.htmlspecialchars($v['value']).'</textarea>'."\n";
			break;
		case 'textarea':
			$out .= '<textarea rows="10" cols="10" class="'.$class.'" name="'.$fid.'" id="'.$fid.'" >'.$v['value'].'</textarea>'."\n";
			break;
		case 'text':
		default:
			$out .= '<input type="text" class="'.$class.'" '.$match.' name="'.$fid.'" id="'.$fid.'" value="'.htmlspecialchars($v['value']).'" />'."\n";
	}
	$out .= '</div>'."\n";
	$out .= '<span class="ui-icon ui-icon-info" title="'.lecho('help_'.$label, $admin_lang).'">&nbsp;</span><span class="ui-icon ui-icon-arrowreturnthick-1-s" title="'.lecho('help_field_reset', $admin_lang).'">&nbsp;</span>'."\n".'</div>'."\n";
	return $out;
	*/
}
function gen_form_field($a=array(), $b=array(), $c=array())
{
	$out = '';
	$a = gff_init($a);
	$b = (0 !== count($b))? gff_init($b) : $b;
	$c = (0 !== count($c))? gff_init($c) : $c;
	//var_dump( $a );
	//var_dump( $b );
	//var_dump( $c );
	$out .= '<div class="form_row '.$a['type'].'">';
	$out .= '<div class="label">';
	$out .= '<label for="'.htmlspecialchars($a['name']).'">'.htmlspecialchars($a['label']).'</label>';
	$out .= '</div>';
	$out .= '<div class="input">';
	$out .= gff_input($a);
	$out .= '</div>';
	$out .= ('' === $a['help'])? '' : '<span class="ui-icon ui-icon-info" title="'.htmlspecialchars($a['help']).'">&nbsp;</span>';
	$out .= '<span class="ui-icon ui-icon-arrowreturnthick-1-s" title="reset">&nbsp;</span>';
	$out .= '</div>'."\n";
	return $out;
}
function gff_init($a)
{
	$a['label'] = ($a['label'])? trim($a['label']) : 'Eingabe';
	$a['type'] = ($a['type'])? $a['type'] : 'text';
	$a['name'] = ($a['name'])? trim($a['name']) : preg_replace('/[^a-zA-Z0-9_]+/', '_', $a['label']);
	$a['value'] = ($a['value'])? $a['value'] : '';
	$a['help'] = ($a['help'])? trim($a['help']) : '';
	$a['set'] = (is_array($a['set']))? $a['set'] : array();
	return $a;
}
function gff_input($a)
{
	$class = '';
	$class .= ($a['set']['must'])? ' must' : '';
	$class .= ($a['set']['match'])? ' match match_'.rawurlencode($a['set']['match']) : '';
	$class .= ($a['set']['notrim'])? ' notrim' : '';
	$name = htmlspecialchars($a['name']);
	$out = '';
	switch($a['type'])
	{
		case 'select_one':
			//var_dump($a);
			$out .= '<select name="'.$name.'" id="'.$name.'">';
			if(is_array($a['options']))
			{
				foreach($a['options'] as $k => $v)
				{
					$evenodd = ($evenodd == 'even')? 'odd' : 'even';
					$sel = '';
					if(is_numeric($k) && $v == $a['value'])
						$sel = ' selected="selected"';
					elseif(!is_numeric($k) && $k == $a['value'])
						$sel = ' selected="selected"';
					$out .= '<option class="'.$evenodd.'" value="'.$k.'"'.$sel.'>'.htmlspecialchars($v).'</option>'."\n";
				}
			}
			$out .= '</select>'."\n";
			break;
		case 'select_more':
			$val = '';
			if(is_array($a['options']))
			{
				foreach($a['options'] as $k => $v)
				{
					$sel = '';
					if(is_array($a['value']) && is_numeric($k) && in_array($v, $a['value']))
						$sel = ' checked="checked"';
					elseif(is_array($a['value']) && !is_numeric($k) && in_array($k, $a['value']))
						$sel = ' checked="checked"';
					$val .= ($sel)? ':'.$k : '';
					$label_val = lecho($label.'_'.$v, $admin_lang);
					if('[[' == substr($label_val, 0, 2))
						$label_val = $v;
					$out .= '<div class="more_row"><input type="checkbox" name="'.$name.'.'.$k.'" id="'.$name.'.'.$k.'"'.$sel.' value="'.$k.'"/><label class="each" for="'.$name.'.'.$k.'">'.htmlspecialchars($label_val).'</label></div>'."\n";
				}
			}
			$out .= '<input type="text" class="hidden select_more_value'.$class.'" name="'.$name.'" id="'.$name.'" value="'.substr($val, 1).'" />'."\n";
			break;
		case 'include_code':
			$out .= '<input type="text" readonly="readonly" class="include_code" name="'.$name.'" id="'.$name.'" value="" />'."\n";
			break;
		case 'password':
			$out .= '<input type="password" class="'.$class.'" name="'.$name.'" id="'.$name.'" value="'.htmlspecialchars($a['value']).'" />'."\n";
			break;
		case 'email':
			$out .= '<input type="text" class="email'.$class.'" name="'.$name.'" id="'.$name.'" value="'.htmlspecialchars($a['value']).'" />'."\n";
			break;
		case 'htmlarea':
			$out .= '<textarea rows="10" cols="10" class="tinymce'.$class.'" name="'.$fid.'" id="'.$fid.'" >'.htmlspecialchars($a['value']).'</textarea>'."\n";
			break;
		case 'textarea':
			$out .= '<textarea rows="10" cols="10" class="'.$class.'" name="'.$name.'" id="'.$name.'" >'.$a['value'].'</textarea>'."\n";
			break;
		case 'text':
		default:
			$out .= '<input type="text" class="'.$class.'" name="'.$name.'" id="'.$name.'" value="'.htmlspecialchars($a['value']).'" />'."\n";
	}
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
			if(count($v['sort_order']) < count($conf_chan[$v['var']]['value']))
			{
				#echo "sort_order: ".count($v['sort_order'])." : conf: ".count($conf_chan[$v['var']]['value'])."\n";
				$conf_keys = array_keys($conf_chan[$v['var']]['value']);
				foreach($conf_keys as $ck => $cv)
				{
					if(!in_array($cv, $v['sort_order']))
					{
						$v['sort_order'][] = $cv;
					}
				}
			}
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
				if(!isset($opts['from_config']) && !isset($opts['from_file']))
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
				if(1 === count($tmp) && '' === $tmp[0])
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
			$conf_val['options']['_none_'] = 0;
		}
		foreach($conf_opts as $ok => $ov)
		{
			#$conf_val['options'][] = $ov[$conf_idx[2]];
			$conf_val['options'][] = $ok;
		}
	}
	if( ('select_more' === $conf_val['type'] || 'select_one' === $conf_val['type']) && isset($conf_val['options']['from_file']))
	{
		#return $conf_val;
		$files = glob($conf_val['options']['from_file']);
		$filter = ($conf_val['options']['filter'])? $conf_val['options']['filter'] : false;
		$conf_val['options'] = array();
		if(isset($conf_val['allow_none']))
		{
			#$conf_val['options'][] = '---';
			$conf_val['options']['_none_'] = 0;
		}
		$conf_val['options'] = array();
		#return $files;
		foreach($files as $fk => $fv)
		{
			$bn = basename($fv);
			if(false === $filter || preg_match('/'.$filter.'/', $bn))
			{
				$conf_val['options'][] = $bn;
			}
		}
		if(isset($conf_val['options'][$new_val]))
		{
			$new_val = $conf_val['options'][$new_val];
		}
		#return var_export($new_val,true) . ' : '.$conf_val['options'][0];
	}
	#return var_export($new_val,true) . ' : '.$conf_val['options'][0];
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
	elseif($conf_val['type'] === 'textarea')
	{
		return true;
	}
	elseif($conf_val['type'] === 'htmlarea')
	{
		return true;
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
	elseif($conf_val['type'] === 'include_code')
	{
		return true;
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
	file_put_contents($file_name, '<?php # '.date('r')."\n\n\$$file = ".preg_replace('/^( +)/me', "str_repeat(\"\t\", (strlen('$1')/2))", var_export($value, true)).";\n\n?>", LOCK_EX);
	@chmod($file_name, 0666);
	$$file = $value;
	$clear_cache_on_change = array('cms','pages','users','textblock','res_cal');
	if(in_array($file, $clear_cache_on_change))
	{
		clear_cache();
	}
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
function parse_page_content($t, $lang='de')
{
	#$out = $t;
	#require_once('cms/system/simple_html_dom.php');
	while(0 !== preg_match_all('#\[\[:([a-zA-Z0-9_]+):([a-zA-Z0-9_]+):\]\]#', $t, $match))
	{
		foreach($match[1] as $k => $v)
		{
			$replace = '';
			if('textblock' == $v)
			{
				#$replace = get_config_data('textblock', 'textblock', '[name='.$match[2][$k].']', 'text');
				$replace = get_textblock($match[2][$k], $lang);
			}
			elseif('res_cal' == $v)
			{
				$replace = reservation_calendar('[name='.$match[2][$k].']', $lang);
			}
			elseif('anderes_plugin' == $v)
			{
				$replace = 'anderes Plugin';
			}
			#$indicator = '<br id="i'.md5(microtime(true)).'" />';
			#$t = str_replace($match[0][$k], $indicator, $t);
			$t = str_replace($match[0][$k], $replace, $t);
		}
	}
	return $t;
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
	$user_data = get_config_data('users', 'users', '[user_name='.$_POST['user'].']');
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
	if(time() - strtotime($sess_data['time']) > 60)
	{
		merge_config('sessions', array(array('var'=>'sessions', 'index'=>$sess_key, 'key'=>'time', 'value'=>date('r'))));
	}
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
function get_textblock($tb, $lang='de')
{
	global $textblock;
	require_once('cms/config/textblock.php');
	#echo '<pre>'.print_r($textblock,true).'</pre>';
	#die();
	if(preg_match('/^(.*?)_[a-z]{2}$/', $tb, $match))
	{
		$match_idx = $textblock['textblock']['index']['name'][$match[1].'_'.$lang];
		if($match_idx)
		{
			return($textblock['textblock']['value'][$match_idx]['text']);
		}
		#var_dump($match);
		#die();
	}
	$match_idx = $textblock['textblock']['index']['name'][$tb];
	if($match_idx)
	{
		return($textblock['textblock']['value'][$match_idx]['text']);
	}
	return '';
}
function get_config_data($file, $var, $index = false, $key = false)
{
	global $$file;
	require_once('cms/config/'.$file.'.php');
	$file = $$file;
	if(false !== $index && '[' === substr($index, 0, 1) && ']' === substr($index, -1))
	{
		#$idx = $conf_chan[$v['var']]['index'];
		$idx = substr($index, 1, -1);
		$idx = explode('=', $idx, 2);
		$idx = $file[$var]['index'][$idx[0]][$idx[1]];
		$index = $idx;
	}
	if(false !== $index && false !== $key)
	{
		return $file[$var]['value'][$index][$key];
	}
	elseif(false === $key && false !== $index)
	{
		$out = $file[$var]['value'][$index];
		if(null === $out)
			return $out;
		$out['conf_idx'] = $index;
		return $out;
	}
	else 
	{
		return $file[$var]['value'];
	}
}
function sitemap()
{
	global $pages, $cms;
	require_once('cms/config/pages.php');
	require_once('cms/config/cms.php');
	$dirname = ('/' == dirname($_SERVER['PHP_SELF']))? '' : dirname($_SERVER['PHP_SELF']);
	$host_path = 'http://'.$_SERVER['HTTP_HOST'].$dirname.'/?';
	$out = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	$out .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
	foreach($pages['pages']['value'] as$k => $v)
	{
		if('public' == $v['access'] && 'On' == $cms['avail_page_lang']['value'][$v['lang']]['visible'])
		{
			$out .= '<url>'."\n";
			$out .= '<loc>'.$host_path.htmlspecialchars($v['name']).'</loc>'."\n";
			$out .= '</url>'."\n";
		}
	}
	$out .= '</urlset>'."\n";
	return $out;
}
function pages_menu($lang='de', $active='', $sitemap=false)
{
	global $pages, $cms;
	require_once('cms/config/pages.php');
	require_once('cms/config/cms.php');
	$out = '';
	$lk = $cms['avail_page_lang']['index']['lang'][$lang];
	if(!$lk || 'On' !== $cms['avail_page_lang']['value'][$lk]['visible'])
		return null;
	$out .= '<ul class="l0">';
	foreach($pages['pages']['value'] as $k => $v)
	{
		if($v['lang'] == $lk && $v['access'] == 'public' && $v['is_sub_of'] == '_none_')
		{
			$entry = pages_menu_entry($k, $active, 0, $sitemap);
			$out .= $entry['entry'];
		}
	}
	$out .= '</ul>';
	return $out;
}
function pages_menu_entry($page, $active='', $level=0, $sitemap=false)
{
	global $pages;
	require_once('cms/config/pages.php');
	$level++;
	$out = '';
	$has_active = false;
	if(isset($pages['pages']['value'][$page]))
	{
		$v = $pages['pages']['value'][$page];
		$class = array();
		if($active == $v['name'])
		{
			$class[] = 'active';
			$has_active = true;
		}
		$title = ($v['title'] && false === $sitemap)? ' title="'.htmlspecialchars($v['title']).'"' : '';
		$tmp_out = '';
		if(isset($pages['pages']['list']['is_sub_of'][$page]))
		{
			$class[] = 'has_sub';
			$tmp_out .= "<ul class=\"l${level}\">";
			foreach($pages['pages']['list']['is_sub_of'][$page] as $sk => $sv)
			{
				$entry = pages_menu_entry($sv, $active, $level, $sitemap);
				if(true === $entry['has_active'])
				{
					$has_active = true;
				}
				$tmp_out .= $entry['entry'];
			}
			$tmp_out .= '</ul>'."\n";
		}
		if(true === $has_active && $active !== $v['name'])
			$class[] = 'has_active';
		$class = (0 < count($class) && false === $sitemap)? ' class="'.implode(' ', $class).'"' : '';
		$out .= '<li'.$class.'><a href="?'.$v['name'].'"'.$title.'>'.(($v['name_show'])? htmlspecialchars($v['name_show']) : $v['name']) .'</a>';
		$out .= $tmp_out;
		$out .= "</li>\n";
		return array('has_active'=>$has_active, 'entry'=>$out);
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
		if($v['is_sub_of'] == '_none_')
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
function formatBytes($bytes, $precision = 0) {
	$units = array('&nbsp;B', 'KB', 'MB', 'GB', 'TB');
	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);
	$bytes /= pow(1024, $pow);
	return number_format(round($bytes, $precision), $precision, ',', '.') . ' ' . $units[$pow];
}
function formatTime($time, $lang = 'de', $precision = 0, $unit_len='short', $unit_pref='') {
	$unit = '';
	if($unit_pref == 's' || abs($time) < 60)
	{
		$unit = 's';
	}
	elseif($unit_pref == 'm' || abs($time) < (60 * 60))
	{
		$time /= 60;
		$unit = 'm';
	}
	elseif($unit_pref == 'h' || abs($time) < (60 * 60 * 24))
	{
		$time /= 60*60;
		$unit = 'h';
	}
	elseif($unit_pref == 'd' || abs($time) < (60 * 60 * 24 * 7))
	{
		$time /= 60*60*24;
		$unit = 'd';
	}
	elseif($unit_pref == 'w' || abs($time) < (60 * 60 * 24 * 7 * 4))
	{
		$time /= 60*60*24*7;
		$unit = 'w';
	}
	elseif($unit_pref == 'M' || abs($time) < (60 * 60 * 24 * 30.42 * 12))
	{
		$time /= 60*60*24*30.42;
		$unit = 'M';
	}
	else
	{
		$time /= 60*60*24*365;
		$unit = 'Y';
	}
	$time = round($time, $precision);
	$unit_1 = array('s' => 'second',  'm' => 'minute',  'h' => 'hour',  'd' => 'day',  'w' => 'week',  'M' => 'month',  'Y' => 'year');
	$unit_2 = array('s' => 'seconds', 'm' => 'minutes', 'h' => 'hours', 'd' => 'days', 'w' => 'weeks', 'M' => 'months', 'Y' => 'years');
	if($unit_len == 'long')
	{
		if($time <= 1)
			$unit = $unit_1[$unit];
		else 
			$unit = $unit_2[$unit];
		$unit = lecho('time_unit_'.$unit, $lang);
	}
	return $time . ' ' . $unit;
}
function get_thumb($img, $opt=array())
{
	$def_opt = array(
		'w' => 300,
		'h' => 200,
		'iar' => 1,
		'f' => 'self',
	);
	if(1 == $opt['iar'])
		$def_opt['zc'] = 1;
	$opt = array_merge($def_opt, $opt);
	if(!is_file($img))
		return false;
	$img_size = getimagesize($img);
	if(null === $img_size)
		return false;
	$img_bn = basename($img);
	$img_ext = strtolower( substr($img_bn, strrpos($img_bn, '.')+1) );
	if('jpg' == $img_ext)
		$img_ext = 'jpeg';
	$opt['f'] = ('self' == $opt['f'])? $img_ext : $opt['f'];
	$opt['w'] = (0 === $opt['w'])? round($img_size[0] / ($img_size[1] / intval($opt['h']))) : $opt['w'];
	$opt['h'] = (0 === $opt['h'])? round($img_size[1] / ($img_size[0] / intval($opt['w']))) : $opt['h'];
	$out_w = $img_size[0];
	$out_h = $img_size[1];
	if(1 == $opt['zc'])
	{
		unset($opt['iar']);
	}
	else 
	{
		if($img_size[0] > $opt['w'])
		{
			$out_w = $opt['w'];
			$out_h = round($img_size[1] / ($img_size[0] / intval($opt['w'])));
		}
		if($img_size[1] > $opt['h'] && $out_h > $opt['h'])
		{
			$out_w = round($img_size[0] / ($img_size[1] / intval($opt['h'])));
			$out_h = $opt['h'];
		}
		$opt['w'] = $out_w;
		$opt['h'] = $out_h;
	}
	$img_title = '';
	if($opt['title'])
	{
		$img_title = ' title="'.htmlspecialchars($opt['title']).'"';
		unset($opt['title']);
	}
	$img_opt = array();
	ksort($opt);
	foreach($opt as $k => $v)
		$img_opt[] = "{$k}={$v}";
	#$img_src = phpThumbURL('src=../../../'. rawurlencode($img).'&'.implode('&', $img_opt));
	$img_src = phpThumbURL('src='.str_replace('../cms/', '', '../../../'.dirname($img)).'/'.rawurlencode(basename($img)).'&'.implode('&', $img_opt));
	return array(
		'src'=>$img_src,
		'w'=>$opt['w'],
		'h'=>$opt['h'],
		'wh'=>'width="'.$opt['w'].'" height="'.$opt['h'].'"',
		'img'=>'<img src="'.htmlspecialchars($img_src).'" alt=""'.$img_title.' width="'.$opt['w'].'" height="'.$opt['h'].'" />'
	);
	#phpThumbURL('src=../../../'. rawurlencode($v).'&w=300&h=300&f='.$bnext)
}
function get_dir_content($dir, $lang = 'de')
{
	global $array_sort_keys_list;
	if(!is_dir($dir))
	{
		return '';
	}
	if('/' !== substr($dir, -1))
		$dir .= '/';
	$file_categories = array(
		'jpg'	=> 'image',
		'jpeg'	=> 'image',
		'gif'	=> 'image',
		'png'	=> 'image',
		'mp3'	=> 'media',
		'ogg'	=> 'media',
		'wav'	=> 'media',
		'mp4'	=> 'media',
		'mpg'	=> 'media',
		'mpeg'	=> 'media',
		'avi'	=> 'media',
		'pdf'	=> 'document',
		'doc'	=> 'document',
		'docx'	=> 'document',
		'odt'	=> 'document',
		'xls'	=> 'document',
		'xlsx'	=> 'document',
		'ods'	=> 'document',
		'*'	=> 'other',
		'order' => array('image','media','document','other'),
	);
	$files_in_cat = array();
	$files = glob($dir.'*');
	$out = '';
	if(is_array($files) && 0 < count($files))
	{
		foreach($files as $k => $v)
		{
			if(is_file($v))
			{
				$pinfo = pathinfo($v);
				$l_ext = strtolower($pinfo['extension']);
				$pinfo['extension'] = ('jpg' === $l_ext)? 'jpeg' : $l_ext;
				$tmp_cat = (isset($file_categories[$pinfo['extension']]))? $file_categories[$pinfo['extension']] : $file_categories['*'];
				$files_in_cat[$tmp_cat][] = $pinfo;
			}
		}
	}
	$array_sort_keys_list = $file_categories['order'];
	uksort($files_in_cat, 'array_sort_keys');
	#var_dump($files_in_cat);
	#die();
	foreach($files_in_cat as $cat => $a)
	{
		$out .= '<span class="dircontent_category">'.lecho('dircontent_category_'.$cat, $lang).'</span>'."\n";
		foreach($a as $k => $v)
		{
			$fp = $v['dirname'].'/'.$v['basename'];
			$fs = filesize($fp);
			$fmt = filemtime($fp);
			$prev_title = '';
			#$mime_img = (is_file('cms/include_files/images/mime/type_'.$v['extension'].'.png'))? 'cms/include_files/images/mime/type_'.$v['extension'].'.png' : 'cms/include_files/images/mime/type_misc.png';
			$mime_img = '<span class="mime_img mime_img_'.$v['extension'].'">&nbsp;</span>';
			if('jpeg' === $v['extension'] || 'gif' === $v['extension'] || 'png' === $v['extension'])
			{
				$img_size = getimagesize($fp);
				#$img_thumb = get_thumb($v, array('w'=>200,'h'=>200,'iar'=>1));
				$img_thumb = get_thumb($fp);
				#var_dump(get_thumb($v));
				#var_dump($img_size);
				#$prev_title = ' title="&lt;img src=&quot;'.htmlspecialchars( phpThumbURL('src=../../../'. rawurlencode($v).'&w=300&h=300&f='.$bnext) ).'&quot; onload=&quot;clog(this);$(this).removeAttr(&apos;height&apos;); &quot; width=&quot;300&quot; height=&quot;300&quot;/&gt;"';
				$prev_title = ' title="'.htmlspecialchars( $img_thumb['img'] . '<br/>'.$img_size[0].' x '.$img_size[1] ).'"';
				#$prev_title = ' title="'.rawurlencode($v).'"';
				#$mime_img = get_thumb($fp, array('w'=>33,'h'=>22,'iar'=>1,'fltr[]'=>'ric|4|4','f'=>'png'));
				$mime_img = get_thumb($fp, array('w'=>33,'h'=>22,'iar'=>1));
				$mime_img = '<span class="tiny_img" style="background-image:url('.htmlspecialchars($mime_img['src']).');">&nbsp;</span>';
			}
			#$out .= (0 === $k)? '<strong>'.$dir.'</strong><br/>'."\n" : '';
			#$out .= '<span title="'.$k.'" onmouseover="titleToTip();">'. basename($v) . '</span><br/>'."\n";
			#$out .= '<span title="'.$k.'" >'. basename($v) . '</span><br/>'."\n";
			$out .= '<div class="dircontent_row">'
				.'<span class="dircontent_name"'.$prev_title.'><span class="mime">'.$mime_img.'</span> <span class="filename">'. htmlspecialchars($v['basename']) . '</span></span>'
				.'<span class="dircontent_size" title="'.number_format($fs, 0, ',', '.').' Byte">'.formatBytes($fs).'</span>'
				.'<span class="dircontent_time" title="'.lecho('dircontent_modified', $lang) .'&lt;br/&gt;'. formatTime(time()-$fmt, $lang, 0, 'long') .'&lt;br/&gt;' . date('d.m.y h:i:s', $fmt).'">' . formatTime(time()-$fmt, $lang) . '</span>'
				.'<span class="dircontent_manage">'
					.'<input class="select_file" title="'.htmlspecialchars( lecho('fileupload_select', $lang) ).'" type="checkbox" />'
					.'<span class="delete_file ui-icon ui-icon-trash" title="'.lecho('fileupload_delete', $lang).'">delete</span>'
					.'<span class="rename_file ui-icon ui-icon-pencil" title="'.lecho('fileupload_rename', $lang).'">rename</span>'
					.'<a class="download_file ui-icon ui-icon-arrowreturnthick-1-s" target="_blank" href="'.htmlspecialchars($v['dirname'].'/'.$v['basename']).'" title="'.lecho('fileupload_download', $lang).'">download</a>'
					.'</span>'
				.'</div>'."\n";
		}
	}
	return $out;
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
	if(false === $admin && 'css' === $type)
	{
		if(is_file('cms/template/'.$cms['template']['value'].'/style.css'))
			$files[] = 'cms/template/'.$cms['template']['value'].'/style.css';
		if(is_file('cms/template/'.$cms['template']['value'].'/style.min.css'))
			$files[] = 'cms/template/'.$cms['template']['value'].'/style.min.css';
	}
	if(false === $admin && 'js' === $type)
	{
		if(is_file('cms/template/'.$cms['template']['value'].'/javascript.js'))
			$files[] = 'cms/template/'.$cms['template']['value'].'/javascript.js';
		if(is_file('cms/template/'.$cms['template']['value'].'/javascript.min.js'))
			$files[] = 'cms/template/'.$cms['template']['value'].'/javascript.min.js';
	}
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
function clear_cache($what='pages')
{
	$status = null;
	$txt = 'wrong type of cache to delete ('.$what.')';
	$files = glob('cms/cache/*');
	$thumb_files = glob('cms/cache/thumb/*');
	$count = array();
	$i = 0;
	if('thumb' === substr($what, -5) || 'count' === $what)
	{
		if(is_array($thumb_files) && 0 < count($thumb_files))
		{
			foreach($thumb_files as $k => $v)
			{
				if(is_file($v))
				{
					if('count' !== $what)
					{
						unlink($v);
					}
					$i++;
				}
			}
		}
		$status = true;
		$txt = $i. ' files removed.';
		$count['thumb'] = $i;
		$i = 0;
	}
	if('pages' === substr($what, -5) || 'count' === $what)
	{
		if(is_array($files) && 0 < count($files))
		{
			foreach($files as $k => $v)
			{
				$bn = basename($v);
				if('.php' === substr($bn, -4)
					&& false === strpos($bn, 'javascript_js') 
					&& false === strpos($bn, 'javascript_admin_js') 
					&& false === strpos($bn, 'style_css') 
					&& false === strpos($bn, 'style_admin_css') )
				{
					if('count' !== $what)
					{
						unlink($v);
					}
					$i++;
				}
			}
		}
		$status = true;
		$txt = $i. ' files removed.';
		$count['pages'] = $i;
		$i = 0;
	}
	if('css' === substr($what, -3) || 'count' === $what)
	{
		if(is_array($files) && 0 < count($files))
		{
			foreach($files as $k => $v)
			{
				$bn = basename($v);
				if(0 === strpos($bn, 'style_css') 
					|| 0 === strpos($bn, 'style_admin_css') )
				{
					if('count' !== $what)
					{
						unlink($v);
					}
					$i++;
				}
			}
		}
		$status = true;
		$txt = $i. ' files removed.';
		$count['css'] = $i;
		$i = 0;
	}
	if('js' === substr($what, -2) || 'count' === $what)
	{
		if(is_array($files) && 0 < count($files))
		{
			foreach($files as $k => $v)
			{
				$bn = basename($v);
				if(0 === strpos($bn, 'javascript_js') 
					|| 0 === strpos($bn, 'javascript_admin_js')
					|| 1 === preg_match('/^[a-z0-9]+\.gz/', $bn) )
				{
					if('count' !== $what)
					{
						unlink($v);
					}
					$i++;
				}
			}
		}
		$status = true;
		$txt = $i. ' files removed.';
		$count['js'] = $i;
		$i = 0;
	}
	if('all' === substr($what, -3) || 'count' === $what)
	{
		if(is_array($thumb_files) && 0 < count($thumb_files))
			$files = array_merge($files, $thumb_files);
		if(is_array($files) && 0 < count($files))
		{
			foreach($files as $k => $v)
			{
				#$bn = basename($v);
				if(true === is_file($v))
				{
					if('count' !== $what)
					{
						unlink($v);
					}
					$i++;
				}
			}
		}
		$status = true;
		$txt = $i. ' files removed.';
		$count['all'] = $i;
		$i = 0;
	}
	return array('status'=>$status, 'txt'=>$txt, 'count'=>$count);
}
function rgb2dec($c)
{
	$c = str_replace('#', '', $c);
	$c = (strlen($c) !== 6)? substr($c, 0, 1).substr($c, 0, 1) . substr($c, 1, 1).substr($c, 1, 1) . substr($c, 2, 1).substr($c, 2, 1) : $c;
	return array(
		base_convert(substr($c, 0, 2), 16, 10),
		base_convert(substr($c, 2, 2), 16, 10),
		base_convert(substr($c, 4, 2), 16, 10),
	);
}
function ri_text(Array $o) #<-- img, font, shadeC, textC, 
{
	$o['x'] = ($o['x'])? $o['x'] : 1;
	$o['y'] = ($o['y'])? $o['y'] : 1;
	$o['shadeX'] = ($o['shadeX'])? $o['shadeX'] : 1;
	$o['shadeY'] = ($o['shadeY'])? $o['shadeY'] : 1;
	$o['fontS'] = ($o['fontS'])? $o['fontS'] : 10;
	$o['shade'] = ($o['shade'])? $o['shade'] : true;
	$o['align'] = ($o['align'])? $o['align'] : 'left';
	$o['text'] = ($o['text'])? $o['text'] : 'text';
	if('left' !== $o['align'])
	{
		$box = imagettfbbox($o['fontS'], 0, $o['font'], $o['text']);
		if('right' === $o['align'])
			$o['x'] -= $box[2]-$box[0];
		elseif('center' === $o['align'])
			$o['x'] -= floor(($box[2]-$box[0])/2);
	}
	if(true === $o['shade'])
	{
		imagettftext($o['img'], $o['fontS'], 0, $o['x']+$o['shadeX'], $o['y']+$o['shadeY']+$o['fontS'], $o['shadeC'], $o['font'], $o['text']);
	}
	imagettftext($o['img'], $o['fontS'], 0, $o['x'], $o['y']+$o['fontS'], $o['fontC'], $o['font'], $o['text']);
}
function ri_box(Array $o) #<-- img, c
{
	$o['x'] = ($o['x'])? $o['x'] : 1;
	$o['y'] = ($o['y'])? $o['y'] : 1;
	$o['width'] = ($o['width'])? $o['width'] : 100;
	$o['height'] = ($o['height'])? $o['height'] : 20;
	imagerectangle($o['img'], $o['x'], $o['y'], $o['x']+$o['width'], $o['y']+$o['height'], $o['c']);
}
function reservation_image($opt = array())
{
	$opt['monthShort'] = ($opt['monthShort'])? $opt['monthShort'] : true;
	$opt['widthAllPadding'] = ($opt['widthPadding'])? $opt['widthPadding'] : 10;
	$opt['heightAllPadding'] = ($opt['heightPadding'])? $opt['heightPadding'] : 10;
	$opt['heightDayMargin'] = ($opt['heightDayMargin'])? $opt['heightDayMargin'] : 1;
	$opt['widthDayMargin'] = ($opt['widthDayMargin'])? $opt['widthDayMargin'] : 1;
	$opt['widthMonth'] = ($opt['widthMonth'])? $opt['widthMonth'] : (($opt['monthShort'])? 30 : 100);
	$opt['widthWeek'] = ($opt['widthWeek'])? $opt['widthWeek'] : 20;
	$opt['widthDay'] = ($opt['widthDay'])? $opt['widthDay'] : 20;
	$opt['heightDay'] = ($opt['heightDay'])? $opt['heightDay'] : 20;
	$opt['heightHead'] = ($opt['heightHead'])? $opt['heightHead'] : 20;
	$opt['calType'] = ($opt['calType'])? $opt['calType'] : 1;
	$opt['calWithHead'] = ($opt['calWithHead'])? $opt['calWithHead'] : true;
	$opt['textShade'] = ($opt['textShade'])? $opt['textShade'] : true;
	$opt['bgcol'] = ($opt['bgcol'])? $opt['bgcol'] : '#eee';
	$opt['mType'] = ($opt['mType'])? $opt['mType'] : 'png'; // png || gif || jpeg
	$opt['textAlign'] = ($opt['textAlign'])? $opt['textAlign'] : 'left';
	$opt['font'] = ($opt['font'])? $opt['font'] : 'font_DejaVuSansMono.ttf';#'font_UbuntuMono-R.ttf';
	$opt['fontSize'] = ($opt['fontSize'])? $opt['fontSize'] : 12;
	$opt['fontColor'] = ($opt['fontColor'])? $opt['fontColor'] : '#333';
	$opt['fontColorShade'] = ($opt['fontColorShade'])? $opt['fontColorShade'] : '#999';
	$opt['font'] = 'cms/include_files/'.$opt['font'];
	if(1 === $opt['calType'])
		$img = imagecreate(2 * $opt['widthAllPadding'] + $opt['widthMonth'] + 31 * $opt['widthDay'], 2 * $opt['heightAllPadding'] + $opt['heightHead'] + 12 * $opt['heightDay']);
	elseif(2 === $opt['calType'])
		$img = imagecreate(2 * $opt['widthAllPadding'] + $opt['widthMonth'] + 37 * $opt['widthDay'], 2 * $opt['heightAllPadding'] + $opt['heightHead'] + 12 * $opt['heightDay']);
	elseif(3 === $opt['calType'])
		$img = imagecreate(300, 200);
	//imageantialias($img, true);
	$bgcol = rgb2dec($opt['bgcol']);
	$bgcol = imagecolorallocate($img, $bgcol[0], $bgcol[1], $bgcol[2]);
	imagefill($img, 1, 1, $bgcol);
	$fontcol = rgb2dec($opt['fontColor']);
	$fontcol = imagecolorallocate($img, $fontcol[0], $fontcol[1], $fontcol[2]);
	if($opt['textShade'])
	{
		$fontcolshade = rgb2dec($opt['fontColorShade']);
		$fontcolshade = imagecolorallocate($img, $fontcolshade[0], $fontcolshade[1], $fontcolshade[2]);
		//imagettftext($img, $opt['fontSize'], 0, 1, 15, $fontcolshade, $opt['font'], 'huhu');
	}
	//imagettftext($img, $opt['fontSize'], 0, 0, 14, $fontcol, $opt['font'], 'huhu');
	//ri_text(array('img'=>$img, 'font'=>$opt['font'], 'shade'=>$opt['textShade'], 'shadeC'=>$fontcolshade, 'fontC'=>$fontcol, 'fontS'=>$opt['fontSize'], 'align'=>$opt['textAlign'], 'x'=>400, 'y'=>100, 'text'=>'huhu huha'));
	//ri_text(array('img'=>$img, 'font'=>$opt['font'], 'shade'=>$opt['textShade'], 'shadeC'=>$fontcolshade, 'fontC'=>$fontcol, 'fontS'=>$opt['fontSize'], 'align'=>$opt['textAlign'], 'x'=>400, 'y'=>120, 'text'=>'hallO'));
	//ri_text(array('img'=>$img, 'font'=>$opt['font'], 'shade'=>$opt['textShade'], 'shadeC'=>$fontcolshade, 'fontC'=>$fontcol, 'fontS'=>$opt['fontSize'], 'align'=>$opt['textAlign'], 'x'=>400, 'y'=>140, 'text'=>'ein etwas längerer Text'));
	$x = ('right' === $opt['textAlign'])? $opt['widthAllPadding'] + $opt['widthMonth'] : $opt['widthAllPadding'];
	for($i=0; $i<12; $i++)
	{
		$y = $opt['heightAllPadding']+(($opt['calWithHead'])? $opt['heightHead']+$opt['heightDayMargin'] : 0) + $i*$opt['heightDay'];
		ri_text(array('img'=>$img, 'font'=>$opt['font'], 'shade'=>$opt['textShade'], 'shadeC'=>$fontcolshade, 'fontC'=>$fontcol, 'fontS'=>$opt['fontSize'], 'align'=>$opt['textAlign'], 'x'=>$x, 'y'=>$y, 'text'=>lecho('cal_month_'.(($opt['monthShort'])? 'short' : 'long').'_'.($i+1), 'de')));
		ri_box(array('img'=>$img));
	}
	imageline($img, 100, 100, 300, 200, $fontcol);
	//imagearc($img, 200, 100, 75, 75, 180, 270, $fontcol);
	imagearc($img, 200, 100, 150, 150, 270, 180, $fontcol);
	imagerectangle($img, 100, 0, 300, 200, $fontcol);
	imgarc($img, 400, 100, 150, 150, 180, 270, $fontcol, 9 /*w*/, 1 /*aa*/);
	imagerectangle($img, 300, 0, 500, 200, $fontcol);
	ob_start();
		if('gif' === $opt['mType'])
			imagegif($img, null);
		elseif('jpeg' === $pot['mType'])
			imagejpeg($img, null, 88);
		else
			imagepng($img, null, 9, PNG_ALL_FILTERS);
		imagedestroy($img);
		$img_data = ob_get_contents();
	ob_end_clean();
	$img_data_b64 = base64_encode($img_data);
	$out = '<img src="data:image/'.$opt['mType'].';base64,'.$img_data_b64.'"/><br/>'."\n";
	return $out;
}
function imgarc($img, $cx, $cy, $width, $height, $start, $end, $color, $bold, $aa)
{
	$red   = imagecolorallocate($img, 150, 50, 50);
	$green = imagecolorallocate($img, 50, 150, 50);
	$blue  = imagecolorallocate($img, 50, 50, 150);
	for(round($x=$cx-($width/2)-$bold-$aa); $x <= round($cx+($width/2)+$bold+$aa); $x++)
	{
		for(round($y=$cy-($height/2)-$bold-$aa); $y <= round($cy+($height/2)+$bold+$aa); $y++)
		{
			//imagesetpixel($img, $x, $y, $color);
			$diff = hypot($x - $cx, $y - $cy);
			if(abs( abs($diff) - ($width/2) ) / $bold < 0.5)
				imagesetpixel($img, $x, $y, $color);
		}
	}
}
function reservation_calendar($cal_conf_index, $lang='de', $year=false, $cal_only=false)
{
	if(false === $year || !$year)
	{
		if(isset($_GET['y']))
			$year = intval($_GET['y']);
		else 
			$year = date('Y');
	}
	$cal_conf = get_config_data('res_cal', 'calendar', $cal_conf_index);
	//~ var_dump($cal_conf);
	//~ die();
	if(null === $cal_conf)
	{
		return null;
	}
	$reserved = explode('|', $cal_conf['reserved']);
	foreach($reserved as $k => $v)
	{
		if(0 === preg_match('/^[0-9]{2}-[0-9]{2}-[0-9]{2}$/', $v))
		{
			unset($reserved[$k]);
		}
	}
	$type = intval($cal_conf['type']);
	$kw_on_3 = (in_array('kw_t3', $cal_conf['settings']))? true : false;
	$with_headline = (in_array('with_headline', $cal_conf['settings']))? true : false;
	$month_name_length = (in_array('short_month_names', $cal_conf['settings']))? 'short' : 'long'; # long / short
	$res_first_last_half = (in_array('first_last_resday_half', $cal_conf['settings']))? true : false;
	$wd_arr = array('mo','tu','we','th','fr','sa','su');
	$out = '';
	if(false === $cal_only)
	{
		#$out .= '<a href="'.merge_href('self', array('y'=>$prev_y)).'">&lt;--</a> <a href="'.merge_href('self', array('y'=>date('Y'))).'">'.$year.'</a> <a href="'.merge_href('self', array('y'=>$next_y)).'">--&gt;</a><br/>'."\n";
		//~ $out .= '<div class="buttonset cal_nav" id="cal_idx_'.$cal_conf['conf_idx'].'">'
			//~ .'<span class="cal_button cal_button_prevy" title="'.lecho('cal_nav_prev', $lang).'">previous</span>'
			//~ .'<span class="cal_button cal_button_thisy" title="'.lecho('cal_nav_this', $lang).'">this</span>'
			//~ .'<span class="cal_button cal_button_nexty" title="'.lecho('cal_nav_next', $lang).'">next</span>'
			//~ .'</div>'."\n";
	}
	#$out .= '<div id="js_res_cal" class="res_cal conf_idx:'.$cal_conf['conf_idx'].'"></div>'."\n";
	$out .= '<div id="res_cal_'.$cal_conf['name'].'" class="res_cal t'.$type.' conf_idx:'.$cal_conf['conf_idx'].'"></div>'."\n";
	/*
	$out .= '<div id="res_cal_'.$cal_conf['name'].'" class="res_cal t'.$type.' conf_idx:'.$cal_conf['conf_idx'].'">'."\n";
	$start = mktime(5, 1, 1, 1, 1, $year); # 1Tag: 86.400
	$prev = $start - 86400;
	# Date-Format:
	#  j   #Tag
	#  d  ##Tag
	#  w    Wochentag 0-6 -> So-Sa
	#  N    Wochentag 1-7 -> Mo-So seit PHP 5.1.0
	#  t    Tage im Monat
	#  W  KW
	#  n   #Monat
	#  M  ##Monat
	#  y    ##Jahr
	#  Y  ####Jahr
	#
	if($type == 2 && true === $with_headline)
	{
		$out .= '<div class="row headline"><div class="float month_'.$month_name_length.'">&nbsp;</div>';
		for($i=0; $i<37; $i++)
		{
			$class_we = ($i % 7 === 6 || $i % 7 === 5)? ' is_we' : '';
			#$out .= '<div class="float with_font cal_day empty'.$class_we.'">'.lecho('cal_head_'.$wd_arr[($i % 7)], $lang).'</div>';
			$out .= '<div class="float with_font '.$class_we.'">'.lecho('cal_head_'.$wd_arr[($i % 7)], $lang).'</div>';
		}
		$out .= '</div>'."\n";
	}
	if($type == 1 && true === $with_headline)
	{
		$out .= '<div class="row headline"><div class="float month_'.$month_name_length.'">&nbsp;</div>';
		for($i=1; $i<32; $i++)
			$out .= '<div class="float with_font cal_day empty">'.$i.'</div>';
		$out .= '</div>'."\n";
	}
	for($d=$start; date('Y', $d)==$year; $d+=86400)
	{
		$kw_td = ($type == 3 && true === $kw_on_3)? '<div class="float with_font cal_kw">'.date('W', $d).'</div>' : '';
		if(date('j', $d) == 1) # Monatserster
		{
			if($type == 3)
			{
				if(date('n', $d) % 3 == 1) # neues Quartal
				{
					$out .= '<div class="row quarter">';
				}
				$out .= '<div class="month"><div class="month_head">'.lecho('cal_month_'.$month_name_length.'_'.date('n', $d), $lang).'</div>'."\n";
				if(true === $with_headline)
				{
					$kw_head = (true === $kw_on_3)? '<div class="float with_font">'.lecho('cal_head_kw', $lang).'</div>' : '';
					$out .= '<div class="row headline">'.$kw_head;
					foreach($wd_arr as $wd_k => $wd)
					{
						$class_we = ($wd_k == 5 || $wd_k == 6)? ' is_we' : '';
						$out .= '<div class="float with_font'.$class_we.'">'.lecho('cal_head_'.$wd, $lang).'</div>';
					}
					$out .= '</div>'."\n";
				}
				$out .= '<div class="row week">'. $kw_td . str_repeat('<div class="float with_font cal_day empty">&nbsp;</div>', (('0' === date('w', $d))? 7 : date('w', $d)) - 1);
			}
			else 
			{
				# neuer Monat Typ 1 und 2
				$out .= '<div class="row"><div class="float with_font cal_index_month month_'.$month_name_length.'">'.lecho('cal_month_'.$month_name_length.'_'.date('n', $d), $lang).'</div>';
				if($type == 2)
				{
					$t2_ins = (('0' === date('w', $d))? 7 : date('w', $d)) - 1;
					$out .= str_repeat('<div class="float with_font cal_day empty">&nbsp;</div>', $t2_ins);
				}
			}
		}
		else 
		{
			if($type == 3 && date('w', $d) == 1) # neue Woche
			{
				$out .= '<div class="row week">'.$kw_td;
			}
		}
		#######
		$day_content = ($type == 1 && true === $with_headline)? '&nbsp;' : date('d', $d);
		$class_we = ('6' === date('w', $d) || '0' === date('w', $d))? ' is_we' : '';
		if(in_array(date('y-m-d', $d), $reserved))
		{
			if(true === $res_first_last_half)
			{
				$bg_half = '';
				if(!in_array(date('y-m-d', $d-86400), $reserved))
				{
					$class_res = ' res_beg';
					$bg_half = '<div class="res_half half_beg"></div>';
				}
				elseif(!in_array(date('y-m-d', $d+86400), $reserved))
				{
					$class_res = ' res_end';
					$bg_half = '<div class="res_half helf_end"></div>';
				}
				else 
					$class_res = ' res';
			}
			else 
			{
				$class_res = ' res';
			}
		}
		else 
		{
			$class_res = '';
			$bg_half = '';
		}
		$out .= '<div id="'.$cal_conf['name'].'_d_'.date('y-m-d', $d).'" class="d_'.date('y-m-d', $d).' float with_font cal_day content'.$class_we.$class_res.'" title="'.lecho('cal_weekday_'.(('0' === date('w', $d))? 7 : date('w', $d)), $lang).'">'.$bg_half.$day_content.'</div>';
		#######
		if($type == 3 && date('w', $d) === '0' && date('j', $d) != date('t', $d)) # Woche ende (Sonntag) ABER NICHT Monatsletzter
		{
			$out .= '</div>'."\n";
		}
		if(date('j', $d) == date('t', $d)) # Monatsletzter
		{
			if($type == 1)
				$out .= str_repeat('<div class="float with_font cal_day empty">&nbsp;</div>', 31 - date('t', $d));
			elseif($type == 2)
				$out .= str_repeat('<div class="float with_font cal_day empty">&nbsp;</div>', 37 - date('t', $d) - $t2_ins);
			elseif($type == 3)
			{
				$out .= str_repeat('<div class="float with_font cal_day empty">&nbsp;</div>', 7 - (('0' === date('w', $d))? 7 : date('w', $d)) );
			}
			$out .= '</div>'."\n";
			if($type == 3)
			{
				$out .= '</div>'."\n";
				if(date('n', $d) % 3 === 0) # Quartal zu ende
				{
					$out .= '</div>';
				}
			}
		}
	}
	$out .= '</div>'."\n";
	*/
	if(true === $cal_only)
	{
		return $out;
	}
	$js_reserved = (0 === count($reserved))? false : '\''.implode('\',\'', $reserved).'\'';
	##$out .= '<script type="text/javascript">/*<![CDATA[*/ if(\'undefined\'===typeof(reservations)){var reservations={};} reservations[\''.$cal_conf['name'].'\']={halfDay:'.((in_array('first_last_resday_half', $cal_conf['settings']))? 'true' : 'false' ).',days:\''. implode('|', $reserved) .'\'.split(\'|\')};var res_cal_year_from='.$year_allowed_from.',res_cal_year_to='.$year_allowed_to.',res_cal_current_year='.date('Y').';/*]]>*/</script>'."\n";
	$out .= '<script type="text/javascript">/*<![CDATA[*/ if(\'undefined\'===typeof(reservations)){var reservations={};} reservations[\''.$cal_conf['name'].'\']='.json_encode($cal_conf).';var res_cal_current_year='.date('Y').';if(\'undefined\'===typeof(lang_obj)){var lang_obj={};} if(!lang_obj.'.$lang.'){lang_obj.'.$lang.'={};} lang_obj.'.$lang.'.cal_nav_prev='.json_encode(lecho('cal_nav_prev', $lang)).';lang_obj.'.$lang.'.cal_nav_this='.json_encode(lecho('cal_nav_this', $lang)).';lang_obj.'.$lang.'.cal_nav_next='.json_encode(lecho('cal_nav_next', $lang)).';/*]]>*/</script>'."\n";
	if('admin&' !== substr($_SERVER['QUERY_STRING'], 0, 6))
	{
		if(in_array('show_form', $cal_conf['form_settings']))
		{
			preg_match('/=([a-zA-Z0-9_]+)]/', $cal_conf_index, $conf_idx_str);
			$out .= '<div class="res_form form ajaxSubmit" id="res_form_'.$cal_conf['name'].'" method="post" action="">'."\n";
			$out .= '<div class="form_row"><div class="label">&nbsp;</div><div class="input">* = '.lecho('field_required', $lang).'</div></div>'."\n";
			$out .= '<div class="form_row must"><div class="label"><label for="reservation_from_'.$conf_idx_str[1].'">'.lecho('cal_form_reservation_from', $lang).'*</label>/<label for="reservation_to_'.$conf_idx_str[1].'">'.lecho('cal_form_reservation_to', $lang).'*</label></div><div class="input"><input type="text" class="w3 datepicker" id="reservation_from_'.$conf_idx_str[1].'" name="reservation_from" /><input type="text" class="w3 datepicker" id="reservation_to_'.$conf_idx_str[1].'" name="reservation_to" /></div></div>'."\n";
			//$out .= '<div class="form_row must"><div class="label"><label for="name_'.$conf_idx_str[1].'">'.lecho('cal_form_name', $lang).'*</label></div><div class="input"><input type="text" id="name_'.$conf_idx_str[1].'" name="name" /></div></div>'."\n";
			$out .= gen_config_field(array('value'=>'name','must'=>1), array('file'=>'kalender_1','val'=>'name'));
			if(in_array('need_address', $cal_conf['form_settings']))
			{
				$out .= '<div class="form_row must"><div class="label"><label for="street_'.$conf_idx_str[1].'">'.lecho('cal_form_street', $lang).'*</label>/<label for="strnum_'.$conf_idx_str[1].'">'.lecho('cal_form_strnum', $lang).'*</label></div><div class="input"><input type="text" class="w2" id="street_'.$conf_idx_str[1].'" name="street" /><input type="text" class="w1" id="strnum_'.$conf_idx_str[1].'" name="strnum" /></div></div>'."\n";
				$out .= '<div class="form_row must"><div class="label"><label for="zip_'.$conf_idx_str[1].'">'.lecho('cal_form_zip', $lang).'*</label>/<label for="city_'.$conf_idx_str[1].'">'.lecho('cal_form_city', $lang).'*</label></div><div class="input"><input type="text" class="w1" id="zip_'.$conf_idx_str[1].'" name="zip" /><input type="text" class="w2" id="city_'.$conf_idx_str[1].'" name="city" /></div></div>'."\n";
			}
			if(in_array('need_country', $cal_conf['form_settings']))
				$out .= '<div class="form_row must"><div class="label"><label for="country_'.$conf_idx_str[1].'">'.lecho('cal_form_country', $lang).'*</label></div><div class="input"><input type="text" id="country_'.$conf_idx_str[1].'" name="country" /></div></div>'."\n";
			if(in_array('need_email', $cal_conf['form_settings']))
				$out .= '<div class="form_row must"><div class="label"><label for="email_'.$conf_idx_str[1].'">'.lecho('cal_form_email', $lang).'*</label></div><div class="input"><input type="text" class="email" id="email_'.$conf_idx_str[1].'" name="email" /></div></div>'."\n";
			if(in_array('need_phone', $cal_conf['form_settings']))
				$out .= '<div class="form_row must"><div class="label"><label for="phone_'.$conf_idx_str[1].'">'.lecho('cal_form_phone', $lang).'*</label></div><div class="input"><input type="text" id="phone_'.$conf_idx_str[1].'" name="phone" /></div></div>'."\n";
			if(in_array('need_legal_accept', $cal_conf['form_settings']))
			{
				$legal_txt_name = get_config_data('textblock', 'textblock', $cal_conf['legal_condition'], 'name');
				$out .= '<div class="form_row must"><div class="label"><label for="legal_'.$conf_idx_str[1].'">'.lecho('cal_form_legal', $lang).'*</label></div><div class="input"><input type="checkbox" id="legal_'.$conf_idx_str[1].'" name="legal" /><br/>'.lecho('cal_form_legal_t1', $lang).'<a id="link_legal_'.$cal_conf['name'].'" class="div2info" href="#">'.lecho('cal_form_legal_t2', $lang).'</a>.</div></div>'."\n";
				//$out .= '<script type="text/javascript">/*<![CDATA[*/$(\'#legal_link_'.$cal_conf['name'].'\').click(function(ev){ev.preventDefault();show_info($(\'#legal_text_'.$cal_conf['name'].'\').html(), \''.lecho('cal_form_legal', $lang).'\');})/*]]>*/</script>'."\n";
				$out .= '<div id="div_legal_'.$cal_conf['name'].'" class="div2info_div" title="'.lecho('cal_form_legal', $lang).'">'.get_textblock($legal_txt_name, $lang).'</div>'."\n";
			}
			if(in_array('allow_msg', $cal_conf['form_settings']))
				$out .= '<div class="form_row"><div class="label"><label for="msg_'.$conf_idx_str[1].'">'.lecho('cal_form_msg', $lang).'</label></div><div class="input"><textarea rows="10" cols="10" id="msg_'.$conf_idx_str[1].'" name="msg" ></textarea></div></div>'."\n";
			if(in_array('need_captcha', $cal_conf['form_settings']))
			{
				//$captcha = captcha();
				//$out .= '<div class="form_row"><div class="label"><label for="captcha_'.$conf_idx_str[1].'">'.lecho('cal_form_captcha', $lang).'</label></div><div class="input">'.$captcha[0].$captcha[2].'</div></div>'."\n";
				$out .= '<div class="form_row captcha"><div class="label"><label for="captcha_'.$conf_idx_str[1].'">'.lecho('cal_form_captcha', $lang).'</label></div><div class="input captcha"></div></div>'."\n";
				$out .= '<div class="form_row captcha must"><div class="label"><label for="captcha_answer_'.$conf_idx_str[1].'">'.lecho('cal_form_captcha_answer', $lang).'*</label></div><div class="input"><input type="text" id="captcha_answer_'.$conf_idx_str[1].'" name="captcha_answer" /></div></div>'."\n";
			}
			$out .= '<input type="submit"/>';
			$out .= '</div>'."\n";
		}
		$out .= "<br/>\n";
		$out .= '<div class="form ajaxSubmit">'."\n";
		$out .= gen_form_field();
		$ff = array(
			'label' => 'bitte eingeben',
			'type' => 'text',
			'name' => 'Feld1',
			//'value' => 'default',
			'help' => 'hier ist bitte ein <b>Wert</b> einzutragen.',
			'set'=>array('must'=>'1'),
		);
		$out .= gen_form_field($ff);
		$ff = array(
			'label' => 'post an',
			'type' => 'email',
			'name' => 'email',
			'value' => 'e@mail.de',
			'help' => '',
		);
		$out .= gen_form_field($ff);
		$ff = array(
			'label' => 'bitte auswählen',
			'type' => 'select_one',
			'name' => 'choice',
			'value' => 'zweites',
			'help' => 'Such dir was raus.',
			'options' => array('_none_'=>'---','erstes','zweites','drittes'),
		);
		$out .= gen_form_field($ff);
		$ff = array(
			'label' => 'bitte mehr auswählen',
			'type' => 'select_more',
			'name' => 'choice_more',
			'help' => 'Such dir mehr raus.',
			'value' => array('eins','drei'),
			'options' => array('eins','zwei','drei'),
		);
		$out .= gen_form_field($ff);
		$out .= '<input type="submit"/>'."\n";
		$out .= '</div>'."\n";
	}
	return $out;
}
function merge_href($p='self', $vars=array(), $noescape=false)
{
	global $req_page;
	$out = '?';
	$out .= ('self' == $p)? $req_page : $p;
	foreach($_GET as $k => $v)
	{
		if(isset($vars[$k]))
		{
			$out .= (false === $vars[$k])? '' : "&$k={$vars[$k]}";
			unset($vars[$k]);
		}
		else 
			$out .= "&$k=$v";
	}
	foreach($vars as $k => $v)
	{
		$out .= (false === $v)? '' : "&$k=$v";
	}
	if(false === $noescape)
		$out = htmlspecialchars($out);
	return $out;
}
function show_info($t)
{
	$out = '<div class="ui-widget"><div class="ui-corner-all ui-state-highlight" style="position:relative;padding:0px 20px;"><span class="ui-icon ui-icon-info" style="position:absolute;top:2px;left:2px;"></span><p>'.$t.'</p></div></div>'."\n";
	return $out;
}
function show_warning($t)
{
	$out = '<div class="ui-widget"><div class="ui-corner-all ui-state-error" style="position:relative;padding:0px 20px;"><span class="ui-icon ui-icon-alert" style="position:absolute;top:2px;left:2px;"></span><p>'.$t.'</p></div></div>'."\n";
	return $out;
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
		$js = '<script type="text/javascript">/*<![CDATA[*/WebFontConfig={google:{families:["Ubuntu+Mono::latin"]}};(function(){var a=document.createElement("script");a.src=("https:"==document.location.protocol?"https":"http")+"://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js";a.type="text/javascript";a.async="true";var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b)})();/*]]>*/</script>';
		$prev = '<div style="font-family: \'Ubuntu Mono\';font-size:20px;text-shadow:none;color:'.$font_color.';background-color:'.$bg_color.';line-height:18px; width:'.(strlen($str)*10).'px; height:20px; border:1px solid #000;">' . $str . '</div>'."\n";
		$out = $js . $prev;
	}
	elseif('php' == $do_it_with)
	{
		$font = 'cms/include_files/font_UbuntuMono-R.ttf';
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
