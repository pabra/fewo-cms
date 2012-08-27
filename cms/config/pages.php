<?php # Mon, 27 Aug 2012 23:36:05 +0200

$pages = array (
	'pages' => 
	array (
		'value' => 
		array (
			'fjk5P' => 
			array (
				'name' => 'Start',
				'title' => 'Startseite',
				'access' => 'private',
				'lang' => 'pz28x',
				'is_sub_of' => '_none_',
			),
			'oON9n' => 
			array (
				'name' => 'Preise',
				'title' => 'hier \'sind\' die "Preise" in € & Cent',
				'access' => 'public',
				'lang' => 'pz28x',
				'is_sub_of' => '_none_',
			),
			'nkewc' => 
			array (
				'name' => 'price',
				'title' => 'here are the prices',
				'access' => 'public',
				'lang' => 'zfKou',
				'is_sub_of' => '_none_',
			),
			'iCHhz' => 
			array (
				'name' => 'Startpage',
				'title' => 'the beginning',
				'access' => 'private',
				'lang' => 'zfKou',
				'is_sub_of' => '_none_',
			),
		),
		'type' => 'array',
		'keep' => '1',
		'model' => 
		array (
			'name' => 
			array (
				'type' => 'text',
				'must' => '1',
				'index' => '1',
				'match' => '^[a-zA-Z]+[a-zA-Z0-9_üöäÜÖÄß]*$',
			),
			'title' => 
			array (
				'type' => 'text',
			),
			'access' => 
			array (
				'type' => 'select_one',
				'options' => 
				array (
					0 => 'public',
					1 => 'private',
					2 => 'password',
				),
			),
			'lang' => 
			array (
				'type' => 'select_one',
				'list' => '1',
				'options' => 
				array (
					'from_config' => 'cms:avail_page_lang:lang_name',
				),
			),
			'is_sub_of' => 
			array (
				'type' => 'select_one',
				'allow_none' => '1',
				'options' => 
				array (
					'from_config' => 'pages:pages:name',
					'filter' => 'lang=$lang_edit_now',
				),
			),
		),
		'index' => 
		array (
			'name' => 
			array (
				'Start' => 'fjk5P',
				'Preise' => 'oON9n',
				'price' => 'nkewc',
				'Startpage' => 'iCHhz',
			),
		),
		'list' => 
		array (
			'lang' => 
			array (
				'pz28x' => 
				array (
					0 => 'fjk5P',
					1 => 'oON9n',
				),
				'zfKou' => 
				array (
					0 => 'nkewc',
					1 => 'iCHhz',
				),
			),
		),
	),
);

?>