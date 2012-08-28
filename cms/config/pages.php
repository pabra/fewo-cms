<?php # Tue, 28 Aug 2012 18:10:23 +0200

$pages = array (
	'pages' => 
	array (
		'value' => 
		array (
			'fjk5P' => 
			array (
				'name' => 'Start',
				'title' => 'Startseite',
				'access' => 'public',
				'lang' => 'pz28x',
				'is_sub_of' => '_none_',
				'content' => 'Inhalt der Startseite.<br/> 
zu den <a href="?Preise" title="tolle Preise">Preisen</a>.',
				'description' => 'Beschreibung der Startseite.',
				'keywords' => 'start,seite,schlag,wort',
			),
			'oON9n' => 
			array (
				'name' => 'Preise',
				'title' => 'hier \'sind\' die "Preise" in € & Cent',
				'access' => 'public',
				'lang' => 'pz28x',
				'is_sub_of' => '_none_',
				'content' => 'Hier stehen dann die Preise.
Und noch einiges mehr...<br/>
zurück <a href="?Start" title="Startseite">zur Startseite</a>.',
				'description' => '',
				'keywords' => '',
			),
			'R6yYj' => 
			array (
				'name' => 'Preise_im_Detail',
				'title' => 'die Preis-Unterseite',
				'access' => 'public',
				'lang' => 'pz28x',
				'is_sub_of' => 'oON9n',
				'content' => 'hier steht dann was ganz spezielles.',
				'description' => '',
				'keywords' => '',
			),
			'dIjvm' => 
			array (
				'name' => 'Startpage',
				'title' => 'the beginning',
				'access' => 'public',
				'lang' => 'zfKou',
				'is_sub_of' => '_none_',
				'content' => 'Welcome...<br/>
go, see <a href="?price">the price</a>.',
				'description' => '',
				'keywords' => '',
			),
			'YCS9k' => 
			array (
				'name' => 'price',
				'title' => 'here are the prices',
				'access' => 'public',
				'lang' => 'zfKou',
				'is_sub_of' => '_none_',
				'content' => 'here are the big prices. <br/>
<a href="?Startpage">start</a>',
				'description' => '',
				'keywords' => '',
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
				'list' => '1',
				'options' => 
				array (
					'from_config' => 'pages:pages:name',
					'filter' => 'lang=$lang_edit_now',
				),
			),
			'content' => 
			array (
				'type' => 'textarea',
			),
			'description' => 
			array (
				'type' => 'text',
			),
			'keywords' => 
			array (
				'type' => 'text',
			),
		),
		'index' => 
		array (
			'name' => 
			array (
				'Start' => 'fjk5P',
				'Preise' => 'oON9n',
				'Preise_im_Detail' => 'R6yYj',
				'Startpage' => 'dIjvm',
				'price' => 'YCS9k',
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
					2 => 'R6yYj',
				),
				'zfKou' => 
				array (
					0 => 'dIjvm',
					1 => 'YCS9k',
				),
			),
			'is_sub_of' => 
			array (
				'_none_' => 
				array (
					0 => 'fjk5P',
					1 => 'oON9n',
					2 => 'dIjvm',
					3 => 'YCS9k',
				),
				'oON9n' => 
				array (
					0 => 'R6yYj',
				),
			),
		),
	),
);

?>