<?php

$config = array(
    'content' => array(
        'title' => t('Paprastas puslapis'),
        'has_content' => true,
        'controller' => 'pages',
    ),
    'link_to_first_child' => array(
        'title' => t('Nuoroda į pirmą gilesnį puslapį'),
        'has_content' => false,
        'controller' => 'pages',
    ),
    'internal_link' => array(
        'title' => t('Nuoroda į svetainės puslapį'),
        'has_content' => false,
        'controller' => 'pages',
    ),
    'link' => array(
        'title' => t('Nuoroda (URL)'),
        'has_content' => false,
        'controller' => 'pages',
    ),
    'external_link' => array(
        'title' => t('Nuoroda naujame lange (URL)'),
        'has_content' => false,
        'controller' => 'pages',
    ),
    'index' => array(
        'title' => t('Titulinis puslapis'),
        'has_content' => true,
        'controller' => 'pages',
    ),
    'all_news' => array(
        'title' => t('Visi naujienų įrašai'),
        'read_only' => true,
        'has_content' => false,
        'controller' => 'news',
        'module' => 'news',
    ),
    'news' => array(
        'title' => t('Naujienų kategorija'),
        'read_only' => true,
        'has_content' => true,
        'controller' => 'news',
        'module' => 'news',
    ),
    'search' => array(
        'title' => 'Paieška',
        'read_only' => true,
        'has_content' => false,
        'controller' => 'search',
    ),
    'products' => array(
        'title' => 'Produktų katalogas',
        'has_content' => true,
        'controller' => 'products'
    ),
    'brands' => array(
        'title' => 'Prekių ženklai',
        'read_only' => true,
        'has_content' => true,
        'module' => 'brands',
        'controller' => 'products',
    ),
);