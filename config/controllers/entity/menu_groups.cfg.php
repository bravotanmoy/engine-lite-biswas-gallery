<?php

$config = array(
	'sort_by' => 'position',
	'photos' => true,
	'translated_fields' => array('name', 'show_all_button_text', 'link'),
    'menu_type_options' => $menu_type = [
        'main' => t('Pagrindinis meniu'),
        'footer' => t('Apatinis meniu')
    ],
    'show_type_options' => $menu_type = [
        'simple' => t('Paprastas meniu'),
        'mega_menu' => t('Mega meniu')
    ],
    'type_options' => [
        'list' => t('Nuoroda arba sąrašas'),
        'category_list' => t('Kategorijų sąrašas'),
        'category' => t('Kategorija'),
        'page_list' => t('Puslapių sąrašas'),
        'page' => t('Puslapis'),
        'brand_list' => t('Prekių ženklai'),
    ],
    'filter_config' => array(
        'menu_type' => array(
            'title' => t('Meniu tipas'),
            'input_type' => 'select',
            'filter_type' => 'field',
            'options' => array_replace(array(''=>'...'),$menu_type),
        ),
    ),
    'languages' => 'multi',
);
