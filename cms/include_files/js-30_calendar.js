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
function js_res_cal(){
	'use strict';
	$('.res_cal').each(function(k,v){
		var oCal = $(this),
			cal_name = $(this).attr('id').replace('res_cal_', ''),
			cal_idx = $(this).attr('class').match(/conf_idx:([a-zA-Z0-9]+)/)[1],
			cal_type = ('|'+(oCal.attr('class').split(' ').join('|'))+'|').match(/\|t([0-9])\|/)[1],
			monthNames = (-1 === $.inArray('short_month_names', reservations[cal_name].settings))? 'monthNames' : 'monthNamesShort',
			monthNameClass = ('monthNamesShort' === monthNames)? 'month_short' : 'month_long',
			year = 2012,
			month = 1,
			day = 1,
			oMonth = !0,
			oDay = !0,
			oQdRow = !0,
			oWeekRow = !0,
			oDate = !0,
			insertDays = !0,
			i = !0,
			regioDate = $.datepicker.regional[docLang],
			regioMonth = regioDate[monthNames],
			insertEmptyDay = function(c){
				var i = 0, tmp = '';
				c = parseInt(c, 10);
				if(0 === c){
					return;
				}
				oDay = $('<div/>').append( $('<div/>').html('&nbsp;').addClass('float with_font cal_day empty') ).html();
				for(i=0; i<c; i++){
					tmp += oDay ;
				}
				return tmp;
			},
			dayFormat = function(){
				var thisDay = strToDate(''+year+'-'+month+'-'+day),
					thisDayStr = dateToStr(thisDay);
				oDay = $('<div/>').addClass('float with_font cal_day').html('&nbsp;');
				if(false === thisDay){
					oDay.addClass('empty');
				} else {
					if(thisDay.getDay() === 0 || thisDay.getDay() === 6){
						oDay.addClass('is_we');
					}
					if(-1 !== $.inArray('with_headline', reservations[cal_name].settings) && cal_type === '1'){
						oDay.html('&nbsp;');
					} else {
						oDay.text( dateToStr(thisDay, 'd') );
					}
					oDay.attr({
							title: $.datepicker.formatDate('DD, dd. MM yy', thisDay ),
							id:cal_name+'_d_'+thisDayStr
						})
						.addClass('content d_'+thisDayStr);
				}
				//oDay.click(function(){ alert($(this).text()); });
				return oDay;
			};
		oCal.empty();
		if('3' === cal_type){
			for(month=1; month<=12; month++){
				if(1 === month %3){
					oQdRow = $('<div/>').addClass('row');
				}
				oMonth = $('<div/>').addClass('month').append( $('<div/>').addClass('month_head').text(regioMonth[month-1]) );
				if(-1 !== $.inArray('with_headline', reservations[cal_name].settings)){
					for(i=1; i<=7; i++){
						oMonth.append( $('<div/>').text( regioDate.dayNamesMin[(i%7 === 0)? 0 : i%7] ).addClass('float with_font'+((i%7 === 6 || i%7 === 0)? ' is_we' : '')) );
					}
				}
				for(day=1; day<=31; day++){
					oDate = strToDate(''+year+'-'+month+'-'+day);
					if(1 === day){
						insertDays = strToDate(''+year+'-'+month+'-'+day).getDay() -1;
						insertDays = (-1 === insertDays)? 6 : insertDays;
						oWeekRow = $('<div/>').addClass('row');
						oWeekRow.append( insertEmptyDay( insertDays ) );
					} else if(1 === strToDate(''+year+'-'+month+'-'+day).getDay()){
						oWeekRow = $('<div/>').addClass('row');
					}
					if(day < 28 || false !== strToDate(''+year+'-'+month+'-'+day)){
						oWeekRow.append( dayFormat() );
					} else {
					
					}
				}
				if(0 === month %3){
					oCal.append( oQdRow );
				}
			}
		} else {
			if(-1 !== $.inArray('with_headline', reservations[cal_name].settings)){
				if('1' === cal_type){
					oMonth = $('<div/>').addClass('row headline');
					oMonth.append( $('<div/>').addClass('float '+monthNameClass) );
					for(day=1; day<=31; day++){
						oMonth.append( $('<div/>').text( ('0'+day).match(/[0-9]{2}$/) ).addClass('float with_font') );
					}
					oCal.append(oMonth);
				} else if('2' === cal_type){
					oMonth = $('<div/>').addClass('row headline');
					oMonth.append( $('<div/>').addClass('float '+monthNameClass) );
					for(i=1; i<=37; i++){
						oMonth.append( $('<div/>').text( regioDate.dayNamesMin[(i%7 === 0)? 0 : i%7] ).addClass('float with_font'+((i%7 === 6 || i%7 === 0)? ' is_we' : '')) );
					}
					oCal.append(oMonth);
				}
			}
			for(month=1; month<=12; month++){
				oMonth = $('<div/>').addClass('row');
				oMonth.append( $('<div/>').addClass('float with_font cal_index_month '+monthNameClass).text( regioMonth[month-1] ) );
				for(day=1; day<=31; day++){
					if('2' === cal_type && 1 === day){
						insertDays = strToDate(''+year+'-'+month+'-'+day).getDay() -1;
						insertDays = (-1 === insertDays)? 6 : insertDays;
						oMonth.append( insertEmptyDay( insertDays ) );
					}
					oMonth.append( dayFormat() );
					if('2' === cal_type && 31 === day){
						insertDays = 37 - 31 - insertDays;
						oMonth.append( insertEmptyDay( insertDays ) );
					}
				}
				oCal.append( oMonth );
			}
		}
		$('#js_res_cal').html(oCal);
		titleToTip();
		bind_nav_buttons();
		let_select();
	});
}
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
function bind_nav_buttons(){
	'use strict';
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
	var i = 0, l, c, o = '', tmp='';
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
				tmp = ''+d.getFullYear();
				o += tmp.substr(tmp.length -2);
				break;
			case 'Y':
				o += (''+d.getFullYear());
				break;
			case 'm':
				tmp = '0'+(d.getMonth() +1);
				o += tmp.substr(tmp.length -2);
				break;
			case 'd':
				tmp = '0'+ d.getDate();
				o += tmp.substr(tmp.length -2);
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
function dateKW(d){
	'use strict';
	if('string' === typeof(d)){
		d = strToDate(d);
	}
	if('function' !== typeof(d.getDate)){
		return false;
	}
	var fow = walkDays(d, ((d.getDay() === 0)? 6 : (d.getDay() -1)) *(-1), 'oDate');
	if(fow.getMonth() === 11 && fow.getDate() >= 29){
		return 1;
	}
	var foy = strToDate(d.getFullYear()+'-1-1');
	var fofw = walkDays(foy, ((foy.getDay() === 0)? 6 : (foy.getDay() -1)) *(-1), 'oDate');
	var fkw = (fofw.getDate() === 1 || fofw.getDate() >= 29)? 1 : 0;
	var dbw = (fow.getTime() - fofw.getTime()) /1000 /60/60/24;
	var wbw = dbw / 7;
	return Math.round(wbw + fkw); 
}
$(function(){
	'use strict';
	if(0 < $('.res_cal').length){
		//js_res_cal();
		$('#content').prepend( $('<button/>').text('klick').click(function(){ js_res_cal(); }) );
		//titleToTip();
		//bind_nav_buttons();
		//let_select();
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
