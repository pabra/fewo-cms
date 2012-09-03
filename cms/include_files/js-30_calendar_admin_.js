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
