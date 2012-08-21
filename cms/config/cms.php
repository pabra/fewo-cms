<?php

$cms = array(
	'admin_language' => array(
		'value' => 'de',
		'type' => 'select_one',
		'help' => 'help_admin_language',
		'options' => array(
			'de','en'
		),
	),
	'test' => array(
		'value' => 'huhu',
		'type'=>'text',
		'match'=>'^[a-z]+$',
	),
	'test_multi'=>array(
		'value'=>array('a','c'),
		'type'=>'select_more',
		'options' =>array(
			'a','bbbbbbbbbbbbbbbbbbbbbbbbb','c','dddddddddddd'
		)
	),
	'avail_page_lang' => array(
		'value' => array(
			'abcd1' => array(
				'lang' => 'de',
				'lang_name' => 'deutsch',
			),
			'xyz2z' => array(
				'lang' => 'en',
				'lang_name' => 'english',
			),
		),
		'type' => 'array',
		'model' => array(
			'lang' => array(
				'type' => 'text',
				'must'=>'1',
			),
			'lang_name' => array(
				'type' => 'text',
				'must'=>'1',
			),
		),
	),
	'page_def_lang_prior_browser_accept' => array(
		'value' => '1',
		'type' => 'select_one',
		'help' => 'help_page_def_lang_prior_browser_accept',
		'options' => array(
			'1','0'
		),
	),
);

?>
