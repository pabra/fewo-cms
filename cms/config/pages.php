<?php # Sun, 26 Aug 2012 22:27:33 +0200

$pages = array (
	'pages' => 
	array (
		'value' => 
		array (
			'RHOsF' => 
			array (
				'name' => 'Startseite',
				'title' => 'start',
				'lang' => 'pz28x',
				'startpage_for_lang' => 
				array (
				),
				'is_sub_of' => '0',
			),
			'x0jUs' => 
			array (
				'name' => 'Startpage',
				'title' => 'start',
				'lang' => 'zfKou',
				'startpage_for_lang' => 
				array (
				),
				'is_sub_of' => '0',
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
				'match' => '^[a-zA-Z0-9_üöäÜÖÄß]+$',
			),
			'title' => 
			array (
				'type' => 'text',
			),
			'lang' => 
			array (
				'type' => 'select_one',
				'options' => 
				array (
					'from_config' => 'cms:avail_page_lang:lang_name',
				),
			),
			'startpage_for_lang' => 
			array (
				'type' => 'select_more',
				'options' => 
				array (
					0 => 'X',
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
				'Startseite' => 'RHOsF',
				'Startpage' => 'x0jUs',
			),
		),
	),
);

?>