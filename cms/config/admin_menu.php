<?php # Tue, 28 Aug 2012 17:39:48 +0200

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