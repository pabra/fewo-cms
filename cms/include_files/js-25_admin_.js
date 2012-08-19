/*! ADMIN JS */
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
		console.log(form_post_data);
		console.log($(this).attr('action').match(/do=[a-zA-Z0-9_-]+/));
		$.post('ajax.php?'+$(this).attr('action').match(/do=[a-zA-Z0-9_-]+/), form_post_data, function(data){
			console.log(data);
			form_fields.prop({disabled:false});
			if(false === data.status){
				$('#dialog').attr('title', 'Fehler').text(data.msg).dialog({modal:true});
			} else {
				console.log('goto: '+form.find('[name=req_page]').val());
				console.log(form);
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
		update:function(){
			$('fieldset.sortable div.idx_group:even').removeClass('odd').addClass('even');
			$('fieldset.sortable div.idx_group:odd').removeClass('even').addClass('odd');
		},
		change:function(){
			$('fieldset.sortable div.idx_group:even').removeClass('odd').addClass('even');
			$('fieldset.sortable div.idx_group:odd').removeClass('even').addClass('odd');
		}
	});
	$('fieldset.sortable').disableSelection();
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
		//console.log($(v));
	});
	$('form.config input, form textarea, form select').change(function(){
		console.log('val:'+$(this).val()+' - default: '+$(this).prop('defaultValue'));
		if($(this).val() !== $(this).prop('defaultValue')){
			$(this).addClass('changed');
			$(this).parent().addClass('ui-state-highlight');
		} else {
			$(this).removeClass('changed');
			$(this).parent().removeClass('ui-state-highlight');
		}
	});
});

