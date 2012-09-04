var cal_handler_enter = function(){
	'use strict';
	$(this).addClass('cal_select');
	if(0!==select_begin && 0===select_end){
		select_range(select_begin, id2int($(this).attr('id')), $(this).parents('.res_cal').first().attr('id').replace(/^res_cal_/, ''));
	}
},
cal_handler_leave = function(){
	'use strict';
	$(this).removeClass('cal_select');
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
		.css({cursor:'pointer'})
		.bind('mouseenter', cal_handler_enter)
		.bind('mouseleave', cal_handler_leave)
		.bind('click', cal_handler_click);
}
$(function(){
	if(0 !== $('.res_form').length){
		$.datepicker.setDefaults( $.datepicker.regional[ "en-GB" ] );
		if('de' === lang){
			$.datepicker.setDefaults( $.datepicker.regional[ "de" ] );
		}
		$('.datepicker').datepicker({
			onSelect:function(d,dObj){
				var cal_name = $(this).parents('form.res_form').attr('id').replace(/^res_form_/, '');
				clog(cal_name);
			}
		});
		let_select();
	}
});

