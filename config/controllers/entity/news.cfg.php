<?php

$now = date('Y-m-d');

$config = array(
	'page_size' => 10,
	'comments' => false,
	'tags' => false,
	'photos' => true,
	'meta_fields' => true,
	'sort_by' => 'date',
	'sort_type' => 'DESC',
	'filter_config' => array(
		'page' => \Elab\Lite\Engine::get_filter_page(),
		'date' => \Elab\Lite\Engine::get_filter_date(),
		'keywords' => \Elab\Lite\Engine::get_filter_keywords(),
		'tags' => \Elab\Lite\Engine::get_filter_tags(),
	),
	'subscribers_email_text' => t("{title}\n\n{description}\n\nPlačiau skaitykite: {url}\n\n--\nNorėdami atsisakyti naujienų prenumeratos, sekite nuoroda: {unsubscribe_url}"),
    'translated_fields' => [
        'name',
        'description',
        'text',
    ],
);
?>