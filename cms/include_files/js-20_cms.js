// JS for CMS

var locObj, docLang, noToolTips=false;
/*window.log = function(){
	log.history = log.history || [];   // store logs to an array for reference
	log.history.push(arguments);
	if(this.console){
		console.log( Array.prototype.slice.call(arguments) );
	}
};*/
Object.size = function(obj){
	'use strict';
	var size = 0, key;
	for(key in obj){
		if (obj.hasOwnProperty(key)){
			size++;
		}
	}
	return size;
};
function clog(l){
	'use strict';
	if('undefined' !== typeof(console)){
		console.log(l);
	}
}
function locationSeach2Obj(t){
	'use strict';
	var vars,
		obj = {},
		tmp,
		i;
	if('undefined' === typeof(t)){
		vars = window.location.search;
	} else {
		vars = t.replace(/^#?/, '');
	}
	vars =  vars.replace(/^\??/, '').split('&');
	for(i in vars){
		if(vars.hasOwnProperty(i)){
			if(vars[i] === ''){
				continue;
			}
			tmp = vars[i].split('=');
			obj[decodeURIComponent(tmp[0])] = decodeURIComponent(tmp[1]);
		}
	}
	return obj;
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
function disableToolTips(doWhat){
	if('undefined' === typeof(doWhat)){
		doWhat = 'disable';
	}
	var ttB = $('#ttBox');
	noToolTips = ('disable'===doWhat)? true : noToolTips;
	ttB.fadeOut(150, function(){ ttB.empty().css({top:5, left:5}); });
}
function enableToolTips(){
	noToolTips = false;
}
function titleToTip(){
	'use strict';
	var ttbto,ttbtom, ttbow,ttboh, docW=$(document).width(), docH=$(document).height(), ttB=$('#ttBox'), offX=-10, offY=-15, ttbevpos={}, 
		toPosX=function(evX){
			docW=$(document).width();
			if(evX - $(window).scrollLeft() -7 + ttbow > docW -3){
				evX = docW -3 - ttbow;
			} else {
				evX = evX -7;
			}
			return evX;
		},
		toPosY=function(evY){
			if(evY - $(window).scrollTop() -10 -ttboh < 3){
				evY = evY + 20;
			} else {
				evY = evY -10 - ttboh;
			}
			return evY;
		};
	$('[title]').each(function(k,v){
		$(this).prop({ttBoxTitle:$(this).attr('title')}).removeAttr('title');
		if('undefined' !== typeof($(this).attr('onmouseover')) && -1 !== $(this).attr('onmouseover').indexOf('titleToTip')){
			$(this).removeAttr('onmouseover');
		}
		$(this).mouseenter(function(ev){
			if(true === noToolTips){
				return false;
			}
			var self = $(this), posX, posY;
			if('undefined' === typeof(self.prop('ttBoxMovTime'))){
				self.prop({ttBoxMovTime:0});
			}
			window.clearTimeout(ttbto);
			ttbto = window.setTimeout(function(){
				ttB.css({top:5, left:5}).html(self.prop('ttBoxTitle'));
				ttbow = ttB.outerWidth();
				ttboh = ttB.outerHeight();
				if('undefined' !== typeof(ttbevpos.x) && 'undefined' !== typeof(ttbevpos.y)){
					posX = ttbevpos.x;
					posY = ttbevpos.y;
				} else {
					posX = ev.pageX;
					posY = ev.pageY;
				}
				ttB.css({top:toPosY(posY), left:toPosX(posX)}).fadeIn(150);
				ttB.find('img').load(function(){
					$(this).css({ backgroundImage:'none' });
				});
				//$('#ttBox').html(self.prop('ttBoxTitle')).css({top:posY -$('#ttBox').outerHeight() +offY, left:posX +offX}).fadeIn(150);
				//clog(ev.pageX);
			}, 300);
		}).mouseleave(function(ev){
			ttbevpos = {};
			window.clearTimeout(ttbto);
			window.clearTimeout(ttbtom);
			ttB.fadeOut(150, function(){ ttB.empty().css({top:5, left:5}); });
		}).mousemove(function(ev){
			var self = $(this);
			if($.now() > self.prop('ttBoxMovTime')){
				ttbevpos = {x:ev.pageX, y:ev.pageY};
				self.prop({ttBoxMovTime: ($.now() + 25) });
				if('none' !== ttB.css('display')){
					ttB.css({top:toPosY(ev.pageY), left:toPosX(ev.pageX)});
				}
			}
		});
	});
}
function show_info(txt, title, callback, txt_wrap, buttons){
	'use strict';
	if('undefined' === typeof(title)){
		title = 'Information';
	}
	if('undefined' === typeof(txt_wrap) || false !== txt_wrap){
		txt = '<p><span class="ui-icon ui-icon-info" style="position:absolute;top:7px;left:0px;"></span>'+txt+'</p>';
	}
	if('undefined' === typeof(buttons)){
		buttons = { Ok:function(){ $(this).dialog('close'); } };
	}
	$('#dialog').html(txt).dialog({modal:true,title:title,minHeight:150,minWidth:200,maxHeight:450,maxWidth:650,buttons:buttons});
	if('function' === typeof(callback)){
		$('#dialog').one('dialogclose', callback);
	}
}
function show_warning(txt, title, callback){
	'use strict';
	if('undefined' === typeof(title)){
		title = 'Warning';
	}
	txt = '<p style="color:maroon;"><span class="ui-icon ui-icon-alert" style="position:absolute;top:7px;left:0px;"></span>'+txt+'</p>';
	show_info(txt, title, callback, false);
}
function show_confirm(txt, title, callback){
	'use strict';
	$('#dialog').prop({choice:false});
	var buttons = {
		Ok: function(){ $('#dialog').prop({choice:true}); $(this).dialog('close'); },
		No: function(){ $(this).dialog('close'); }
		};
	if('undefined' === typeof(title)){
		title = '?';
	}
	txt = '<p><span class="ui-icon ui-icon-help" style="position:absolute;top:7px;left:0px;"></span>'+txt+'</p>';
	show_info(txt, title, callback, false, buttons);
}

$(function(){
	'use strict';
	docLang = $('html').attr('lang');
	locObj = locationSeach2Obj();
	/*$('input[type=file]').bind('change', function() {
		if('undefined' !== typeof(this.files)){
			alert(this.files[0].size);
		}
	});*/
	$('input.email').change(function(){
		$(this).parents('.form_row').removeClass('mismatch');
		if(false === check_email_address($(this).val())){
			$(this).parents('.form_row').addClass('mismatch');
		}
	});
	$('body').append('<div id="ttBox"></div><div id="dialog"></div>');
	titleToTip();
});

// END
