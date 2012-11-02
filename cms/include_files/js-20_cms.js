/*global console, strToDate, dateToStr, walkDays, MD5 */
// JS for CMS

var locObj, docLang, docName, noToolTips=false, lang_obj={};
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
Array.uniqueClean = function(arr){
	'use strict';
	var a=[], i, l=arr.length;
	for(i=0; i<l; i++){
		if((''+arr[i]).length >= 1 && -1 === a.indexOf(arr[i])){
			a.push(arr[i]);
		}
	}
	return a;
};


/******************************************/
var iPadding = {x:5, y:5},
	iHeadHeight = 25,
	iMonthWidth = 100,
	iDayDim = {x:20, y:20},
	matrix = {d:{}}
	;
function calYearMatrix(opt){
	'use strict';
	if('object' !== typeof(opt)){
		opt = {};
	}
	opt.year = (opt.year)? opt.year : new Date().getFullYear();
	//var iPadding = {x:5, y:5},
		//iHeadHeight = 25,
		//iMonthWidth = 100,
		//iDayDim = {x:20, y:20},
		//matrix = {}
		//;
	matrix = {d:{}};
	clog(opt);
	for(var d = strToDate(opt.year+'-1-1'), d_ = dateToStr(strToDate((opt.year +1) +'-1-1')),x,y; dateToStr(d) !== d_; d = walkDays(d, +1, 'oDate')){
		//clog(d);
		//if(d.getFullYear() === 2015){
			//break;
		//}
		y = iPadding.y + iHeadHeight + d.getMonth()*iDayDim.y;
		x = iPadding.x + iMonthWidth + d.getDate()*iDayDim.x;
		if(!matrix['y'+y]){
			matrix['y'+y] = {};
		}
		matrix['y'+y]['x'+x] = {d:d, s:dateToStr(d)};
		matrix.d[dateToStr(d)] = {x:x, y:y, d:d};
		//clog(y + ' - ' + x);
	}
	clog(matrix);
}
function pxToDay(pos){
	'use strict';
	var y=false ,x=false;
	if('object' !== typeof(pos)){
		pos = {x:0, y:0};
	}
	if('string' === typeof(pos.x)){
		pos.x = parseInt(pos.x, 10);
	}
	if('string' === typeof(pos.y)){
		pos.y = parseInt(pos.y, 10);
	}
	$.each(matrix, function(k,v){
		var tmp = parseInt(k.substr(1), 10);
		if(pos.y >= tmp && pos.y <= tmp +iDayDim.y){
			y = tmp;
			$.each(v, function(k, v){
				var tmp = parseInt(k.substr(1), 10);
				if(pos.x >= tmp && pos.x <= tmp +iDayDim.x){
					x = tmp;
					return x;
				}
			});
			return y;
		}
	});
	return {x:x, y:y};
}
$(function(){
	'use strict';
	var t1, t2;
	$('#b1').click(function(){
		t1 = $.now();
		calYearMatrix({year: parseInt($('#i1').val(), 10) });
		t2 = $.now();
		$('#out').append('calMatrix in: '+((t2-t1)/1000)+'s<br/>');
	});
	$('#b2').click(function(){
		t1 = $.now();
		var px = pxToDay({x: parseInt($('#i2').val(), 10), y: parseInt($('#i3').val(), 10)});
		$('#out').append('x: '+px.x+' - y: '+px.y+'<br/>');
		t2 = $.now();
		$('#out').append('pxXY in: '+((t2-t1)/1000)+'s<br/>');
	});
});
/******************************************/

