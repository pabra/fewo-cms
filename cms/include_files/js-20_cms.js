// JS for CMS

/*window.log = function(){
	log.history = log.history || [];   // store logs to an array for reference
	log.history.push(arguments);
	if(this.console){
		console.log( Array.prototype.slice.call(arguments) );
	}
};*/
Object.size = function(obj){
	var size = 0, key;
	for(key in obj){
		if (obj.hasOwnProperty(key)) size++;
	}
	return size;
};
function clog(l){
	if('undefined' !== typeof(console)){
		console.log(l);
	}
}

function check_email_address(email)
{
	'use strict';
	var email_array, local_array, domain_array, i;
	// First, we check that there's one @ symbol, and that the lengths are right
	if(!email.match(/^[^@]{1,64}@[^@]{1,255}$/)){
		// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	email_array = email.split('@');
	local_array = email_array[0].split('.');
	for(i=0; i<local_array.length; i++){
		if(!local_array[i].match(/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/)){
			return false;
		}
	}
	if(!email_array[1].match(/^\[?[0-9\.]+\]?$/)){ // Check if domain is IP. If not, it should be valid domain name
		domain_array = email_array[1].split('.');
		if(domain_array.length < 2){
			return false; // Not enough parts to domain
		}
		for(i=0; i<domain_array.length; i++){
			if(!domain_array[i].match(/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/)){
				return false;
			}
		}
	}
	return true;
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
