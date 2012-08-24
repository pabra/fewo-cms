<?php # Thu, 23 Aug 2012 19:30:29 +0200

$pages = array (
	'start_page' => 
	array (
		'value' => 'neue',
		'type' => 'select_one',
		'options' => 
		array (
			'from_config' => 'pages:pages:name',
		),
	),
	'pages' => 
	array (
		'value' => 
		array (
			'KD4mY' => 
			array (
				'name' => 'Startseite',
				'title' => 'Die Startseite',
				'lang' => 'deutsch',
				'startpage_for_lang' => 
				array (
					0 => 'X',
				),
				'is_subpage_of' => '---',
			),
			'unskc' => 
			array (
				'name' => 'startpage',
				'title' => 'the startpage new',
				'lang' => 'english',
				'startpage_for_lang' => 
				array (
					0 => 'X',
				),
				'is_subpage_of' => '---',
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
			'is_subpage_of' => 
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
				'Startseite' => 'KD4mY',
				'startpage' => 'unskc',
			),
		),
	),
);

?>