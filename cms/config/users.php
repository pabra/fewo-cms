<?php # Sat, 18 Aug 2012 10:52:57 +0200

$users = array (
	'users' => 
	array (
		'value' => 
		array (
			'eURSc' => 
			array (
				'id' => 'eURSc',
				'user_name' => 'pepp_4',
				'real_name' => 'Pat.Bass',
				'password' => '123',
				'email' => 'abc@def.gh',
				'role' => 'user',
			),
			'CioR2' => 
			array (
				'id' => 'CioR2',
				'user_name' => 'pepper',
				'real_name' => 'Pat.Bass',
				'password' => '123',
				'email' => 'abc@def.gh',
				'role' => 'user',
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
			'ab12d' => 
			array (
				'id' => 'ab12d',
				'user_name' => 'user',
				'real_name' => 'Benutzer  User',
				'password' => '1a1dc91c907325c69271ddf0c944bc72',
				'email' => 'a@b.c',
				'role' => 'admin',
			),
			'LU9Au' => 
			array (
				'id' => 'LU9Au',
				'user_name' => 'pepp_3',
				'real_name' => 'Pat.Bass',
				'password' => '123',
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
		'index' => 
		array (
			'id' => 
			array (
				'eURSc' => 'eURSc',
				'CioR2' => 'CioR2',
				'mko98' => 'mko98',
				'ab12d' => 'ab12d',
				'LU9Au' => 'LU9Au',
			),
			'user_name' => 
			array (
				'pepp_4' => 'eURSc',
				'pepper' => 'CioR2',
				'benutzer' => 'mko98',
				'user' => 'ab12d',
				'pepp_3' => 'LU9Au',
			),
		),
		'list' => 
		array (
			'email' => 
			array (
				'abc@def.gh' => 
				array (
					0 => 'eURSc',
					1 => 'CioR2',
					2 => 'mko98',
					3 => 'LU9Au',
				),
				'a@b.c' => 
				array (
					0 => 'ab12d',
				),
			),
			'role' => 
			array (
				'user' => 
				array (
					0 => 'eURSc',
					1 => 'CioR2',
					2 => 'mko98',
					3 => 'LU9Au',
				),
				'admin' => 
				array (
					0 => 'ab12d',
				),
			),
		),
		'keep' => 1,
	),
);

?>