<?php # Sun, 02 Sep 2012 17:29:52 +0200

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
			),
		),
	),
);

?>