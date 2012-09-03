<?php # Mon, 03 Sep 2012 11:12:46 +0200

$textblock = array (
	'textblock' => 
	array (
		'value' => 
		array (
			'LT1bZ' => 
			array (
				'name' => 'ein_textblock',
				'text' => 'das ist ein textblock',
				'include' => '[[:textblock::]]',
			),
			'KiFYL' => 
			array (
				'name' => 'agb_de',
				'text' => 'die deutschen AGB',
				'include' => '',
			),
			'OhvlA' => 
			array (
				'name' => 'agb_en',
				'text' => 'here are the terms and conditions',
				'include' => '',
			),
		),
		'type' => 'array',
		'model' => 
		array (
			'name' => 
			array (
				'type' => 'text',
				'must' => '1',
				'index' => '1',
				'match' => '^[a-zA-Z]+[a-zA-Z0-9_]*$',
			),
			'text' => 
			array (
				'type' => 'textarea',
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
				'ein_textblock' => 'LT1bZ',
				'agb_de' => 'KiFYL',
				'agb_en' => 'OhvlA',
			),
		),
	),
);

?>