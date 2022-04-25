<?php

$config = array(
    'active' => true,
	'sort_by' => 'position',
	'photos' => true,
	'audit' => false,
	'tags' => true,
	'page_size' => 0,
	'sort_by' => 'position',
	'sort_type' => 'asc',
	'translated_fields' => array('name'),
	'filters' => true,
	'filter_config' => array(
		'keywords' => \Elab\Lite\Engine::get_filter_keywords(),
	),
);
