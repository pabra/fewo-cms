// JS for CMS

/*window.log = function(){
	log.history = log.history || [];   // store logs to an array for reference
	log.history.push(arguments);
	if(this.console){
		console.log( Array.prototype.slice.call(arguments) );
	}
};*/

function clog(l){
	if('undefined' !== typeof(console)){
		console.log(l);
	}
}

function test(given_var){
	var own_var = 1;
	return ' '+own_var+' '+given_var+' ';
}

$(function(){
	//alert('cms');
	//alert('Class of <html>: '+$('html').attr('class'));
	$('input[type=file]').bind('change', function() {
		//this.files[0].size gets the size of your file.
		alert(this.files[0].size);
	});
	$('body').append('<div id="dialog"></div>');
	/*$('form').find('textarea,select,input[type!=submit],option')
		.addClass('ui-corner-all ui-button ui-state-active ui-widget')
		.css({textAlign:'left', outlineWidth:'0px', padding:'0.4em 1em', width:'155px'})
		.mouseenter(function(){
			$(this).removeClass('ui-state-active').addClass('ui-state-default');
		}).mouseleave(function(){
			$(this).removeClass('ui-state-default').addClass('ui-state-active');
		}).focusin(function(){
			$(this).removeClass('ui-state-active').addClass('ui-state-hover');
		}).focusout(function(){
			$(this).removeClass('ui-state-hover').addClass('ui-state-active');
		});
	$('form').find('input[type=submit],input[type=reset]').button();*/
});

// END
