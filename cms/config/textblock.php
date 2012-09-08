<?php # Fri, 07 Sep 2012 15:22:56 +0200

$textblock = array (
	'textblock' => 
	array (
		'value' => 
		array (
			'LT1bZ' => 
			array (
				'name' => 'ein_textblock',
				'text' => '<p>das ist ein textbl&ouml;ck</p>
<p>&nbsp;&lt;huhu&gt;</p>',
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
				'type' => 'htmlarea',
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