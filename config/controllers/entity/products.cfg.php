<?php

$config = array(
	'use_categories' => true,
	'use_cart' => true,
	'similar_products_limit' => 10,
	'related_products' => true,
	'related_products_limit' => 10,
	'audit' => false,
	'manufacturers' => true,
	'comments' => false,
	'tags' => true,
	'photos' => true,
    'thumbnails_count' => 3,
    'meta_fields' => true,
	'filters' => true,
	'page_size' => 20,
	'available_page_sizes' => array(10, 20, 50, 100),
	'sort_by' => 'position',
	'sort_type' => 'asc',
	'filter_config' => array(
		'keywords' => \Elab\Lite\Engine::get_filter_keywords(['fields' => ['name', 'code']]),
		'tags' => \Elab\Lite\Engine::get_filter_tags(),
		'category' => array(
			'title' => 'Prekių kategorija',
			'input_type' => 'select',
			'filter_type' => 'custom',
			'function' => 'categories',
		),
		'brand_id' => array(
			'title' => 'Prekių ženklas',
			'input_type' => 'select',
			'filter_type' => 'field',
			'function' => 'brands',
		),
		'qty' => [
			'title' => 'Likutis',
			'input_type' => 'select',
			'filter_type' => 'custom',
			'function' => 'qty',
		],
		'active' => [
			'title' => 'Prekių aktyvumas',
			'input_type' => 'select',
			'filter_type' => 'field',
			'function' => 'active',
		],
	),
	'sort_options' => array(
		'position desc' => t('Rikiuoti pagal'),
		'name asc' => t('Pavadinimą'),
		'date desc' => t('Datą'),
		'discount desc' => t('Didžiausią nuolaidą'),
		'price asc' => t('Mažiausią kainą'),
		'price desc' => t('Didžiausią kainą'),
	),
	'translated_fields' => array('name','description', 'short_description','url')
);

