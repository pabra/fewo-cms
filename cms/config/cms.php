<?php # Sun, 26 Aug 2012 23:50:02 +0200

$cms = array (
	'admin_language' => 
	array (
		'value' => 'de',
		'help' => 'help_admin_language',
		'type' => 'select_one',
		'options' => 
		array (
			0 => 'de',
			1 => 'en',
		),
	),
	'use_cache' => 
	array (
		'value' => 'On',
		'type' => 'select_one',
		'options' => 
		array (
			0 => 'On',
			1 => 'Off',
		),
	),
	'compress_js' => 
	array (
		'value' => 'On',
		'type' => 'select_one',
		'options' => 
		array (
			0 => 'On',
			1 => 'Off',
		),
	),
	'compress_css' => 
	array (
		'value' => 'On',
		'type' => 'select_one',
		'options' => 
		array (
			0 => 'On',
			1 => 'Off',
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
	'page_def_lang_prior_browser_accept' => 
	array (
		'value' => '0',
		'type' => 'select_one',
		'options' => 
		array (
			0 => '1',
			1 => '0',
		),
	),
);

?>