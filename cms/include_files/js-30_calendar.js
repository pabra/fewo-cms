/*global clog, docLang, reservations, titleToTip, lecho, res_cal_current_year*/
var resCalHandlerEnter, resCalHandlerLeave, resCalHandlerClick;

function js_res_cal(options){
	'use strict';
	if('undefined' === typeof(options)){
		options = {};
	}
	$('.res_cal').each(function(k,v){
		var oCal = $(this),
			cal_name = $(this).attr('id').replace('res_cal_', ''),
			cal_idx = $(this).attr('class').match(/conf_idx:([a-zA-Z0-9]+)/)[1],
			cal_type = ('|'+(oCal.attr('class').split(' ').join('|'))+'|').match(/\|t([0-9])\|/)[1],
			monthNames = (-1 === $.inArray('short_month_names', reservations[cal_name].settings))? 'monthNames' : 'monthNamesShort',
			resHalfDay = (-1 !== $.inArray('first_last_resday_half', reservations[cal_name].settings))? true : false,
			noDayTitle = (-1 !== $.inArray('no_day_title', reservations[cal_name].settings))? true : false,
			monthNameClass = ('monthNamesShort' === monthNames)? 'month_short' : 'month_long',
			month = 1,
			day = 1,
			year,oMonth,oDay,oQdRow,oWeekRow,oDate,insertDays,i,tmp,
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
					oDay.attr({id:cal_name+'_d_'+thisDayStr})
						.addClass('content d_'+thisDayStr);
					if(false === noDayTitle){
						oDay.attr({title: $.datepicker.formatDate('DD, dd. MM yy', thisDay, regioDate )});
					}
				}
				//oDay.click(function(){ alert($(this).text()); });
				return oDay;
			};
		if(options.cal && cal_name !== options.cal){
			return;
		}
		year = (options.year)? parseInt(options.year, 10) : res_cal_current_year;
		if(year === reservations[cal_name].showYear){
			$('#res_nav_'+cal_name+' :button').button('enable'); 
			return;
		}
		reservations[cal_name].showYear = year;
		oCal.empty();
		if('3' === cal_type){
			for(month=1; month<=12; month++){
				if(1 === month %3){
					oQdRow = $('<div/>').addClass('row');
				}
				oMonth = $('<div/>').addClass('month').append( $('<div/>').addClass('month_head').text(regioMonth[month-1]) );
				if(-1 !== $.inArray('with_headline', reservations[cal_name].settings)){
					oWeekRow = $('<div/>').addClass('row headline');
					if(-1 !== $.inArray('kw_t3', reservations[cal_name].settings)){
						oWeekRow.append( $('<div/>').text( regioDate.weekHeader ).addClass('float with_font')  );
					}
					for(i=1; i<=7; i++){
						oWeekRow.append( $('<div/>').text( regioDate.dayNamesMin[(i%7 === 0)? 0 : i%7] ).addClass('float with_font'+((i%7 === 6 || i%7 === 0)? ' is_we' : '')) );
					}
					oMonth.append( oWeekRow );
				}
				for(day=1; day<=31; day++){
					oDate = strToDate(''+year+'-'+month+'-'+day);
					if(1 === day || 1 === oDate.getDay()){
						oWeekRow = $('<div/>').addClass('row');
						if(-1 !== $.inArray('kw_t3', reservations[cal_name].settings)){
							tmp = '0'+dateKW(oDate);
							oWeekRow.append( $('<div/>').addClass('float with_font cal_kw').text( tmp.substr( tmp.length -2) ) );
						}
					}
					if(1 === day){
						insertDays = oDate.getDay() -1;
						insertDays = (-1 === insertDays)? 6 : insertDays;
						oWeekRow.append( insertEmptyDay( insertDays ) );
					}
					oWeekRow.append( dayFormat() );
					if(oDate.getDay() === 0){
						//oWeekRow.append( insertEmptyDay( 15 ));
						oMonth.append( oWeekRow );
					}
					if(day > 27 && oDate.getMonth() !== walkDays(oDate, 1, 'oDate').getMonth()){
						//clog( year+'-'+month+'-'+day + ': '+oDate.getDay());
						walkDays(oDate, 1, 'oDate');
						oWeekRow.append( insertEmptyDay( ((oDate.getDay() === 0)? 0 : 7 - oDate.getDay() ) ) );
						oMonth.append( oWeekRow );
						day = 31;
					}
				}
				oQdRow.append( oMonth );
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
		if('string' === typeof(reservations[cal_name].reserved)){
			reservations[cal_name].reserved = reservations[cal_name].reserved.split('|').sort();
		}
		$.each(reservations[cal_name].reserved, function(k,v){
			if(v.match(new RegExp('^'+(year+'').substr(2)+'-'))){
				if(true === resHalfDay && -1 === $.inArray(walkDays(v, -1), reservations[cal_name].reserved)){
					oCal.find('.d_'+v).addClass('res_beg').prepend( $('<div/>').addClass('res_half half_beg') );
				} else if(true === resHalfDay && -1 === $.inArray(walkDays(v, +1), reservations[cal_name].reserved)){
					oCal.find('.d_'+v).addClass('res_end').prepend( $('<div/>').addClass('res_half half_end') );
				} else {
					oCal.find('.d_'+v).addClass('res');
				}
			}
		});
		if(options.withNavBtn){
			oCal.before( $('<span/>')
				.addClass('buttonset resNavBtnSet')
				.attr({id:'res_nav_'+cal_name})
				.append( $('<button/>')
					.addClass('prevYear')
					.click(function(){ $('#res_nav_'+cal_name+' button').button('disable'); $('#ttBox').hide(); window.setTimeout(function(){ js_res_cal({year:reservations[cal_name].showYear -1, cal:cal_name}); },10); })
					.button({icons:{ primary: 'ui-icon-circle-triangle-w' }, text:false, label:lecho('cal_nav_prev')})
				)
				.append( $('<button/>')
					.addClass('thisYear')
					.attr({title:lecho('cal_nav_this')})
					.click(function(){ $('#res_nav_'+cal_name+' button').button('disable'); $('#ttBox').hide(); window.setTimeout(function(){ js_res_cal({year:res_cal_current_year, cal:cal_name}); },10); })
					.button({label:year})
				)
				.append( $('<button/>')
					.addClass('nextYear')
					.click(function(){ $('#res_nav_'+cal_name+' button').button('disable'); $('#ttBox').hide(); window.setTimeout(function(){ js_res_cal({year:reservations[cal_name].showYear +1, cal:cal_name}); },10); })
					.button({icons:{ primary: 'ui-icon-circle-triangle-e' }, text:false, label:lecho('cal_nav_next')})
				));
		} else {
			$('#res_nav_'+cal_name+' .thisYear').button('option', {label:year});
			$('#res_nav_'+cal_name+' button').button('enable');
			resCalShowSelection({cal:cal_name,globalFromTo:true});
		}
		titleToTip();
		resCalLetSelect({cal:cal_name});
	});
}
function resCalResInSel(opt){
	'use strict';
	var resHalfDay = (-1 !== $.inArray('first_last_resday_half', reservations[opt.cal].settings))? true : false;
	if('undefined' === typeof(opt)){
		opt = {};
	}
	if(!opt.cal){
		return false;
	}
	if(false === reservations[opt.cal].sel1 instanceof(Date) || false === reservations[opt.cal].sel2 instanceof(Date)){
		return false;
	}
}
function resCalShowSelection(opt){
	'use strict';
	var tmp, tmpTo;
	if('undefined' === typeof(opt)){
		opt = {};
	}
	if(!opt.cal || ((!opt.from || !opt.to) && true !== opt.globalFromTo)){
		return false;
	}
	if(true === opt.globalFromTo){
		opt.from = (reservations[opt.cal].sel1)? reservations[opt.cal].sel1 : reservations[opt.cal].sel2;
		opt.to   = (reservations[opt.cal].sel2)? reservations[opt.cal].sel2 : reservations[opt.cal].sel1;
	}
	if('string' === typeof(opt.from)){
		opt.from = strToDate(opt.from);
	}
	if('string' === typeof(opt.to)){
		opt.to = strToDate(opt.to);
	}
	if(false === opt.from instanceof(Date) || false === opt.to instanceof(Date)){
		return false;
	}
	if(opt.from.getTime() > opt.to.getTime()){
		tmp = opt.from;
		opt.from = opt.to;
		opt.to = tmp;
	}
	if(opt.clearFirst === true){
		$('#res_cal_'+opt.cal+' .cal_select').removeClass('cal_select');
	}
	tmpTo = walkDays(opt.to);
	for(tmp = dateToStr(opt.from); tmp !== tmpTo; tmp = walkDays(tmp)){
		$('#'+opt.cal+'_d_'+tmp).addClass('cal_select');
	}
}
function resCalLetSelect(options){
	'use strict';
	var enterTimeOut, leaveTimeOut;
	if('undefined' === typeof(options)){
		options = {};
	}
	var calSelector = (options.cal)? 'div#res_cal_'+options.cal+' div.content' : 'div.res_cal div.content';
	resCalHandlerEnter = function(){
		var self = $(this);
		window.clearTimeout(enterTimeOut);
		enterTimeOut = window.setTimeout(function(){
			var dayMatch = self.attr('id').match(/^([a-zA-Z0-9_]+)_d_([0-9]{2}-[0-9]{2}-[0-9]{2})$/),
				calName = dayMatch[1],
				oDay = strToDate(dayMatch[2]);
			resCalShowSelection({cal:calName, from:reservations[calName].sel1, to:oDay});
		}, 50);
	};
	resCalHandlerLeave = function(){
		var self = $(this);
		window.clearTimeout(leaveTimeOut);
		leaveTimeOut = window.setTimeout(function(){
			var dayMatch = self.attr('id').match(/^([a-zA-Z0-9_]+)_d_([0-9]{2}-[0-9]{2}-[0-9]{2})$/),
				calName = dayMatch[1];
			$('div#res_cal_'+calName+' div.cal_select').removeClass('cal_select');
		}, 50);
	};
	resCalHandlerClick = function(){
		var self = $(this),
			dayMatch = self.attr('id').match(/^([a-zA-Z0-9_]+)_d_([0-9]{2}-[0-9]{2}-[0-9]{2})$/),
			calName = dayMatch[1],
			oDay = strToDate(dayMatch[2]);
		if(!reservations[calName].sel1 || (reservations[calName].sel1 && reservations[calName].sel2)){
			reservations[calName].sel2 = false;
			reservations[calName].sel1 = oDay;
			$('#reservation_from_'+calName).datepicker('setDate', oDay);
			$('#reservation_to_'+calName).datepicker('setDate', null);
			$('div#res_cal_'+calName+' div.cal_select').removeClass('cal_select');
			$('div#res_cal_'+calName+' div.content')
				.bind('mouseenter', resCalHandlerEnter)
				.bind('mouseleave', resCalHandlerLeave);
		} else if(reservations[calName].sel1 && !reservations[calName].sel2){
			if(oDay.getTime() < reservations[calName].sel1.getTime()){
				reservations[calName].sel2 = reservations[calName].sel1;
				$('#reservation_to_'+calName).datepicker('setDate', reservations[calName].sel1);
				reservations[calName].sel1 = oDay;
				$('#reservation_from_'+calName).datepicker('setDate', oDay);
			} else {
				reservations[calName].sel2 = oDay;
				$('#reservation_to_'+calName).datepicker('setDate', oDay);
			}
			$('div#res_cal_'+calName+' div.content')
				.unbind('mouseenter', resCalHandlerEnter)
				.unbind('mouseleave', resCalHandlerLeave);
			resCalShowSelection({cal:calName, globalFromTo:true, clearFirst:true});
		}
	};
	$(calSelector)
		.addClass('cal_selectable')
		.bind('click', resCalHandlerClick);
}
function resFormDateSelected(self){
	'use strict';
	if('object' !== typeof(self) || false === self instanceof jQuery){
		return false;
	}
	var selfMatch = self.attr('id').match(/reservation_(from|to)_([a-zA-Z0-9_-]+)/),
		calName = selfMatch[2],
		oDate = self.datepicker('getDate');
	reservations[calName][((selfMatch[1] === 'from')? 'sel1' : 'sel2')] = oDate;
	if(reservations[calName].sel1 && reservations[calName].sel2){
		if(reservations[calName].sel1.getTime() > reservations[calName].sel2.getTime()){
			$('#reservation_from_'+calName).datepicker('setDate', reservations[calName].sel2);
			$('#reservation_to_'+calName).datepicker('setDate', reservations[calName].sel1);
			reservations[calName].sel1 = $('#reservation_from_'+calName).datepicker('getDate');
			reservations[calName].sel2 = $('#reservation_to_'+calName).datepicker('getDate');
		}
	}
	resCalShowSelection({cal:calName, globalFromTo:true, clearFirst:true});
}
function strToDate(s){
	'use strict';
	var parsed = s.match(/([0-9]{1,4})([.\/-])([01]?[0-9])\2([0-9]{1,4})/),
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
	if(false === d instanceof(Date)){
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
	if(false === d instanceof(Date)){
		return false;
	}
	var nDat = new Date(d.getTime());
	if('undefined' === typeof(w)){
		w = 1;
	} else {
		w = parseInt(w, 10);
	}
	nDat.setDate(d.getDate() + w);
	if('undefined' !== typeof(f) && 'oDate' === f){
		return nDat;
	} else {
		return dateToStr(nDat, f);
	}
}
function daysBetween(a, b){
	'use strict';
	if('string' === typeof(a)){
		a = strToDate(a);
	}
	if('string' === typeof(b)){
		b = strToDate(b);
	}
	if(false === a instanceof(Date) || false === b instanceof(Date)){
		return false;
	}
	return Math.round( Math.abs( a.getTime() - b.getTime() ) /86400000 );
}
function dateKW(d){
	'use strict';
	if('string' === typeof(d)){
		d = strToDate(d);
	}
	if(false === d instanceof(Date)){
		return false;
	}
	var fow = walkDays(d, ((d.getDay() === 0)? 6 : (d.getDay() -1)) *(-1), 'oDate');
	if(fow.getMonth() === 11 && fow.getDate() >= 29){
		return 1;
	}
	var foy = strToDate(d.getFullYear()+'-1-1');
	var fofw = walkDays(foy, ((foy.getDay() === 0)? 6 : (foy.getDay() -1)) *(-1), 'oDate');
	if(fow.getTime() === fofw.getTime() && d.getDay() !== 1){
		return dateKW(new Date(fow.getTime()));
	}
	var fkw = (fofw.getDate() === 1 || fofw.getDate() >= 29)? 1 : 0;
	var dbw = (fow.getTime() - fofw.getTime()) /1000 /60/60/24;
	var wbw = dbw / 7;
	return Math.round(wbw + fkw); 
}

$(function(){
	'use strict';
	if(0 < $('.res_cal').length){
		js_res_cal({withNavBtn:true});
	}
	if(0 < $('.res_form').length){
		$.datepicker.setDefaults( $.datepicker.regional[ "en-GB" ] );
		if('de' === docLang){
			$.datepicker.setDefaults( $.datepicker.regional.de );
		}
		$('.datepicker').datepicker({
			onSelect:function(){
				$(this).datepicker('hide');
				resFormDateSelected($(this));
			}
		});
	}
});
