var cal_handler_enter = function(){
	'use strict';
	//$(this).addClass('cal_select');
	if(0!==select_begin && 0===select_end){
		select_range(select_begin, id2int($(this).attr('id')), $(this).parents('.res_cal').first().attr('id').replace(/^res_cal_/, ''));
	}
},
cal_handler_leave = function(){
	'use strict';
	//$(this).removeClass('cal_select');
	if(0!==select_begin && 0===select_end){
		$('div.res_cal div.cal_select').removeClass('cal_select');
	}
},
cal_handler_click = function(){
	'use strict';
	var d_match = id2int($(this).attr('id')),
		cal_name = $(this).parents('.res_cal').first().attr('id').replace(/^res_cal_/, ''),
		d_arr = $(this).attr('id').match(/_d_(([0-9]{2})-([0-9]{2})-([0-9]{2}))$/),
		d_date = new Date(('20'+d_arr[2]), (parseInt(d_arr[3], 10)-1), d_arr[4]);
	if(0 === select_begin){
		$(this).addClass('cal_select_begin');
		select_begin = d_match;
		if(1 === $('#reservation_from_'+cal_name).length){
			$('#reservation_from_'+cal_name).datepicker('setDate', d_date);
			$('#reservation_to_'+cal_name).val('');
			//clog(d_arr);
		}
	} else if(0 === select_end){
		$(this).addClass('cal_select_end');
		select_end = d_match;
		if(1 === $('#reservation_to_'+cal_name).length){
			if(select_end < select_begin){
				$('#reservation_to_'+cal_name).datepicker('setDate', $('#reservation_from_'+cal_name).datepicker('getDate') );
				$('#reservation_from_'+cal_name).datepicker('setDate', d_date);
			} else {
				$('#reservation_to_'+cal_name).datepicker('setDate', d_date);
			}
			//clog(d_arr);
			clog(select_begin);
			clog(select_end);
			select_begin=0;
			select_end=0;
			$('div.res_cal .cal_select').removeClass('cal_select cal_select_begin cal_select_end');
		} else {
			$('div.res_cal div.content')
				.css({cursor:'default'})
				.unbind('mouseenter', cal_handler_enter)
				.unbind('mouseleave', cal_handler_leave)
				.unbind('click', cal_handler_click);
			$('.add_timespan_buttonset').show('slide',{direction:'up'},300);
		}
	}
}, select_begin = 0, select_end = 0;
function id2int(s){
	'use strict';
	return parseInt(s.replace(/^[a-zA-Z0-9_-]+_d_/, '').replace(/-/g, ''), 10);
}
function select_range(r_beg, r_end, cal_name){
	'use strict';
	var tmp, tmpStr;
	if(r_end < r_beg){
		tmp = r_beg;
		r_beg = r_end;
		r_end = tmp;
	}
	for(tmp = r_beg; tmp <= r_end; tmp++){
		tmp = ((''+tmp).substr(4)==='32')? tmp+69 : tmp;
		tmpStr = ''+tmp;
		tmpStr = cal_name+'_d_'+tmpStr.substr(0,2)+'-'+tmpStr.substr(2,2)+'-'+tmpStr.substr(4,2);
		$('#'+tmpStr).addClass('cal_select');
	}
}
function let_select(){
	'use strict';
	select_begin = 0;
	select_end = 0;
	$('div.res_cal div.content')
		.addClass('cal_selectable')
		.bind('mouseenter', cal_handler_enter)
		.bind('mouseleave', cal_handler_leave)
		.bind('click', cal_handler_click);
}
function bind_nav_buttons()
{
	$('.cal_nav.buttonset').each(function(k,v){
		var this_idx = $(this).attr('id').replace('cal_idx_', ''),
			this_year = strToDate( $('.res_cal[class~="conf_idx:'+this_idx+'"] .content:first').attr('id').match(/_d_([0-9-]+)$/)[1] ).getFullYear(),
			disable_prev = (this_year <= res_cal_year_from)? true : false,
			disable_next = (this_year >= res_cal_year_to)? true : false,
			button_prev = $(this).find('.cal_button_prevy'),
			button_next = $(this).find('.cal_button_nexty'),
			button_this = $(this).find('.cal_button_thisy');
		//clog(this_year);
		button_prev.prop({gotoYear: (this_year -1)});
		button_next.prop({gotoYear: (this_year +1)});
		button_this.prop({gotoYear: res_cal_current_year});
		if(!$(this).hasClass('ui-buttonset')){
			button_prev.button({
				icons:{ primary: 'ui-icon-circle-triangle-w' },
				text: false,
				disabled: disable_prev
			});
			button_next.button({
				icons:{ primary: 'ui-icon-circle-triangle-e' },
				text: false,
				disabled: disable_next
			});
			button_this.button({
				icons:{ primary: 'ui-icon-circle-triangle-s' }/*,
				label: this_year*/
			});
			//$(this).buttonset();
		}
		button_prev.button('option',{disabled:disable_prev});
		button_next.button('option',{disabled:disable_next});
		button_this.button('option',{disabled:false, label:this_year});
		$(this).find('.cal_button').unbind('click').click(function(){
			var gotoYear = $(this).prop('gotoYear'),
				direction = (gotoYear < this_year)? {dOut:'right',dIn:'left'} : {dIn:'right',dOut:'left'};
			if(gotoYear !== this_year && gotoYear >= res_cal_year_from && gotoYear <= res_cal_year_to){
				//clog('goto: '+gotoYear);
				$(this).parent().find('.cal_button').removeClass('ui-state-hover').button('option',{disabled:true});
				disableToolTips('hide');
				$.get('?res_cal:'+this_idx+':'+gotoYear+':'+docLang, function(data){
					//data = $(data);
					//data.find('.res_cal').css({display:'hidden'});
					//clog(data);
					$('.res_cal[class~="conf_idx:'+this_idx+'"]').replaceWith( data );
					/*$('.res_cal[class~="conf_idx:'+this_idx+'"]')
						.wrap('<div class="wrap" />')
						.parent()
						.toggle('blind',{direction:'horizontal'},500, function(){
							//clog(this);
							$(this).children().replaceWith(data);
							$(this).toggle('blind',{direction:'horizontal'},500, function(){
								//clog(this);
								$(this).children().unwrap();
								bind_nav_buttons();
								titleToTip();
								let_select();
							});
						});*/
					/*$('.res_cal[class~="conf_idx:'+this_idx+'"]').toggle('slide',{direction:direction},300, function(){
						$(this).children().replaceWith( $(data).children() );
						//clog(this);
						$(this).toggle('slide',{},300);
					});*/
					bind_nav_buttons();
					titleToTip();
					let_select();
				});
			}
		});
	});
}
function strToDate(s){
	'use strict';
	var parsed = s.match(/([0-9]{1,4})([-.\/])([01]?[0-9])\2([0-9]{1,4})/),
		nDate = false,
		fullYear = function(y){
			if(y < 40){
				return y + 2000;
			}
			if(y < 100){
				return y + 1900;
			}
			return y;
		};
	if(!parsed){
		return false;
	}
	parsed[1] = parseInt(parsed[1], 10);
	parsed[3] = parseInt(parsed[3], 10);
	parsed[4] = parseInt(parsed[4], 10);
	if('.' === parsed[2]){
		parsed[4] = fullYear(parsed[4]);
		nDate = new Date(parsed[4], (parsed[3] -1), parsed[1]);
		if(nDate.getDate() === parsed[1] &&
				(nDate.getMonth() +1) === parsed[3] &&
				nDate.getFullYear() === parsed[4]){
			return nDate;
		} else {
			return false;
		}
	} else {
		parsed[1] = fullYear(parsed[1]);
		nDate = new Date(parsed[1], (parsed[3] -1), parsed[4]);
		if(nDate.getDate() === parsed[4] &&
				(nDate.getMonth() +1) === parsed[3] &&
				nDate.getFullYear() === parsed[1]){
			return nDate;
		} else {
			return false;
		}
	}
}
function dateToStr(d, f){
	'use strict';
	var i = 0, l, c, o = '';
	if('function' !== typeof(d.getDate)){
		return false;
	}
	if('undefined' === typeof(f)){
		f = 'y-m-d';
	}
	l = f.length;
	for(i=0; i<l; i++){
		c = f.substr(i, 1);
		switch(c){
			case 'y':
				o += (''+d.getFullYear()).substr(-2);
				break;
			case 'Y':
				o += (''+d.getFullYear());
				break;
			case 'm':
				o += ('0'+(d.getMonth() +1)).substr(-2);
				break;
			case 'd':
				o += ('0'+ d.getDate()).substr(-2);
				break;
			default:
				o += c;
				break;
		}
	}
	return o;
}
function walkDays(d, w, f){
	'use strict';
	if('string' === typeof(d)){
		d = strToDate(d);
	}
	if('function' !== typeof(d.getDate)){
		return false;
	}
	if('undefined' === typeof(w)){
		w = 1;
	} else {
		w = parseInt(w, 10);
	}
	d.setDate(d.getDate() + w);
	if('undefined' !== typeof(f) && 'oDate' === f){
		return d;
	} else {
		return dateToStr(d, f);
	}
}
$(function(){
	'use strict';
	if(0 < $('.res_cal').length){
		bind_nav_buttons();
		let_select();
	}
	if(0 !== $('.res_form').length){
		$.datepicker.setDefaults( $.datepicker.regional[ "en-GB" ] );
		if('de' === docLang){
			$.datepicker.setDefaults( $.datepicker.regional.de );
		}
		$('.datepicker').datepicker({
			onSelect:function(d,dObj){
				var cal_name = $(this).parents('form.res_form').attr('id').replace(/^res_form_/, '');
				clog(cal_name);
			}
		});
	}
});
