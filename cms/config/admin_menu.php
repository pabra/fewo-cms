<?php # Sun, 26 Aug 2012 22:49:19 +0200

$admin_menu = array (
	'menu_user' => 
	array (
		'value' => 
		array (
			'Es5FG' => 
			array (
				'name' => 'pages',
				'link' => '?admin&do=pages_order',
				'is_sub_of' => '0',
			),
			'TUG3u' => 
			array (
				'name' => 'order',
				'link' => '?admin&do=pages_order',
				'is_sub_of' => 'Es5FG',
			),
			'TnkRq' => 
			array (
				'name' => 'content',
				'link' => '?admin&do=pages_content',
				'is_sub_of' => 'Es5FG',
			),
			'fqp4T' => 
			array (
				'name' => 'another one',
				'link' => '?admin',
				'is_sub_of' => '0',
			),
		),
		'type' => 'array',
		'model' => 
		array (
			'name' => 
			array (
				'type' => 'text',
			),
			'link' => 
			array (
				'type' => 'text',
			),
			'is_sub_of' => 
			array (
				'type' => 'select_one',
				'allow_none' => '1',
				'list' => '1',
				'options' => 
				array (
					'from_config' => 'admin_menu:menu_user:name',
					'filter' => 'is_sub_of=0',
				),
			),
		),
		'list' => 
		array (
			'is_sub_of' => 
			array (
				0 => 
				array (
					0 => 'Es5FG',
					1 => 'fqp4T',
				),
				'Es5FG' => 
				array (
					0 => 'TUG3u',
					1 => 'TnkRq',
				),
			),
		),
	),
	'menu_admin' => 
	array (
		'value' => 
		array (
			'DVXAX' => 
			array (
				'name' => 'edit config',
				'link' => '?admin&do=config_select',
				'is_sub_of' => '0',
			),
			'fZDR1' => 
			array (
				'name' => 'clear cache',
				'link' => '?admin&do=clear_cache',
				'is_sub_of' => '0',
			),
		),
		'type' => 'array',
		'model' => 
		array (
			'name' => 
			array (
				'type' => 'text',
			),
			'link' => 
			array (
				'type' => 'text',
			),
			'is_sub_of' => 
			array (
				'type' => 'select_one',
				'allow_none' => '1',
				'list' => '1',
				'options' => 
				array (
					'from_config' => 'admin_menu:menu_admin:name',
					'filter' => 'is_sub_of=0',
				),
			),
		),
		'list' => 
		array (
			'is_sub_of' => 
			array (
				0 => 
				array (
					0 => 'DVXAX',
					1 => 'fZDR1',
				),
			),
		),
	),
);

?>