function formInput_addListenser(){
	'use strict';
	$('form .ui-icon-arrowreturnthick-1-s').unbind('click').click(function(){
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
	$('form input[type=password]').unbind('keydown').keydown(function(ev){
		if(13 === ev.which){
			ev.preventDefault();
			//clog($(this));
			$(this).blur();
			//$(this).change();
		}
	});
	$('form input, form textarea, form select').unbind('change').change(function(ev){
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
						//window.alert(lecho('pass_doublecheck_mismatch'));
						window.setTimeout(function(){show_warning(lecho('pass_doublecheck_mismatch'));}, 10);
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
	$('form select').each(function(k, v){
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
	$('form input:not(.notrim)').change(function(){
		var trimmed = $.trim($(this).val());
		if(trimmed !== $(this).val()){
			$(this).val(trimmed).change();
		}
	});
}
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
	'use strict';
	if('undefined' === typeof(doWhat)){
		doWhat = 'disable';
	}
	var ttB = $('#ttBox');
	noToolTips = ('disable'===doWhat)? true : noToolTips;
	ttB.fadeOut(150, function(){ ttB.empty().css({top:5, left:5}); });
}
function enableToolTips(){
	'use strict';
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
	$('[title]').not('.div2info_div').each(function(k,v){
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
	var openSize = function(ev,ui){
		var dia = $(this),
			//diaLen = dia.text().length,
			diaWrap = dia.parent(),
			pos = { my:'center', at:'center', of:window };
		//if(diaWrap.height() -10 > dia.dialog('option','maxHeight') || diaLen > 750){
			//dia.dialog('option',{ height:dia.dialog('option','maxHeight'), width:dia.dialog('option','maxWidth') });
			//dia.dialog('option',{ position:pos });
		//} else if(diaLen < 150){
			//dia.dialog('option', { height:dia.dialog('option','minHeight'), width:dia.dialog('option','minWidth') });
			//dia.dialog('option',{ position:pos });
		//}
		if(diaWrap.height() -5 > dia.dialog('option','maxHeight') || diaWrap.width() -5 > dia.dialog('option','maxWidth')){
			dia.dialog('option',{ height:dia.dialog('option','maxHeight'), width:dia.dialog('option','maxWidth') });
			dia.dialog('option',{ position:pos });
		}
	}, closeSize = function(ev, ui){
		$(this).dialog('option', {width:'auto', height:'auto'});
	};
	if('undefined' === typeof(title)){
		title = 'Information';
	}
	if('undefined' === typeof(txt_wrap) || false !== txt_wrap){
		txt = '<p><span class="ui-icon ui-icon-info" style="position:absolute;top:7px;left:0px;"></span>'+txt+'</p>';
	}
	if('undefined' === typeof(buttons)){
		buttons = { Ok:function(){ $(this).dialog('close'); }, 
			print:function(){
				var nW = window.open();
				nW.document.write( $(this).html() );
				nW.print();
				//nW.close();
			} };
	}
	//$('#dialog').html(txt).dialog({modal:true,title:title,minHeight:160,minWidth:200,maxHeight:450,maxWidth:650,buttons:buttons,open:openSize });
	$('#dialog').html(txt).dialog({modal:true,title:title,minHeight:160,minWidth:200,maxHeight:($(window).height()*0.85),maxWidth:($(window).width()*0.85),buttons:buttons,open:openSize,close:closeSize });
	//$('#dialog').html(txt).dialog({modal:true,title:title,buttons:buttons});
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
function div2form(el){
	'use strict';
	var cpAttr = ['name','id','style','class','method','action'],
		nEl = $('<form/>');
	el.removeClass('form');
	$.each(cpAttr, function(k,v){
		var a = el.attr(v);
		if(a){
			nEl.attr(v, a);
		}
	});
	nEl.html( el.html() ).show();
	if(nEl.hasClass('ajaxSubmit')){
		nEl.submit(function(ev){
			ev.preventDefault();
		});
	}
	el.replaceWith(nEl);
}
function reloadDocument(opt){
	'use strict';
	var loc = window.location.href.replace(/#.*/, '').replace(/&_=[0-9]+/, ''),
		nC;
	if('undefined' === typeof(opt)){
		opt = {cut:'hash'};
	}
	nC = (opt.noCache || opt.nC)? '&_='+$.now() : '';
	if(opt.cut === 'search'){
		window.location = loc.replace(/&.*/, '') +nC;
		return 1;
	} else if(opt.cut instanceof(Array)){
		loc = [];
		$.each(locObj, function(k,v){
			if(-1 === $.inArray(k, opt.cut)){
				if('undefined' === v){
					loc.push( ''+k );
				} else {
					loc.push( k+'='+v );
				}
			}
		});
		window.location = '?' + loc.join('&') +nC;
		return 1;
	} else {
		window.location = loc +nC;
		return 1;
	}
}
function div2info(){
	'use strict';
	$('.div2info').click(function(ev){
		var self = $(this),
			div = $('#'+self.attr('id').replace(/^link_/, 'div_'));
		ev.preventDefault();
		show_info(div.html(), div.attr('title'));
	});
}
function formFieldCheck(fld){
	'use strict';
	var row = fld.parents('.form_row'),
		fail = function(){
			row.addClass('mismatch');
		};
	row.removeClass('mismatch');
	fld.val( $.trim(fld.val()) );
	if(fld.hasClass('email') && false === check_email_address(fld.val()) ){
		//row.addClass('mismatch');
		fail();
	}
	if(fld.hasClass('must')){
		if(fld.attr('type') === 'checkbox' && false === fld.prop('checked')){
			fail();
		} else if(fld.val().length === 0){
			fail();
		}
	}
}
$(function(){
	'use strict';
	docLang = $('html').attr('lang');
	locObj = locationSeach2Obj();
	docName = location.search.match(/^\??([^&]+)/)[1];
	$('div.form').each(function(){
		div2form($(this));
	});
	if(docName !== 'admin' && $('form').length > 0){
		formInput_addListenser();
	}
	/*$('form .form_row.must input').each(function(k,v){
		if(false === $(this).hasClass('must')){
			$(this).addClass('must');
		}
	});*/
	/*$('input[type=file]').bind('change', function() {
		if('undefined' !== typeof(this.files)){
			alert(this.files[0].size);
		}
	});*/
	/*$('input.email').change(function(){
		//clog($(this));
		$(this).parents('.form_row').removeClass('mismatch');
		if(false === check_email_address($(this).val())){
			$(this).parents('.form_row').addClass('mismatch');
		}
	});*/
	/*$('input, select, textarea').change(function(){
		formFieldCheck($(this));
	});*/
	$('input[type=text], input[type=password]').focus(function(){
		var self = $(this);
		window.setTimeout(function(){ self.select(); }, 5);
	});
	//$('.must label').each(function(k,v){
		//$(v).text( $(v).text() + '*' );
	//});
	$('.input.captcha').each(function(){
		var self = $(this),
			pForm = self.parents('form'),
			cRow = pForm.find('.form_row.captcha');
		cRow.hide();
		pForm.one('change', 'input, select, textarea', function(){
			$.ajax('ajax.php', {data:{'do':'captcha', what:'get'}, cache:false, success:function(data){
				if(data.status === true){
					//clog(data);
					//self.append(data.txt[0].replace(/(<span)/, '$1 style="display:none;"'), data.txt[2]).find('span:first').show(300);
					self.append(data.txt[0], data.txt[2]);
					cRow.show('slide', 300);
				}
			}});
		});
	});
	$('body').append('<div id="ttBox"></div><div id="dialog"></div>');
	titleToTip();
	div2info();
});

// END
