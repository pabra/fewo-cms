<?php # Mon, 03 Sep 2012 19:15:16 +0200

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
					0 => 'first_last_resday_half',
				),
				'form_settings' => 
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
				'legal_condition' => 'KiFYL',
				'type' => '1',
				'reserved' => '12-11-23|12-11-23|12-11-24|12-11-24|12-11-25|12-11-26|12-11-27|12-11-28|12-11-29',
				'include' => '[[:res_cal::]]',
			),
			'o7z5b' => 
			array (
				'name' => 'kalender_2',
				'settings' => 
				array (
					0 => 'with_headline',
					1 => 'short_month_names',
					2 => 'first_last_resday_half',
				),
				'form_settings' => 
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
				'legal_condition' => 'KiFYL',
				'type' => '2',
				'reserved' => '12-05-12|12-05-13|12-05-14|12-05-15|12-05-16|12-05-17|12-05-18|12-05-19',
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
				'kalender_2' => 'o7z5b',
			),
		),
	),
);

?>