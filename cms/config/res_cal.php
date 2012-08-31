<?php # Fri, 31 Aug 2012 23:42:45 +0200

$res_cal = array (
	'calendar' => 
	array (
		'value' => 
		array (
			'z2OLb' => 
			array (
				'name' => 'kalender_1',
				'type' => '2',
				'kw_t3' => 'On',
				'with_headline' => 'On',
				'short_month_names' => 'Off',
				'first_last_resday_half' => 'On',
				'reserved' => '|12-04-08|12-04-09|12-04-10|12-04-11|12-04-12|12-04-13|12-04-16|12-04-17|12-04-18|12-04-19|12-04-20|12-04-21|12-04-25|12-04-26|12-04-27|12-04-28|12-04-29|12-04-30|12-05-01|12-05-02|12-05-03|12-05-04|12-05-05|12-05-06|12-06-07|12-06-08|12-06-09|12-06-10|12-06-11|12-06-12|12-06-14|12-06-15|12-06-16|12-06-18|12-06-19|12-06-20|12-06-21|12-06-22|12-06-23|12-06-24|12-08-05|12-08-06|12-08-07|12-08-08|12-08-09|12-08-10|12-08-11|12-08-18|12-08-19|12-08-20|12-08-21|12-08-22|12-08-23|12-08-24|12-08-25',
				'include' => '[[:res_cal::]]',
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
			'kw_t3' => 
			array (
				'type' => 'select_one',
				'options' => 
				array (
					0 => 'On',
					1 => 'Off',
				),
			),
			'with_headline' => 
			array (
				'type' => 'select_one',
				'options' => 
				array (
					0 => 'On',
					1 => 'Off',
				),
			),
			'short_month_names' => 
			array (
				'type' => 'select_one',
				'options' => 
				array (
					0 => 'On',
					1 => 'Off',
				),
			),
			'first_last_resday_half' => 
			array (
				'type' => 'select_one',
				'options' => 
				array (
					0 => 'On',
					1 => 'Off',
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
			),
		),
	),
);

?>