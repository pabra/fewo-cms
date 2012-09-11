<?php # Tue, 11 Sep 2012 10:07:32 +0200

$admin_menu = array (
	'menu_user' => 
	array (
		'value' => 
		array (
			'Es5FG' => 
			array (
				'name' => 'pages',
				'link' => '?admin&do=pages_order',
				'is_sub_of' => '_none_',
			),
			'fqp4T' => 
			array (
				'name' => 'cms',
				'link' => '?admin&do=cms_config',
				'is_sub_of' => '_none_',
			),
			'ESXV6' => 
			array (
				'name' => 'media',
				'link' => '?admin&do=media',
				'is_sub_of' => '_none_',
			),
			'crfxF' => 
			array (
				'name' => 'textblock',
				'link' => '?admin&do=textblock',
				'is_sub_of' => '_none_',
			),
			'YGolI' => 
			array (
				'name' => 'reservations',
				'link' => '?admin&do=reservations',
				'is_sub_of' => '_none_',
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
					'filter' => 'is_sub_of=_none_',
				),
			),
		),
		'list' => 
		array (
			'is_sub_of' => 
			array (
				'_none_' => 
				array (
					0 => 'Es5FG',
					1 => 'fqp4T',
					2 => 'ESXV6',
					3 => 'crfxF',
					4 => 'YGolI',
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
				'is_sub_of' => '_none_',
			),
			'fZDR1' => 
			array (
				'name' => 'clear cache',
				'link' => '?admin&do=clear_cache',
				'is_sub_of' => '_none_',
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
					'filter' => 'is_sub_of=_none_',
				),
			),
		),
		'list' => 
		array (
			'is_sub_of' => 
			array (
				'_none_' => 
				array (
					0 => 'DVXAX',
					1 => 'fZDR1',
				),
			),
		),
	),
);

?>