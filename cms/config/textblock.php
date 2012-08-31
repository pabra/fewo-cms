<?php # Fri, 31 Aug 2012 22:29:15 +0200

$textblock = array (
	'textblock' => 
	array (
		'value' => 
		array (
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
			),
		),
	),
);

?>