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
	$('fieldset.sortable').sortable({
		items: "div.idx_group",
		opacity: 0.6,
		axis:'y',
		//forcePlaceholderSize: true,
		//forceHelperSize:true,
		update:function(){
			$('fieldset.sortable div.idx_group:even').removeClass('odd').addClass('even');
			$('fieldset.sortable div.idx_group:odd').removeClass('even').addClass('odd');
		},
		change:function(){
			$('fieldset.sortable div.idx_group:even').removeClass('odd').addClass('even');
			$('fieldset.sortable div.idx_group:odd').removeClass('even').addClass('odd');
		}
	});
	//$('fieldset.sortable').disableSelection();
	//$('fieldset.sortable input').click(function(){ if(false === $(this).is(':focus')){ $(this).focus(); } });
	$('div.idx_group').mouseenter(function(ev){
		$(this).find('.idx_nav').show('slide',{direction:'down'},300);
	}).mouseleave(function(ev){
		$(this).find('.idx_nav').hide('slide',{direction:'down'},300);
	});
	$('.idx_nav li').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	);
	$('form.config input:not(.notrim)').change(function(){
		$(this).val($.trim($(this).val()));
	});
	$('form.config select').each(function(k, v){
		$(v).prop({defaultValue: $(v).val()});
		//clog($(v));
	});
	$('form.config .ui-icon-arrowreturnthick-1-s').click(function(){
		var el = $(this).parents('.form_row').find('input, textarea, select');
		clog(el);
		if(el.hasClass('select_more')){
		
		} else {
			el.val(el.prop('defaultValue'));
			el.change();
		}
	});
	$('form.config').submit(function(ev){
		ev.preventDefault();
		$('form.config .changed').each(function(k, v){
			clog(v);
		});
	});
	$('form.config').on('reset', function(){
		clog('reset');
		//var self = $(this);
		window.setTimeout(function(){
			$('form.config input, form.config textarea, form.config select').each(function(){
				//clog($(this));
				if($(this).hasClass('select_more')){
			
				} else {
					$(this).change();
				}
			});
		}, 50);
	});
	$('form.config input, form.config textarea, form.config select').change(function(){
		var match_regex = '';
		clog('val:'+$(this).val()+' - default: '+$(this).prop('defaultValue'));
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
		if($(this).val() !== $(this).prop('defaultValue')){
			//$(this).addClass('changed');
			$(this).parent().addClass('changed');
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
				if(rest < -15){
					window.location.reload();
				}
			}
			$('#keep_alive_timer').text(show);
		}, 500);
	}
});

