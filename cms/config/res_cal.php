<?php # Mon, 15 Oct 2012 23:01:33 +0200

$res_cal = array (
	'calendar' => 
	array (
		'value' => 
		array (
			'z2OLb' => 
			array (
				'name' => 'kalender_1',
				'settings' => 
				array (
					0 => 'kw_t3',
					1 => 'with_headline',
					2 => 'first_last_resday_half',
					3 => 'no_day_title',
					4 => 'show_today',
				),
				'form_settings' => 
				array (
					0 => 'show_form',
					1 => 'need_captcha',
					2 => 'need_email',
				),
				'legal_condition' => 'KiFYL',
				'type' => '3',
				'reserved' => '12-05-09|12-05-10|12-05-11|12-05-12|12-05-13|12-05-14|12-05-15|12-05-16|12-05-17|12-05-18|12-05-25|12-05-26|12-05-27|12-05-28|12-05-29|12-05-30|12-05-31|12-06-01|12-06-05|12-06-06|12-06-07|12-06-08|12-06-09|12-08-09|12-08-10|12-08-11|12-08-12|12-08-16|12-08-17|12-08-18|12-10-19|12-10-20|12-10-21|12-10-22|12-11-10|12-11-11|12-11-23|12-11-24|12-11-25|12-11-26|12-11-27|12-11-28|12-11-29|12-12-29|12-12-30|12-12-31|13-01-01|13-01-02|13-03-03|13-03-04|13-03-05|13-03-06|13-03-07|13-03-08|13-03-09',
				'include' => '[[:res_cal::]]',
			),
			'YLVXR' => 
			array (
				'name' => 'kalender_2',
				'settings' => 
				array (
					0 => 'show_today',
				),
				'form_settings' => 
				array (
					0 => 'show_form',
					1 => 'need_captcha',
				),
				'legal_condition' => 'KiFYL',
				'type' => '1',
				'reserved' => '12-05-05|12-05-06|12-05-07|12-05-08|12-05-09|12-05-10|12-05-11|12-05-12|12-05-13|12-05-14|12-05-15|12-05-16|12-05-17|12-05-18|12-05-19|12-05-20|12-05-21|12-05-22|12-05-23|12-05-24|12-05-25|12-05-26|12-05-27|12-05-28|12-05-29|12-05-30|12-05-31|12-06-01|12-06-02|12-06-03|12-06-04|12-06-05|12-06-06|12-06-07|12-06-08|12-06-09|12-06-10|12-06-11|12-06-12|12-11-03|12-11-04|12-11-05|12-11-06|12-11-07|12-11-08|12-11-09|12-11-20|12-11-21|12-11-22|12-11-27|12-12-05|12-12-06|12-12-07|12-12-08|12-12-09|12-12-10|12-12-11|12-12-12',
				'include' => '',
			),
		),
		'type' => 'array',
		'model' => 
		array (
			'name' => 
			array (
				'type' => 'text',
				'match' => '^[a-zA-Z]+[a-zA-Z0-9_]*$',
				'must' => '1',
				'index' => '1',
			),
			'settings' => 
			array (
				'type' => 'select_more',
				'options' => 
				array (
					0 => 'kw_t3',
					1 => 'with_headline',
					2 => 'short_month_names',
					3 => 'first_last_resday_half',
					4 => 'no_day_title',
					5 => 'show_today',
				),
			),
			'form_settings' => 
			array (
				'type' => 'select_more',
				'options' => 
				array (
					0 => 'show_form',
					1 => 'need_captcha',
					2 => 'need_legal_accept',
					3 => 'need_address',
					4 => 'need_country',
					5 => 'need_email',
					6 => 'need_phone',
					7 => 'allow_msg',
				),
			),
			'legal_condition' => 
			array (
				'type' => 'select_one',
				'options' => 
				array (
					'from_config' => 'textblock:textblock:name',
				),
			),
			'type' => 
			array (
				'type' => 'select_one',
				'options' => 
				array (
					0 => '1',
					1 => '2',
					2 => '3',
				),
			),
			'reserved' => 
			array (
				'type' => 'text',
			),
			'include' => 
			array (
				'type' => 'include_code',
			),
		),
		'index' => 
		array (
			'name' => 
			array (
				'kalender_1' => 'z2OLb',
				'kalender_2' => 'YLVXR',
			),
		),
	),
);

?>