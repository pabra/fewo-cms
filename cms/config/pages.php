<?php # Mon, 03 Sep 2012 19:15:56 +0200

$pages = array (
	'pages' => 
	array (
		'value' => 
		array (
			'dIjvm' => 
			array (
				'name' => 'Startpage',
				'name_show' => '',
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
				'name_show' => '',
				'title' => 'here are the prices',
				'access' => 'public',
				'lang' => 'zfKou',
				'is_sub_of' => '_none_',
				'content' => 'here are the big prices. <br/>
<a href="?Startpage">start</a><br/>
[[:res_cal:kalender_1:]]',
				'description' => '',
				'keywords' => '',
			),
			'fjk5P' => 
			array (
				'name' => 'Start',
				'name_show' => '',
				'title' => 'Startseite',
				'access' => 'public',
				'lang' => 'pz28x',
				'is_sub_of' => '_none_',
				'content' => 'Inhalt der Startseite.<br/> 
zu den <a href="?Preise" title="tolle Preise">Preisen</a>.
[[:textblock:test:]]',
				'description' => 'Beschreibung der Startseite.',
				'keywords' => 'start,seite,schlag,wort',
			),
			'oON9n' => 
			array (
				'name' => 'Preise',
				'name_show' => '',
				'title' => 'hier \'sind\' die "Preise" in € & Cent',
				'access' => 'public',
				'lang' => 'pz28x',
				'is_sub_of' => '_none_',
				'content' => 'Hier stehen dann die Preise.
Und noch einiges mehr...<br/>
zurück <a href="?Start" title="Startseite">zur Startseite</a>.<br/>
[[:anderes_plugin:123:]]
[[:res_cal:kalender_1:]]
<p>und noch einer:</p>
[[:res_cal:kalender_2:]]',
				'description' => '',
				'keywords' => '',
			),
			'R6yYj' => 
			array (
				'name' => 'preise_detail',
				'name_show' => 'Preise im Detail',
				'title' => 'die Preis-Unterseite',
				'access' => 'public',
				'lang' => 'pz28x',
				'is_sub_of' => 'oON9n',
				'content' => 'hier steht dann was ganz spezielles.<br/>
[[:res_cal:kalender_2:]]',
				'description' => '',
				'keywords' => '',
			),
			'hf31w' => 
			array (
				'name' => 'sub_preise_2',
				'name_show' => 'unterseite 2 preise',
				'title' => '',
				'access' => 'public',
				'lang' => 'pz28x',
				'is_sub_of' => 'oON9n',
				'content' => 'huhu',
				'description' => '',
				'keywords' => '',
			),
			'kBtPS' => 
			array (
				'name' => 'p2_sub1',
				'name_show' => '',
				'title' => '',
				'access' => 'public',
				'lang' => 'pz28x',
				'is_sub_of' => 'hf31w',
				'content' => 'subsub 1',
				'description' => '',
				'keywords' => '',
			),
			'DLlJa' => 
			array (
				'name' => 'p2_sub2',
				'name_show' => '',
				'title' => '',
				'access' => 'public',
				'lang' => 'pz28x',
				'is_sub_of' => 'hf31w',
				'content' => 'subsub 2',
				'description' => '',
				'keywords' => '',
			),
			'a7ab4' => 
			array (
				'name' => 'sub_preise_3',
				'name_show' => '',
				'title' => '',
				'access' => 'public',
				'lang' => 'pz28x',
				'is_sub_of' => 'oON9n',
				'content' => 'huhu 3',
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
				'match' => '^[a-zA-Z]+[a-zA-Z0-9_]*$',
			),
			'name_show' => 
			array (
				'type' => 'text',
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
				'Startpage' => 'dIjvm',
				'price' => 'YCS9k',
				'Start' => 'fjk5P',
				'Preise' => 'oON9n',
				'preise_detail' => 'R6yYj',
				'sub_preise_2' => 'hf31w',
				'p2_sub1' => 'kBtPS',
				'p2_sub2' => 'DLlJa',
				'sub_preise_3' => 'a7ab4',
			),
		),
		'list' => 
		array (
			'lang' => 
			array (
				'zfKou' => 
				array (
					0 => 'dIjvm',
					1 => 'YCS9k',
				),
				'pz28x' => 
				array (
					0 => 'fjk5P',
					1 => 'oON9n',
					2 => 'R6yYj',
					3 => 'hf31w',
					4 => 'kBtPS',
					5 => 'DLlJa',
					6 => 'a7ab4',
				),
			),
			'is_sub_of' => 
			array (
				'_none_' => 
				array (
					0 => 'dIjvm',
					1 => 'YCS9k',
					2 => 'fjk5P',
					3 => 'oON9n',
				),
				'oON9n' => 
				array (
					0 => 'R6yYj',
					1 => 'hf31w',
					2 => 'a7ab4',
				),
				'hf31w' => 
				array (
					0 => 'kBtPS',
					1 => 'DLlJa',
				),
			),
		),
	),
);

?>