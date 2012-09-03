var cal_handler_enter = function(){
	'use strict';
	$(this).addClass('cal_select');
	if(0!==select_begin && 0===select_end){
		select_range(select_begin, id2int($(this).attr('id')));
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
	var d_match = id2int($(this).attr('id'));
	if(0 === select_begin){
		$(this).addClass('cal_select_begin');
		select_begin = d_match;
	} else if(0 === select_end){
		$(this).addClass('cal_select_end');
		select_end = d_match;
		$('div.res_cal div.content')
			.css({cursor:'default'})
			.unbind('mouseenter', cal_handler_enter)
			.unbind('mouseleave', cal_handler_leave)
			.unbind('click', cal_handler_click);
		$('.add_timespan_buttonset').show('slide',{direction:'up'},300);
	}
}, select_begin = 0, select_end = 0;
function id2int(s){
	'use strict';
	return parseInt(s.replace(/^d_/, '').replace(/-/g, ''), 10);
}
function select_range(r_beg, r_end){
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
		tmpStr = 'd_'+tmpStr.substr(0,2)+'-'+tmpStr.substr(2,2)+'-'+tmpStr.substr(4,2);
		$('#'+tmpStr).addClass('cal_select');
	}
}
function let_select(){
	'use strict';
	$('div.res_cal div.content')
		.css({cursor:'pointer'})
		.bind('mouseenter', cal_handler_enter)
		.bind('mouseleave', cal_handler_leave)
		.bind('click', cal_handler_click);
}

