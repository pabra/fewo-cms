function select_range(r_beg, r_end){
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
	$('table.res_cal td.content').css({cursor:'pointer'}).hover(function(){
		$(this).addClass('cal_select');
		if(0!==select_begin && 0===select_end){
			select_range(select_begin, parseInt($(this).attr('id').substr(2).replace(/-/g, ''), 10));
		}
	},function(){
		$(this).removeClass('cal_select');
		if(0!==select_begin && 0===select_end){
			$('table.res_cal td.cal_select').removeClass('cal_select');
		}
	}).click(function(){
		var d_match = parseInt($(this).attr('id').substr(2).replace(/-/g, ''), 10);
		if(0 === select_begin){
			$(this).addClass('cal_select_begin');
			select_begin = d_match;
		} else if(0 === select_end){
			$(this).addClass('cal_select_end');
			select_end = d_match;
			$('table.res_cal td.content').unbind('hover').unbind('click').css({cursor:'default'});
			$('.add_timespan_buttonset').show('slide',{direction:'up'},300);
		}
	});
}
function send_res(){
	var p_data = {};
	p_data['res_cal:calendar:'+cal_idx+':reserved'] = reserved.join('|').replace(/\|[|]+/g, '');
	while(reserved.indexOf('') !== -1){
		delete(reserved[reserved.indexOf('')]);
	}
	reserved.sort();
	$.post('ajax.php?do=send_config', p_data, function(data){
		//clog(data);
		if(true === data.status){
			window.location = window.location;
		}
	});
}
$(function(){
	$('.add_timespan_buttonset span').button().parent().buttonset().hide();
	$('span.add_timespan.mark_unselect').click(function(){
		select_begin=0;
		select_end=0;
		$('table.res_cal .cal_select').removeClass('cal_select cal_select_begin cal_select_end');
		$('.add_timespan_buttonset').hide('slide',{direction:'up'},300);
		let_select();
	});
	$('span.add_timespan.mark_reserved').click(function(){
		$('.add_timespan_buttonset span').button('disable');
		$('table.res_cal .cal_select').each(function(k,v){
			reserved.push($(v).attr('id').substr(2));
		});
		reserved = $.unique( reserved ).sort();
		send_res();
	});
	$('span.add_timespan.mark_free').click(function(){
		$('.add_timespan_buttonset span').button('disable');
		$('table.res_cal .cal_select').each(function(k,v){
			delete(reserved[reserved.indexOf($(v).attr('id').substr(2))]);
		});
		reserved = $.unique( reserved ).sort();
		send_res();
	});
});
