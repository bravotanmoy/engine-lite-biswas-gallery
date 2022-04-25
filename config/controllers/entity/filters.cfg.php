<?php

$config = array(
	'sort_by' => 'position',
	'priority_options' => array(
		1 => 'Top',
		2 => 'Bottom',
	),
	'compare_options' => array(
		1 => 'Pagal kiekį',
		2 => 'A-Z',
		3 => 'Z-A',
	),
	'method_options' => array(
		1 => 'or',
		2 => 'ir',
	),
	
	'translated_fields' => array('name'),
	'input_types' => array(
		'text' => t('Įvedama reikšmė'),
		'select' => t('Pasirenkama reikšmė'),
        'checkbox' => t('Pasirinkimas taip/ne'),
		'checkboxes' => t('Pasirenkamos reikšmės'),
    ),
	
	'scope' => array(
		1 => t('Produktai'),
		2 => t('Modifikacijos'),
		3 => t('Prekės (SKU)'),
	)
);
