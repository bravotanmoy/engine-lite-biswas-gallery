<?php

$config = array(
	'type_options' => array(
		1 => 'Tekstas',
		2 => 'Baneris',
		3 => 'Prenumeratos Forma',
	),

	'sort_by' => 'position',
    'languages' => 'multi',
	'photos' => true,
	'cookie_expire' => 24, //hours
	'popup_delay' => 0, // seconds
	'max_width' => 600, // pixels
    'translated_fields' => array('name', 'content'),
);

$config['filter_config'] = array(
	'type' => array(
		'title' => 'Tipas',
		'input_type' => 'select',
		'options' => array('' => '...') + $config['type_options'],
		'filter_type' => 'field',
	),
);
