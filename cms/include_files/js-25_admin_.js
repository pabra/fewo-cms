/*! ADMIN JS */
var admin_keep_alive_off = true;
$(function(){
	"use strict";
	//alert('admin');
	$('input[type=text], input[type=password]').focus(function(){
		var self = $(this);
		window.setTimeout(function(){ self.select(); }, 5);
	})
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
		clog(form_post_data);
		clog($(this).attr('action').match(/do=[a-zA-Z0-9_-]+/));
		$.post('ajax.php?'+$(this).attr('action').match(/do=[a-zA-Z0-9_-]+/), form_post_data, function(data){
			clog(data);
			form_fields.prop({disabled:false});
			if(false === data.status){
				$('#dialog').attr('title', 'Fehler').text(data.msg).dialog({
					modal:true,
					buttons: {
						Ok: function() {
							$( this ).dialog( "close" );
						}
					}
					});
			} else {
				clog('goto: '+form.find('[name=req_page]').val());
				clog(form);
				window.location = '?'+form.find('[name=req_page]').val();
			}
		});
		//$('#dialog').dialog( "destroy" );
		//$('#dialog').empty().attr({title:'Der Titel'}).html('huhu');
		//$('#dialog').dialog({modal:true, buttons: { 'Ok' : function() {
		//			$( this ).dialog( "close" );
		//		} }});
		//$('#dialog').dialog({
		//	modal: true,
		//	buttons: {
		//		'Ok - mach': function() {
		//			$( this ).dialog( "close" );
		//		}
		//	}
		//});
		//$('#progressbar').progressbar({value:35});
	});
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
	//$('fieldset.sortable').disableSelection();
	//$('fieldset.sortable input').click(function(){ if(false === $(this).is(':focus')){ $(this).focus(); } });
	$('form.config div.idx_group').mouseenter(function(ev){
		$(this).find('.idx_nav:not(.move_up):not(.move_down)').show('slide',{direction:'down'},300);
		if(1 === $(this).prev('.idx_group').length){
			$(this).find('.idx_nav.move_up').show('slide',{direction:'down'},300);
		}
		if(1 === $(this).next('.idx_group').length){
			$(this).find('.idx_nav.move_down').show('slide',{direction:'down'},300);
		}
	}).mouseleave(function(ev){
		$(this).find('.idx_nav:visible').hide('slide',{direction:'down'},300);
	});
	/*$('div.idx_group:not(:first)').mouseenter(function(ev){
		$(this).find('.idx_nav.move_up').show('slide',{direction:'down'},300);
	});
	$('div.idx_group:not(:last)').mouseenter(function(ev){
		$(this).find('.idx_nav.move_down').show('slide',{direction:'down'},300);
	});*/
	$('form.config .idx_nav li').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	);
	$('form.config .idx_nav.move_up span').click(function(){
		$(this).parents('.idx_group').insertBefore($(this).parents('.idx_group').prev('.idx_group'));
		//$(this).parents('.idx_group').find('.idx_nav:visible').hide('slide',{direction:'down'},300);
		idx_group_sorted($(this).parents('fieldset'));
	});
	$('form.config .idx_nav.move_down span').click(function(){
		$(this).parents('.idx_group').insertAfter($(this).parents('.idx_group').next('.idx_group'));
		//$(this).parents('.idx_group').find('.idx_nav:visible').hide('slide',{direction:'down'},300);
		idx_group_sorted($(this).parents('fieldset'));
	});
	$('form.config .idx_nav.delete span').click(function(){
		var input = $(this).parents('fieldset').find('.delete_index'),
			group = $(this).parents('.idx_group'),
			index_key = group.prop('id').split(':')[1],
			fieldset = $(this).parents('fieldset');
		//$(this).parents('.idx_group').find('.idx_nav:visible').hide('slide',{direction:'down'},300);
		idx_group_sorted($(this).parents('fieldset'));
		if(input.val()){
			input.val(input.val()+':'+index_key);
		} else {
			input.val(index_key);
		}
		group.remove();
		input.change();
		idx_group_sorted(fieldset);
	});
	$('form.config input:not(.notrim)').change(function(){
		$(this).val($.trim($(this).val()));
	});
	$('form.config select').each(function(k, v){
		$(v).prop({defaultValue: $(v).val()});
		//clog($(v));
	});
	$('form.config .ui-icon-arrowreturnthick-1-s').click(function(){
		$(this).parents('.form_row').find('input, textarea, select').each(function(k,v){
			if('checkbox' === $(v).prop('type')){
				$(v).prop({checked: $(v).prop('defaultChecked')});
			} else {
				$(v).val($(v).prop('defaultValue')).change();
				
			}
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
			var send_el = $(v).find('input, textarea, select').first();
			clog(v);
			if($(v).hasClass('select_more')){
				send_el = $(v).find('input.select_more_value').first();
			} else if($(v).hasClass('no_parent')){
				send_el = $(v);
			}
			send_obj[send_el.prop('name')] = send_el.val();
		});
		//$.each
		clog(send_obj.length);
		if(0 === Object.size(send_obj)){
			//$('#dialog').attr({title:'nothing to do'}).text('Here is nothing to do.').dialog({modal:true, buttons:{ok:function(){$(this).dialog('close');}}});
		} else {
			clog(send_obj);
			//$.post('ajax.php', send_obj);
		}
	});
	$('form.config').on('reset', function(){
		clog('reset');
		//var self = $(this);
		window.setTimeout(function(){
			$('form.config input, form.config textarea, form.config select').each(function(){
				//clog($(this));
				if($(this).hasClass('select_more')){
					$(this).change();
				} else {
					$(this).change();
				}
			});
		}, 50);
	});
	$('form.config input, form.config textarea, form.config select').change(function(){
		var match_regex = '', multi_var = [];
		clog('val:'+$(this).val()+' - default: '+$(this).prop('defaultValue'));
		$(this).removeClass('changed');
		$(this).parents('.form_row').removeClass('mismatch changed');
		if($(this).hasClass('must') && !$(this).val()){
			clog('must');
			$(this).parents('.form_row').addClass('mismatch');
			return;
		}
		if($(this).hasClass('match')){
			match_regex = new RegExp(decodeURIComponent($(this).prop('className').match(/match_([a-zA-Z0-9%_-]+)/)[1]));
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
				return
			}
		}
		if('checkbox' === $(this).prop('type')){
			$(this).parents('.form_row').find('input[type=checkbox]').each(function(k, v){
				if(true === $(v).prop('checked')){
					multi_var.push($(v).val());
				}
			});
			$(this).parents('.form_row').find('input.select_more_value').val(multi_var.join(':')).change();
			return;
		}
		if($(this).val() !== $(this).prop('defaultValue')){
			//$(this).addClass('changed');
			clog('add changed');
			if($(this).hasClass('no_parent')){
				$(this).addClass('changed');
			} else {
				$(this).parents('.form_row').addClass('changed');
			}
		}
	});
	if('undefined' !== typeof(admin_keep_alive)){
		var jetzt = new Date().getTime() / 1000, 
			admin_keep_alive_off = jetzt + admin_keep_alive ;
		//jetzt = jetzt.getTime() / 1000;
		$('#admin_head_bar').append('<div id="keep_alive_timer"></div>');
		$('#keep_alive_timer').text('0:00');
		window.setInterval(function(){
			var jetzt = new Date().getTime() / 1000,
				rest = admin_keep_alive_off - jetzt,
				rest_min = Math.floor(rest/60),
				rest_sec = Math.floor(rest - (rest_min * 60)),
				show = '';
			if(rest_sec < 10){
				rest_sec = '0'+rest_sec;
			}
			show = rest_min+':'+rest_sec;
			if(rest < 0){
				show = '0:00';
				if(rest < -5){
					window.location.reload();
				}
			}
			$('#keep_alive_timer').text(show);
		}, 500);
	}
});
function idx_group_sorted(fieldset){
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




