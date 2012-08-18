<?php # Sat, 18 Aug 2012 00:29:40 +0200

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
			'WZQGg' => 
			array (
				'id' => 'WZQGg',
				'user_name' => 'pepp',
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
			'yX5xp' => 
			array (
				'id' => 'yX5xp',
				'user_name' => 'pepp_2',
				'real_name' => 'Pat.Bass',
				'password' => '123',
				'email' => 'abc@def.gh',
				'role' => 'user',
			),
			'TW8rf' => 
			array (
				'id' => 'TW8rf',
				'user_name' => 'pepper_2',
				'real_name' => 'Pat.Bass',
				'password' => '123',
				'email' => 'abc@def.gh',
				'role' => 'user',
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
			'DTLLc' => 
			array (
				'id' => 'DTLLc',
				'user_name' => 'pepper_3',
				'real_name' => 'Pat.Bass',
				'password' => '123',
				'email' => 'abc@def.gh',
				'role' => 'user',
			),
			'twjIJ' => 
			array (
				'id' => 'twjIJ',
				'user_name' => 'pepp_4',
				'real_name' => 'Pat.Bass',
				'password' => '123',
				'email' => 'abc@def.gh',
				'role' => 'user',
			),
			'wlIVx' => 
			array (
				'id' => 'wlIVx',
				'user_name' => 'pepper_4',
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
				'ab12d' => 'ab12d',
				'mko98' => 'mko98',
				'WZQGg' => 'WZQGg',
				'CioR2' => 'CioR2',
				'yX5xp' => 'yX5xp',
				'TW8rf' => 'TW8rf',
				'LU9Au' => 'LU9Au',
				'DTLLc' => 'DTLLc',
				'twjIJ' => 'twjIJ',
				'wlIVx' => 'wlIVx',
			),
			'user_name' => 
			array (
				'user' => 'ab12d',
				'benutzer' => 'mko98',
				'pepp' => 'WZQGg',
				'pepper' => 'CioR2',
				'pepp_2' => 'yX5xp',
				'pepper_2' => 'TW8rf',
				'pepp_3' => 'LU9Au',
				'pepper_3' => 'DTLLc',
				'pepp_4' => 'twjIJ',
				'pepper_4' => 'wlIVx',
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
				'abc@def.gh' => 
				array (
					0 => 'mko98',
					1 => 'WZQGg',
					2 => 'CioR2',
					3 => 'yX5xp',
					4 => 'TW8rf',
					5 => 'LU9Au',
					6 => 'DTLLc',
					7 => 'twjIJ',
					8 => 'wlIVx',
				),
			),
			'role' => 
			array (
				'admin' => 
				array (
					0 => 'ab12d',
				),
				'user' => 
				array (
					0 => 'mko98',
					1 => 'WZQGg',
					2 => 'CioR2',
					3 => 'yX5xp',
					4 => 'TW8rf',
					5 => 'LU9Au',
					6 => 'DTLLc',
					7 => 'twjIJ',
					8 => 'wlIVx',
				),
			),
		),
		'keep' => 1,
	),
);

?>