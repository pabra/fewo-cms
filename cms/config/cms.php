<?php # Wed, 05 Sep 2012 01:06:46 +0200

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
	'page_author' => 
	array (
		'value' => 'hOpQ5',
		'type' => 'select_one',
		'options' => 
		array (
			'from_config' => 'users:users:real_name',
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
	'template' => 
	array (
		'value' => 'doernbrack',
		'type' => 'select_one',
		'options' => 
		array (
			'from_file' => 'cms/template/*',
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
				'page_title' => 'Titel der Seite',
				'page_description' => 'allgemeine Beschreibung der Seite',
				'page_keywords' => 'allg,schlag,worte,für,die,seite',
				'visible' => 'On',
			),
			'zfKou' => 
			array (
				'lang' => 'en',
				'lang_name' => 'english',
				'page_title' => 'title of page',
				'page_description' => 'general description of the page',
				'page_keywords' => 'general,key,words,of,web,site',
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
			'page_title' => 
			array (
				'type' => 'text',
				'must' => '1',
			),
			'page_description' => 
			array (
				'type' => 'text',
				'must' => '1',
			),
			'page_keywords' => 
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