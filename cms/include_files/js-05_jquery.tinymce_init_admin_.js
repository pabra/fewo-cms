$(function(){
	$('textarea.tinymce').tinymce({
		script_url: 'cms/include_files/tiny_mce/tiny_mce_gzip.php',
		language: $('html').attr('lang'),
		//plugins : 'pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template', 
		//plugins: 'fullscreen,contextmenu,advlink,advimage,table',
		theme : 'advanced',
		//theme_advanced_buttons1 : 'newdocument,|,bold,italic,underline,|,copy,paste,cut,|,bullist,numlist,indent,outdent,undo,redo',
		//theme_advanced_buttons2 : 'link,unlink,|,image,visualaid,fullscreen,code,help',
		//theme_advanced_buttons3 : '',
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,
		theme_advanced_resizing_use_cookie : false,
		onchange_callback : function(inst){
			$(inst.getElement()).change();
		}
	});
});
