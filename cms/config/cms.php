<?php # Thu, 23 Aug 2012 02:05:08 +0200

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
	'test' => 
	array (
		'value' => 'huhu',
		'type' => 'text',
		'match' => '^[a-z]+$',
	),
	'test_multi' => 
	array (
		'value' => 
		array (
			0 => 'c',
		),
		'type' => 'select_more',
		'must' => '1',
		'options' => 
		array (
			0 => 'a',
			1 => 'bbbbbbb',
			2 => 'c',
			3 => 'dddddddddddd',
		),
	),
	'test_mail' => 
	array (
		'value' => 'abc@def.gh',
		'type' => 'email',
		'must' => 1,
	),
	'avail_page_lang' => 
	array (
		'value' => 
		array (
			'abcd1' => 
			array (
				'lang' => 'de',
				'lang_name' => 'deutsch',
				'option' => 'def',
				'select' => 
				array (
					0 => 'stu',
					1 => 'vwx',
				),
			),
			'xyz2z' => 
			array (
				'lang' => 'en',
				'lang_name' => 'english',
				'option' => 'def',
				'select' => 
				array (
					0 => 'vwx',
				),
			),
		),
		'type' => 'array',
		'keep' => 2,
		'model' => 
		array (
			'lang' => 
			array (
				'type' => 'text',
				'must' => '1',
			),
			'lang_name' => 
			array (
				'type' => 'text',
				'must' => '1',
			),
			'option' => 
			array (
				'type' => 'select_one',
				'options' => 
				array (
					0 => 'abc',
					1 => 'def',
					2 => 'ghi',
				),
			),
			'select' => 
			array (
				'type' => 'select_more',
				'options' => 
				array (
					0 => 'stu',
					1 => 'vwx',
					2 => 'yzß',
				),
			),
		),
	),
	'page_def_lang_prior_browser_accept' => 
	array (
		'value' => '1',
		'type' => 'select_one',
		'options' => 
		array (
			0 => '1',
			1 => '0',
		),
	),
);

?>
