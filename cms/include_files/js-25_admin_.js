/*! ADMIN JS */
$(function(){
	//alert('admin');
	$('form').submit(function(ev){
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
});

