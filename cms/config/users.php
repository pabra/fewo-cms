<?php # Mon, 27 Aug 2012 22:51:51 +0200

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
			'VFT1u' => 
			array (
				'id' => 'VFT1u',
				'user_name' => 'user_1',
				'real_name' => 'simple user',
				'password' => '2eed9b05408fcacc423b3e99f9859703',
				'email' => 'abc@def.gh',
				'role' => 'user',
			),
			'hOpQ5' => 
			array (
				'id' => 'hOpQ5',
				'user_name' => 'user_2',
				'real_name' => 'Super User',
				'password' => '522dbd353c8d3b0cd413accda1a82153',
				'email' => 'def@gh.ij',
				'role' => 'superuser',
			),
			'Pvtq2' => 
			array (
				'id' => 'Pvtq2',
				'user_name' => 'user_3',
				'real_name' => 'admin user',
				'password' => '69d62f0f86688bf03f6903eb9a2da486',
				'email' => 'xyz@abc.vv',
				'role' => 'admin',
			),
		),
		'type' => 'array',
		'keep' => 1,
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
					0 => 'user',
					1 => 'superuser',
					2 => 'admin',
				),
			),
		),
		'index' => 
		array (
			'id' => 
			array (
				'ab12d' => 'ab12d',
				'VFT1u' => 'VFT1u',
				'hOpQ5' => 'hOpQ5',
				'Pvtq2' => 'Pvtq2',
			),
			'user_name' => 
			array (
				'user' => 'ab12d',
				'user_1' => 'VFT1u',
				'user_2' => 'hOpQ5',
				'user_3' => 'Pvtq2',
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
					0 => 'VFT1u',
				),
				'def@gh.ij' => 
				array (
					0 => 'hOpQ5',
				),
				'xyz@abc.vv' => 
				array (
					0 => 'Pvtq2',
				),
			),
			'role' => 
			array (
				'admin' => 
				array (
					0 => 'ab12d',
					1 => 'Pvtq2',
				),
				'user' => 
				array (
					0 => 'VFT1u',
				),
				'superuser' => 
				array (
					0 => 'hOpQ5',
				),
			),
		),
	),
);

?>