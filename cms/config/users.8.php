<?php # Sat, 18 Aug 2012 00:16:48 +0200

$users = array (
	'users' => 
	array (
		'value' => 
		array (
			'ab12d' => 
			array (
				'id' => 'ab12d',
				'user_name' => 'user',
				'real_name' => 'Benutzer  User',
				'password' => '1a1dc91c907325c69271ddf0c944bc72',
				'email' => 'a@b.c',
				'role' => 'admin',
			),
			'mko98' => 
			array (
				'id' => 'mko98',
				'user_name' => 'benutzer',
				'real_name' => 'Mein Name',
				'password' => '1a1dc91c907325c69271ddf0c944bc72',
				'email' => 'abc@def.gh',
				'role' => 'user',
			),
		),
		'type' => 'array',
		'model' => 
		array (
			'id' => 
			array (
				'type' => 'id',
			),
			'user_name' => 
			array (
				'type' => 'text',
				'must' => true,
				'index' => true,
			),
			'real_name' => 
			array (
				'type' => 'text',
				'must' => true,
				'match' => '^[a-zA-Z0-9üöäßÜÖÄ.,& ]+$',
			),
			'password' => 
			array (
				'type' => 'password',
				'must' => true,
			),
			'email' => 
			array (
				'type' => 'email',
				'must' => true,
				'list' => true,
			),
			'role' => 
			array (
				'type' => 'select_one',
				'must' => true,
				'list' => true,
				'options' => 
				array (
					0 => 'admin',
					1 => 'user',
					2 => 'guest',
				),
			),
		),
		'keep' => 1,
		'index' => 
		array (
			'id' => 
			array (
				'ab12d' => 'ab12d',
				'mko98' => 'mko98',
			),
			'user_name' => 
			array (
				'user' => 'ab12d',
				'benutzer' => 'mko98',
			),
		),
		'list' => 
		array (
			'email' => 
			array (
				'a@b.c' => 
				array (
					0 => 'ab12d',
				),
			),
		),
	),
);

?>