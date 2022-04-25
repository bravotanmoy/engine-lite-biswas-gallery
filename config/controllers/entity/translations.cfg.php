<?php

$config = array(
	'page_usage' => 'language',
    'page_size' => 30,
	'filter_config' => array(
		'keywords' => \Elab\Lite\Engine::get_filter_keywords(array('fields' => array('key', 'value'))),
		'sys_language' => array(
			'title' => t('Versti iš'),
			'filter_type' => 'custom',
			'input_type' => 'select',
			'function' => 'change_systems_language',
		),
		'language' => array(
			'title' => t('Versti į'),
			'filter_type' => 'custom',
			'input_type' => 'select',
			'function' => 'languages',
		),
		'interface' => array(
			'title' => t('Interfeisas'),
			'input_type' => 'select',
			'filter_type' => 'custom',
			'options' => array(
				0 => '...',
				1 => t('Frontend'),
				2 => t('Backend'),
			),
			'function' => 'interface',
		),
		'value' => array(
			'title' => t('Reikšmė'),
			'input_type' => 'select',
			'filter_type' => 'custom',
			'options' => array(
				0 => '...',
				1 => t('Įvesta'),
				2 => t('Neįvesta'),
			),
			'function' => 'not_empty',
		),
	),
	)
?>