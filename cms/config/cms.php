<?php # Mon, 27 Aug 2012 23:39:59 +0200

$cms = array (
	'admin_language' => 
	array (
		'value' => 'de',
		'type' => 'select_one',
		'options' => 
		array (
			0 => 'de',
			1 => 'en',
		),
	),
	'use_cache' => 
	array (
		'value' => 'Off',
		'type' => 'select_one',
		'options' => 
		array (
			0 => 'On',
			1 => 'Off',
		),
	),
	'compress_js' => 
	array (
		'value' => 'Off',
		'type' => 'select_one',
		'options' => 
		array (
			0 => 'On',
			1 => 'Off',
		),
	),
	'compress_css' => 
	array (
		'value' => 'Off',
		'type' => 'select_one',
		'options' => 
		array (
			0 => 'On',
			1 => 'Off',
		),
	),
	'page_author' => 
	array (
		'value' => 'hOpQ5',
		'type' => 'select_one',
		'options' => 
		array (
			'from_config' => 'users:users:real_name',
		),
	),
	'avail_page_lang' => 
	array (
		'value' => 
		array (
			'pz28x' => 
			array (
				'lang' => 'de',
				'lang_name' => 'deutsch',
				'visible' => 'On',
			),
			'zfKou' => 
			array (
				'lang' => 'en',
				'lang_name' => 'english',
				'visible' => 'On',
			),
		),
		'type' => 'array',
		'keep' => 1,
		'model' => 
		array (
			'lang' => 
			array (
				'type' => 'text',
				'must' => '1',
				'index' => '1',
			),
			'lang_name' => 
			array (
				'type' => 'text',
				'must' => '1',
			),
			'visible' => 
			array (
				'type' => 'select_one',
				'options' => 
				array (
					0 => 'On',
					1 => 'Off',
				),
			),
		),
		'index' => 
		array (
			'lang' => 
			array (
				'de' => 'pz28x',
				'en' => 'zfKou',
			),
		),
	),
);

?>
