/*global locObj, docName, docLang, clog, reservations, js_res_cal, resCalShowSelection, resCalResInSel, show_warning, show_info, lecho, walkDays, dateToStr, strToDate */
function send_res(calName){
	'use strict';
	reservations[calName].reserved.sort();
	//reservations[calName].reserved = reservations[calName].reserved.join('|');
	clog(reservations[calName].reserved);
	var p_data = {};
	p_data['res_cal:calendar:'+reservations[calName].conf_idx+':reserved'] = reservations[calName].reserved.join('|').replace(/^\|+/, '').replace(/\|+$/, '').replace(/\|[|]+/g, '');
	//clog(p_data);
	$.post('ajax.php?do=send_config', p_data, function(data){
		//clog(data);
		if(true === data.status){
			//window.location = window.location;
			//window.location = window.location.href.replace(/#.*$/, '');
			window.location = window.location.href.replace(/&res_(?:beg|end)=[0-9d_-]+/g, '');
		}
	});
}
$(function(){
	'use strict';
	//var locObj = locationSeach2Obj();
	if('undefined' !== typeof(locObj['do']) && locObj['do'] === 'reservations'){
		$('#cal_button_set').buttonset().change(function(ev){
			window.location = '?admin&do='+locObj['do']+'&cal_idx='+$(this).find(':checked').val();
		});
		$('.add_timespan_buttonset span').button().parent().buttonset().hide();
		$('span.add_timespan.mark_unselect').click(function(){
			var calName = $(this).parent().attr('id').replace('selection_do_', '');
			//clog(calName);
			$('#res_cal_'+calName+' .cal_select').removeClass('cal_select');
			$('.add_timespan_buttonset').hide('slide',{direction:'up'},300);
		});
		$('span.add_timespan.mark_reserved').click(function(){
			var calName = $(this).parent().attr('id').replace('selection_do_', ''),
				dStr;
			if(false === reservations[calName].sel1 instanceof(Date) || 
				false === reservations[calName].sel2 instanceof(Date) || 
				reservations[calName].sel2.getTime() <= reservations[calName].sel1){
				
				return false;
			}
			$('.add_timespan_buttonset span').button('disable');
			for(var d = reservations[calName].sel1; d.getTime() <= reservations[calName].sel2; d = walkDays(d, +1, 'oDate')){
				dStr = dateToStr(d);
				if(-1 === $.inArray(dStr, reservations[calName].reserved)){
					reservations[calName].reserved.push( dateToStr(d) );
				}
			}
			//reservations[calName].reserved.sort();
			//clog(reservations[calName].reserved);
			send_res(calName);
		});
		$('span.add_timespan.mark_free').click(function(){
			var calName = $(this).parent().attr('id').replace('selection_do_', ''),
				dStr, i;
			if(false === reservations[calName].sel1 instanceof(Date) || 
				false === reservations[calName].sel2 instanceof(Date) || 
				reservations[calName].sel2.getTime() <= reservations[calName].sel1){
				
				return false;
			}
			$('.add_timespan_buttonset span').button('disable');
			for(var d = reservations[calName].sel1; d.getTime() <= reservations[calName].sel2; d = walkDays(d, +1, 'oDate')){
				dStr = dateToStr(d);
				i = $.inArray(dStr, reservations[calName].reserved);
				if(-1 !== i){
					reservations[calName].reserved.splice(i, 1);
				}
			}
			//reservations[calName].reserved.sort();
			//clog(reservations[calName].reserved);
			send_res(calName);
		});
		if('undefined' !== typeof(locObj.res_beg) &&
			'undefined' !== typeof(locObj.res_end) &&
			'undefined' !== typeof(locObj.cal_idx) &&
			'undefined' !== typeof(reservations) &&
			true === strToDate(locObj.res_beg) instanceof(Date) &&
			true === strToDate(locObj.res_end) instanceof(Date)){
		
			var cal_name = false,
				res_beg = strToDate(locObj.res_beg),
				res_end = strToDate(locObj.res_end),
				dStr;
			$.each(reservations, function(k,v){
				if(v.conf_idx === locObj.cal_idx){
					cal_name = v.name;
				}
			});
			if(false !== cal_name){
				if(res_end.getTime() < res_beg.getTime()){
					reservations[cal_name].sel1 = res_end;
					reservations[cal_name].sel2 = res_beg;
				} else {
					reservations[cal_name].sel1 = res_beg;
					reservations[cal_name].sel2 = res_end;
				}
				clog(reservations[cal_name].sel1);
				clog(reservations[cal_name].sel2);
				//clog(resCalResInSel({cal:cal_name}));
				if(reservations[cal_name].sel1.getFullYear() !== reservations[cal_name].showYear){
					js_res_cal({ year:reservations[cal_name].sel1.getFullYear() });
				}
				resCalShowSelection({ cal:cal_name, clearFirst:true, globalFromTo:true });
				if(1 === resCalResInSel({ cal:cal_name, dontReselect:true }) ){
					show_warning( lecho('cal_admin_add_overlap_period') );
				} else {
					for(var d = reservations[cal_name].sel1; d.getTime() <= reservations[cal_name].sel2; d = walkDays(d, +1, 'oDate')){
						dStr = dateToStr(d);
						if(-1 === $.inArray(dStr, reservations[cal_name].reserved)){
							reservations[cal_name].reserved.push( dateToStr(d) );
						}
					}
					send_res(cal_name);
				}
			}
			//send_res(cal_name);
			//select_range(id2int(locObj.res_beg), id2int(locObj.res_end));
			//$('span.add_timespan.mark_reserved').click();
		}
	}
});
