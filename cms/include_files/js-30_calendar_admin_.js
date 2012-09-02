var handler_enter = function(){
	'use strict';
	$(this).addClass('cal_select');
	if(0!==select_begin && 0===select_end){
		select_range(select_begin, id2int($(this).attr('id')));
	}
},
handler_leave = function(){
	'use strict';
	$(this).removeClass('cal_select');
	if(0!==select_begin && 0===select_end){
		$('div.res_cal div.cal_select').removeClass('cal_select');
	}
},
handler_click = function(){
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
			.unbind('mouseenter', handler_enter)
			.unbind('mouseleave', handler_leave)
			.unbind('click', handler_click);
		$('.add_timespan_buttonset').show('slide',{direction:'up'},300);
	}
};
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
		.bind('mouseenter', handler_enter)
		.bind('mouseleave', handler_leave)
		.bind('click', handler_click);
}
function send_res(){
	'use strict';
	while(reserved.indexOf('') !== -1){
		delete(reserved[reserved.indexOf('')]);
	}
	reserved.sort();
	var p_data = {};
	p_data['res_cal:calendar:'+cal_idx+':reserved'] = reserved.join('|').replace(/^\|+/, '').replace(/\|+$/, '').replace(/\|[|]+/g, '');
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
	var locObj = locationSeach2Obj();
	$('.add_timespan_buttonset span').button().parent().buttonset().hide();
	$('span.add_timespan.mark_unselect').click(function(){
		select_begin=0;
		select_end=0;
		$('div.res_cal .cal_select').removeClass('cal_select cal_select_begin cal_select_end');
		$('.add_timespan_buttonset').hide('slide',{direction:'up'},300);
		let_select();
	});
	$('span.add_timespan.mark_reserved').click(function(){
		$('.add_timespan_buttonset span').button('disable');
		$('div.res_cal .cal_select').each(function(k,v){
			reserved.push($(v).attr('id').substr(2));
		});
		reserved = $.unique( reserved );
		reserved.sort();
		send_res();
	});
	$('span.add_timespan.mark_free').click(function(){
		$('.add_timespan_buttonset span').button('disable');
		$('div.res_cal .cal_select').each(function(k,v){
			delete(reserved[reserved.indexOf($(v).attr('id').substr(2))]);
		});
		reserved = $.unique( reserved );
		reserved.sort();
		send_res();
	});
	if('undefined' !== typeof(locObj.res_beg) 
		&& 'undefined' !== typeof(locObj.res_end) 
		&& 1 === $('span.add_timespan.mark_reserved').length){
		
		select_range(id2int(locObj.res_beg), id2int(locObj.res_end));
		$('span.add_timespan.mark_reserved').click();
	}
});
