<?php # Wed, 29 Aug 2012 14:17:21 +0200

$textblock = array (
	'textblock' => 
	array (
		'value' => 
		array (
			'IxB1s' => 
			array (
				'name' => 'test',
				'text' => '<table><tr><td>erstens:</td><td>dies & das</td></tr>
<tr><td>zweitens:</td><td>jenes & welches</td></tr></table>',
			),
			'B4ON9' => 
			array (
				'name' => 'noch_einer',
				'text' => 'huhu',
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
				'test' => 'IxB1s',
				'noch_einer' => 'B4ON9',
			),
		),
	),
);

?>