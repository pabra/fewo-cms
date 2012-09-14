/*! ADMIN JS */
var admin_keep_alive_off = true, lang_obj={};
$(function(){
	'use strict';
	//alert('admin');
	$('input[type=text], input[type=password]').focus(function(){
		var self = $(this);
		window.setTimeout(function(){ self.select(); }, 5);
	});
	$('form.login input[name=user]').focus();
	$('form.login').submit(function(ev){
		var form_post_data = '', form_fields = $(this).find('textarea,select,input'), form = $(this);
		ev.preventDefault();
		$(this).find('input[type=password]').each(function(k,v){
			if($(this).prop('name').indexOf('md5_') === -1){
				$(this).prop({name: 'md5_'+$(this).prop('name'), value: MD5($(this).val())});
			} else {
				$(this).prop({value: MD5($(this).val())});
			}
		});
		form_post_data = $(this).serialize(); // 'json='+escape($(this).serializeArray());
		form_fields.prop({disabled:true});
		//clog(form_post_data);
		//clog($(this).attr('action').match(/do=[a-zA-Z0-9_-]+/));
		$.post('ajax.php?'+$(this).attr('action').match(/do=[a-zA-Z0-9_-]+/), form_post_data, function(data){
			//clog(data);
			if(false === data.status){
				show_warning(data.msg);
				form_fields.prop({disabled:false});
				form.find('input[type=password]').val('');
				/*$('#dialog').attr('title', 'Fehler').text(data.msg).dialog({
					modal:true,
					buttons: {
						Ok: function() {
							$( this ).dialog( "close" );
						}
					}
					});*/
			} else {
				//clog('goto: '+form.find('[name=req_page]').val());
				//clog(form);
				//window.location = '?'+form.find('[name=req_page]').val();
				//window.location = window.location;
				window.location = window.location.href.replace(/#.*$/, '');
			}
		});
	});
	$('a.ajax').click(function(ev){
		ev.preventDefault();
		//clog($(this).attr('href').substr(1));
		$.post('ajax.php', $(this).attr('href').substr(1), function(data){
			//clog(data);
			//window.location.reload();
			window.location = window.location;
		});
	});
	//$('fieldset.sortable').disableSelection();
	//$('fieldset.sortable input').click(function(){ if(false === $(this).is(':focus')){ $(this).focus(); } });
	/*$('div.idx_group:not(:first)').mouseenter(function(ev){
		$(this).find('.idx_nav.move_up').show('slide',{direction:'down'},300);
	});
	$('div.idx_group:not(:last)').mouseenter(function(ev){
		$(this).find('.idx_nav.move_down').show('slide',{direction:'down'},300);
	});*/
	if(0 < $('form.config').length){
		fieldset_add_listener();
		$('form.config .idx_group .activate_new_index').parents('.idx_group').unbind('mouseenter');
		$('form.config .idx_group .activate_new_index').click(function(){
			var group = $(this).parents('.idx_group');
			$(this).mouseleave().remove();
			fieldset_add_listener();
			group.find('input, textarea, select').change();
		});
		$('form.config input.include_code').each(function(k,v){
			var self = $(this),
				id_field = self.parents('.idx_group').find('input[type=text]:first'),
				inc_code_file_var = self.attr('id').split(':'),
				inc_code = ['[[:'+inc_code_file_var[0]+':',':]]'];
			self.val(inc_code[0]+id_field.val()+inc_code[1]);
			id_field.change(function(){
				//clog(self);
				//clog(id_field);
				self.val(inc_code[0]+id_field.val()+inc_code[1]);
			});
		});
		$('form.config').submit(function(ev){
			var send_obj = {}, mis_matched = $('form.config .mismatch');
			ev.preventDefault();
			if(0 < mis_matched.length){
				mis_matched.effect('shake', 100);
				return false;
			}
			$('form.config .changed').each(function(k, v){
				var send_el = $(v).find('input, textarea, select').first(),
					modul_match = false, tmp_str;
				//clog(v);
				if($(v).hasClass('select_more')){
					send_el = $(v).find('input.select_more_value').first();
				} else if($(v).hasClass('no_parent')){
					send_el = $(v);
				}
				if(send_el.hasClass('tinymce')){
					modul_match =  send_el.val().match(/\[\[:([a-zA-Z0-9_]+):([a-zA-Z0-9_]+):\]\]/g);
					if(modul_match){
						//clog('match');
						$.each(modul_match, function(k,v){
							//clog(v);
							tmp_str = $('<div class="modul_match" />').append(send_el.val().replace(v, '<br id="modulMatchPlaceHolder" />'));
							if(1 === tmp_str
									.find('#modulMatchPlaceHolder')
									.parent('p')
									.find('#modulMatchPlaceHolder:only-child')
									.length){
								tmp_str.find('#modulMatchPlaceHolder').parent('p').replaceWith('<div>'+v+'</div>');
								//clog(tmp_str.html());
								send_el.val( tmp_str.html() );
							}
						});
					}
				}
				send_obj[send_el.prop('name')] = send_el.val();
			});
			//$.each
			//clog(send_obj.length);
			if(0 === Object.size(send_obj)){
				//$('#dialog').attr({title:'nothing to do'}).text('Here is nothing to do.').dialog({modal:true, buttons:{ok:function(){$(this).dialog('close');}}});
				//$('#dialog').attr('title', 'nothing changed').html('No changed values to submit.').dialog({modal:true, buttons:{ok:function(){$(this).dialog('close');}}});
				show_info('No changed values to submit.','nothing changed');
			} else {
				//clog(send_obj);
				$.post('ajax.php?do=send_config', send_obj, function(data){
					//clog(data);
					if(true === data.status){
						window.location.reload();
					} else {
						//$('#dialog').attr('title', 'Error').html(data.txt).dialog({modal:true, buttons:{ok:function(){$(this).dialog('close');}}});
						show_warning(data.txt, 'Error');
					}
				});
			}
		});
	}
	
	// dircontent
	if(1 === $('#dir_content').length){
		var get_to = false, prog_hide_to = false, delete_files = [], 
			dircontent_binder  = function(){
				var reEnableToolTip_to = false,
				fRenTidy = function(t){
					return $.trim( t.replace(/[^a-zA-Z0-9 üöäÜÖÄß\(\)._-]/g, '')
							.replace(/^[^a-zA-Z0-9_üöäÜÖÄ\(\)]+/g, '')
							.replace(/\s*\.\s*/g, '.')
							.replace(/\s+/g, ' ')
							);
				},
				fRenUndo = function(elInput, elSpan){
					elInput.replaceWith(elSpan);
					fRenSpanListener(elSpan);
					reEnableToolTip_to = window.setTimeout(function(){
						enableToolTips();
					}, 300);
				},
				fRenSpanListener = function(el){
					el.click(function(ev){
						ev.stopPropagation();
						fRenInput( $(this) );
					});
				},
				fRenDouble = function(t){
					var double = false;
					$('.dircontent_row .filename').each(function(k,v){
						if(t === $(v).text()){
							double = true;
						}
					});
					return double;
				},
				fRenTryRename = function(elInput, elSpan){
					elInput.val( fRenTidy(elInput.val()) );
					if(elInput.val() === elSpan.text()){
						fRenUndo(elInput, elSpan);
					} else {
						if(true === fRenDouble(elInput.val())){
							fRenUndo(elInput, elSpan);
						} else {
							//clog('rename');
							if(!elInput.val().match(/\.[^.]+$/) && elSpan.text().match(/\.[^.]+$/)){
								elInput.val( elInput.val() + elSpan.text().match(/\.[^.]+$/)[0] );
							}
							$.post('ajax.php?do=user_files_rename', {from:elSpan.text(), to:elInput.val()}, function(data){
								refresh_dir_content();
								enableToolTips();
							});
						}
					}
				},
				fRenInput = function(el){
					var repIn = $('<input/>')
						.attr({type:'text', value:fRenTidy(el.text()) })
						.css({fontFamily:'monospace',fontWeight:'bold',fontSize:'10pt',marginLeft:'-4px',width:400,padding:'0px 2px',height:'18px'})
						.focusout(function(){
							fRenUndo($(this), el);
						})
						.keydown(function(ev){
							ev.stopPropagation();
							if(13 === ev.which){
								fRenTryRename($(this), el);
							} else if(27 === ev.which){
								fRenUndo($(this), el);
							}
						});
					window.clearTimeout(reEnableToolTip_to);
					disableToolTips();
					el.replaceWith(repIn);
					repIn.focus().select();
				};
				$('.dircontent_row').click(function(){
					if(true === noToolTips){
						return false;
					}
					//$(this).parents('.dircontent_row').find('.select_file').click();
					//$(this).find('.select_file').click();
					if(true === $(this).find('.select_file').prop('checked')){
						$(this).find('.select_file').prop({checked:false});
					} else {
						$(this).find('.select_file').prop({checked:true});
					}
					$(this).find('.select_file').change();
				});
				$('.dircontent_manage .rename_file').click(function(ev){
					ev.stopPropagation();
					fRenInput( $(this).parents('.dircontent_row').find('.filename') );
				});
				$('.dircontent_row .filename').each(function(k,v){
					fRenSpanListener( $(v) );
				});
				$('.dircontent_manage .delete_file').click(function(ev){
					ev.stopPropagation();
					delete_files = [];
					var file_name = $(this).parents('.dircontent_row').find('.filename').text();
					delete_files.push(file_name);
					clog(delete_files);
					delete_confirm();
				});
				$('.dircontent_manage .download_file').click(function(ev){
					ev.stopPropagation();
				});
				$('.dircontent_manage input.select_file').click(function(ev){
					ev.stopPropagation();
					$(this).change();
				});
				$('.dircontent_manage input.select_file').change(function(ev){
					ev.stopPropagation();
					if($(this).prop('checked')){
						$(this).css({display:'inline-block'});
					} else {
						$(this).css({display:''});
					}});
			},
			refresh_dir_content = function(){
				$.post('ajax.php?do=user_files_list', {time:$.now()}, function(data){
					$('#dir_content').html(data);
					titleToTip();
					dircontent_binder();
				});
			},
			delete_confirm = function(){
				lecho(['dircontent_confirm_delete', 'dircontent_confirm_del_files']);
				show_confirm(
					'<strong>'+lecho('dircontent_confirm_del_files') + delete_files.length+'</strong><br/>'+delete_files.slice(0, 10).join('<br/>')+((10<delete_files.length)? '<br/>...' : ''), 
					lecho('dircontent_confirm_delete'), 
					function(){
						clog($('#dialog').prop('choice')); 
						if(true === $('#dialog').prop('choice')){
							$.post('ajax.php?do=user_files_delete', {delete_files:delete_files}, function(data){
								//clog(data);
								refresh_dir_content();
							});
						}
					});
			};
		$('.jqful_add').button({icons:{primary:'ui-icon-plusthick'}});
		$('.jqful_del').button({icons:{primary:'ui-icon-trash'}}).click(function(){
			var sel_files = $('.dircontent_manage input.select_file:checked');
			delete_files = [];
			//clog(sel_files);
			if(0 < sel_files.length){
				sel_files.each(function(k,v){
					var file_name = $(this).parents('.dircontent_row').find('.filename').text();
					delete_files.push(file_name);
				});
				clog(delete_files);
				delete_confirm();
			}
		});
		$('.jqful_sel').button({icons:{primary:'ui-icon-circlesmall-close'}}).toggle(
			function(){ $('.dircontent_manage input.select_file').prop({checked:true}).change(); },
			function(){ $('.dircontent_manage input.select_file').prop({checked:false}).change(); }
		);
		$(document).keydown(function(ev){
			if(46 === ev.which){
				$('.jqful_del').click();
			}
		});
		$('.buttonset').buttonset();
		//$('div.form_row, div.input').css({width:'auto'});
		//$('input[type=file]').css({display:'inline'});
		//$('div.input').buttonset();
		$('#jqful_input').fileupload({
			dataType:'json',
			limitConcurrentUploads:3,
			add: function (e, data) {
				$('.jqful_label').show();
				data.context = $('<p/>').html('&nbsp;&nbsp;'+data.files[0].name + ' Uploading...').addClass('progress').prepend('<span class="bar"></div>').appendTo('.jqful_feedback');
				//data.submit();
				$(this).fileupload('process', data).done(function () {
					data.submit();
					$(window).scrollTop(10000);
				});
			},
			done: function(e,data){
				var self = data.context;
				data.context.html('&nbsp;&nbsp;'+data.files[0].name + ' Upload finished.').show(1).delay(1000).hide(300, function(){ $(this).remove(); });
				window.clearTimeout(get_to);
				get_to = window.setTimeout(function(){
					//$('.jqful_label').hide(300);
					//clog(data);
					refresh_dir_content();
				}, 1000);
			},
			progress: function(e,data){
				var progress = parseInt(data.loaded / data.total * 100, 10);
				data.context.find('.bar').css({width: progress + '%'});
			},
			progressall: function(e,data){
				var progress = parseInt(data.loaded / data.total * 100, 10);
				$('div.progress .bar').css({width: progress + '%'}).html('&nbsp;&nbsp;'+progress+'%');
				if(100 === progress){
					window.clearTimeout(prog_hide_to);
					window.setTimeout(function(){
						$('.jqful_label').hide(300);
					}, 1000);
				}
			}
		});
		dircontent_binder();
		$('.select_file').change();
	}
	/*$('form.config').on('reset', function(ev){
		ev.preventDefault();
		clog('reset');
		return;
		//var self = $(this);
		window.setTimeout(function(){
			$('form.config input, form.config textarea, form.config select').each(function(){
				//clog($(this));
				if($(this).hasClass('sort_order') || $(this).hasClass('delete_index')){
					$(this).change();
				} else {
					$(this).change();
				}
			});
		}, 50);
	});*/
	if('undefined' !== typeof(admin_keep_alive)){
		var jetzt = new Date().getTime() / 1000, 
			admin_keep_alive_off = jetzt + admin_keep_alive ;
		//jetzt = jetzt.getTime() / 1000;
		//$('#admin_head_bar').append('<div id="keep_alive_timer"></div>');
		$('#keep_alive_timer').text(sec_to_str(admin_keep_alive));
		window.setInterval(function(){
			var jetzt = new Date().getTime() / 1000,
				rest = admin_keep_alive_off - jetzt,
				show = sec_to_str(rest);
			if(rest < -5){
				//window.location.reload();
				window.location = window.location;
			}
			$('#keep_alive_timer').text(show);
		}, 500);
	}
});
function fieldset_add_listener(){
	'use strict';
	var idx_nav_to;
	$('form.config fieldset.sortable').sortable({
		items: "div.idx_group",
		handle: ".move_free",
		opacity: 0.6,
		axis:'y',
		//forcePlaceholderSize: true,
		//forceHelperSize:true,
		update:function(ev){
			idx_group_sorted($(this));
		},
		change:function(){
			$('fieldset.sortable div.idx_group:even').removeClass('odd').addClass('even');
			$('fieldset.sortable div.idx_group:odd').removeClass('even').addClass('odd');
		}
	});
	$('form.config div.idx_group').unbind('mouseenter').mouseenter(function(ev){
		var self = $(this), self_ev = ev;
		window.clearTimeout(idx_nav_to);
		idx_nav_to = window.setTimeout(function(){
			var fieldset = self.parents('fieldset'),
				keep, count_groups;
			self.find('.idx_nav:not(.move_up):not(.move_down):not(.delete):not(.move_free)').show('slide',{direction:'down'},300);
			if(fieldset.hasClass('keep')){
				keep = parseInt(fieldset.prop('className').match(/keep_([0-9]+)/)[1], 10);
			} else {
				keep = 0;
			}
			count_groups = fieldset.find('.idx_group').length;
			//clog('count: '+count_groups);
			//clog('keep: '+keep);
			if(count_groups > keep){
				self.find('.idx_nav.delete').show('slide',{direction:'down'},300);
			}
			if(1 === self.prev('.idx_group').length){
				self.find('.idx_nav.move_up').show('slide',{direction:'down'},300);
			}
			if(1 === self.next('.idx_group').length){
				self.find('.idx_nav.move_down').show('slide',{direction:'down'},300);
			}
			if(1 < self.parents('fieldset').find('.idx_group').length){
				self.find('.idx_nav.move_free').show('slide',{direction:'down'},300);
			}
		}, 300);
	}).unbind('mouseleave').mouseleave(function(ev){
		$(this).find('.idx_nav:visible').hide('slide',{direction:'down'},300);
		window.clearTimeout(idx_nav_to);
	});
	$('form.config .idx_nav li').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	);
	$('form.config .idx_nav.move_up').unbind('click').click(function(){
		$(this).parents('.idx_group').insertBefore($(this).parents('.idx_group').prev('.idx_group'));
		//$(this).parents('.idx_group').find('.idx_nav:visible').hide('slide',{direction:'down'},300);
		idx_group_sorted($(this).parents('fieldset'));
	});
	$('form.config .idx_nav.move_down').unbind('click').click(function(){
		$(this).parents('.idx_group').insertAfter($(this).parents('.idx_group').next('.idx_group'));
		//$(this).parents('.idx_group').find('.idx_nav:visible').hide('slide',{direction:'down'},300);
		idx_group_sorted($(this).parents('fieldset'));
	});
	$('form.config .idx_nav.delete').unbind('click').click(function(){
		/*$('#dialog').attr({title:'Delete?'}).html('This will delete that entry<br/>Are you sure?').dialog({modal:true, buttons:{
			'Delete them': function(){$(this).dialog('close');},
			Cancel: function(){$(this).dialog('close');}
		}});*/
		var input = $(this).parents('fieldset').find('.delete_index'),
			group = $(this).parents('.idx_group'),
			index_key = group.prop('id').split(':')[1],
			fieldset = $(this).parents('fieldset');
		//$(this).parents('.idx_group').find('.idx_nav:visible').hide('slide',{direction:'down'},300);
		idx_group_sorted($(this).parents('fieldset'));
		if('_new_' !== index_key.substr(0,5)){
			if(input.val()){
				input.val(input.val()+':'+index_key);
			} else {
				input.val(index_key);
			}
		}
		group.remove();
		input.change();
		idx_group_sorted(fieldset);
	});
	$('form.config .idx_nav.insert_new').unbind('click').click(function(){
		var master = $(this).parents('.idx_group'),
			master_index = master.prop('id').match(/([a-zA-Z0-9_]+):([a-zA-Z0-9_]+)/),
			master_var = master_index[1],
			clone_index = '',
			i = 1,
			clone = master.clone();
		master_index = master_index[2];
		do{
			clone_index = '_new_'+i;
			i++;
		}while(0 < $(this).parents('fieldset').find('.idx_group[id="'+master_var+':'+clone_index+'"]').length);
		clone.attr({id:master_var+':'+clone_index});
		clone.find('input, textarea, select, label').each(function(){
			var new_id = $(this).prop('id').replace(':'+master_index+':', ':'+clone_index+':');
			if('label' === $(this).prop('nodeName').toLowerCase()){
				$(this).attr({'for': $(this).attr('for').replace(':'+master_index+':', ':'+clone_index+':') });
			} else {
				$(this).prop({id:new_id, name:new_id});
				if('checkbox' !== $(this).prop('type')){
					$(this).prop({defaultValue:''}).val('');
				}
			}
		});
		clone.insertAfter(master);
		fieldset_add_listener();
		titleToTip();
		idx_group_sorted($(this).parents('fieldset'));
		clone.find('input, textarea, select').change();
		clog(clone);
	});
	$('form.config .ui-icon-arrowreturnthick-1-s').unbind('click').click(function(){
		$(this).parents('.form_row').find('input, textarea, select').each(function(k,v){
			if('checkbox' === $(v).prop('type')){
				$(v).prop({checked: $(v).prop('defaultChecked')});
			} else {
				$(v).val($(v).prop('defaultValue'));
				if('password' === $(v).prop('type')){
					$(v).parents('.form_row').removeClass('mismatch changed');
				} else {
					$(v).change();
				}
			}
		});
	});
	$('form.config input[type=password]').unbind('keydown').keydown(function(ev){
		if(13 === ev.which){
			ev.preventDefault();
			//clog($(this));
			$(this).blur();
			//$(this).change();
		}
	});
	$('form.config input, form.config textarea, form.config select').unbind('change').change(function(ev){
		var match_regex = '', multi_var = [], self = $(this);
		clog('val:'+$(this).val()+' - default: '+$(this).prop('defaultValue'));
		$(this).removeClass('changed');
		$(this).parents('.form_row').removeClass('mismatch changed');
		if($(this).hasClass('must') && !$(this).val()){
			clog('must');
			$(this).parents('.form_row').addClass('mismatch');
			return;
		}
		if($(this).hasClass('match')){
			match_regex = new RegExp(decodeURIComponent($(this).prop('className').match(/match_([a-zA-Z0-9%._-]+)/)[1]));
			clog($(this).val().match(match_regex));
			if(!$(this).val().match(match_regex)){
				$(this).parents('.form_row').addClass('mismatch');
				return;
			}
		}
		if($(this).hasClass('email')){
			if(false === check_email_address($(this).val()))
			{
				$(this).parents('.form_row').addClass('mismatch');
				return;
			}
		}
		if('checkbox' === $(this).prop('type')){
			$(this).parents('.form_row').find('input[type=checkbox]').each(function(k, v){
				if(true === $(v).prop('checked')){
					multi_var.push($(v).val());
				}
			});
			$(this).parents('.form_row').find('input.select_more_value').val(multi_var.join(':')).change();
			clog(multi_var.join(':'));
			return;
		}
		if('password' === $(this).prop('type')){
			//ev.stopImmediatePropagation();
			//ev.preventDefault();
			//clog(ev);
			clog(MD5($(this).val()));
			if(MD5($(this).val()) === $(this).prop('defaultValue')){
				$(this).val($(this).prop('defaultValue'));
				return;
			}
			lecho(['pass_doublecheck_title','pass_doublecheck_body','pass_doublecheck_mismatch']);
			$('#dialog').attr('title', lecho('pass_doublecheck_title')).html(lecho('pass_doublecheck_body')+'<br/><input type="password" name="pass_doublecheck" /><br/>');
			$('#dialog input[name=pass_doublecheck]').keydown(function(ev){
				if(13 === ev.which){
					$('[role=dialog] button[type=button]').click();
				}
			});
			$('#dialog').dialog({modal:true,buttons:{
				ok:function(){
					var dc_pass = $('input[name=pass_doublecheck]').val();
					$('input[name=pass_doublecheck]').prop({doubleChecked: true});
					$(this).dialog('close');
					if(dc_pass === self.val()){
						$(self).val(MD5(self.val()));
						self.parents('.form_row').addClass('changed');
					} else {
						window.alert(lecho('pass_doublecheck_mismatch'));
						self.parents('.form_row').find('.ui-icon-arrowreturnthick-1-s').click();
					}
					dc_pass = '';
				}
				},close:function(){
					$('input[name=pass_doublecheck]').val('');
					if(true !== $('input[name=pass_doublecheck]').prop('doubleChecked')){
						self.parents('.form_row').find('.ui-icon-arrowreturnthick-1-s').click();
					}
					/*if('' !== $('input[name=pass_doublecheck]').val()){
						self.parents('.form_row').find('.ui-icon-arrowreturnthick-1-s').click();
					}*/
					//alert('close');
				}
			});
			//$(this).val(MD5($(this).val()));
		}
		if($(this).val() !== $(this).prop('defaultValue')){
			//$(this).addClass('changed');
			clog('add changed');
			if($(this).hasClass('no_parent')){
				$(this).addClass('changed');
			} else {
				$(this).parents('.form_row').addClass('changed');
			}
		}else if(0 < $(this).attr('name').indexOf(':_new_')){
			clog('add _new_ changed');
			if($(this).hasClass('no_parent')){
				$(this).addClass('changed');
			} else {
				$(this).parents('.form_row').addClass('changed');
			}
		}
	});
	$('form.config select').each(function(k, v){
		if('undefined' === typeof($(v).prop('defaultValue'))){
			var sel = $(v).find('option[selected]');
			if(1 === sel.length){
				$(v).prop({defaultValue: sel.val()});
			} else {
				$(v).prop({defaultValue: ''});
				$(v).change();
			}
			//$(v).prop({defaultValue: $(v).val()});
		}
		//clog($(v));
	});
	$('form.config input:not(.notrim)').change(function(){
		var trimmed = $.trim($(this).val());
		if(trimmed !== $(this).val()){
			$(this).val(trimmed).change();
		}
	});
}
function idx_group_sorted(fieldset){
	'use strict';
	/*clog(self);
	clog(ev);
	clog($(ev.srcElement).parents('.idx_group').find('.idx_nav:visible'));*/
	//$(ev.srcElement).parents('.idx_group').find('.idx_nav:visible').hide();
	fieldset.find('.idx_nav:visible').hide();
	var sort_field = fieldset.find('input.sort_order'),
		new_sort = [];
	//$('fieldset.sortable div.idx_group:even').removeClass('odd').addClass('even');
	//$('fieldset.sortable div.idx_group:odd').removeClass('even').addClass('odd');
	fieldset.find('.idx_group:even').removeClass('odd').addClass('even');
	fieldset.find('.idx_group:odd').removeClass('even').addClass('odd');
	fieldset.find('div.idx_group').each(function(k,v){
		clog($(v).prop('id').split(':')[1]);
		new_sort.push($(v).prop('id').split(':')[1]);
	});
	sort_field.val(new_sort.join(':')).change();
}
function sec_to_str(s){
	'use strict';
	var str_hou, str_min, str_sec, str_out;
	s = parseInt(s, 10);
	if(s < 0){
		str_out = '00:00';
	} else {
		str_hou = Math.floor(s/60/60);
		str_min = Math.floor((s - (str_hou *60*60))/60);
		str_sec = Math.floor(s - (str_hou *60*60)-(str_min * 60));
		if(str_hou === 0){
			str_hou = '';
		} else {
			str_hou = str_hou+':';
		}
		if(str_min < 10){
			str_min = '0'+str_min;
		}
		if(str_sec < 10){
			str_sec = '0'+str_sec;
		}
		str_out = str_hou + str_min + ':' + str_sec;
	}
	return str_out;
}
function lecho(t,l)
{
	'use strict';
	var send_obj = [];
	if('undefined' === typeof(l)){
		if('undefined' === typeof(docLang)){
			l = 'de';
		} else {
			l = docLang;
		}
	}
	if('undefined' === typeof(lang_obj[l])){
		lang_obj[l] = {};
	}
	if('object' === typeof(t)){
		$.each(t, function(k, v){
			if('undefined' === typeof(lang_obj[l][v])){
				send_obj.push(v);
			}
		});
		if(0 < send_obj.length){
			$.ajax({url: 'ajax.php', data:{'do':'lecho', 'text':t, 'lang':l}, async:false, success:function(data){
				//lang_obj[l][t] = data.txt;
				$.each(data.txt, function(k, v){
					lang_obj[l][k] = v;
				});
			}});
		}
		return lang_obj[l];
	} else {
		if('undefined' === typeof(lang_obj[l][t])){
			$.ajax({url: 'ajax.php', data:{'do':'lecho', 'text':t, 'lang':l}, async:false, success:function(data){
				lang_obj[l][t] = data.txt;
			}});
			return lang_obj[l][t];
		} else {
			return lang_obj[l][t];
		}
	}
}





