<?php
/**
 * Sistemos konstantos. Pirmiausia yra includinamas failas iš projekto, todėl ten galima apibrėžti konstantas, jeigu
 * netinka default reikšmės. Šiame faile turi būti tikrinama, ar dar nėra apibrėžta ir tik tuo atve, jei ne,
 * apibrėžiama.
 */
@define('TIMEZONE', 'Europe/Vilnius');
@define('NOW', date('Y-m-d H:i:s'));

// kokia simboliu kodavimo lentele naudosime?
@define('CHARSET', 'utf-8');
@define('SQL_CHARSET', 'utf8');

//numatytos projekto kalbos kodas
@define('DEFAULT_LANG', 'lt');
//einamosios projekto kalbos kodas
@define('LANG', DEFAULT_LANG);

@define('CACHE_TIMEOUT', 5 * 60); // 5 minutes
@define('CACHE_ENABLED', true); // 5 minutes

$scheme = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
$path = trim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

if ($path)
    $path .= "/";
$project_url = "$scheme://$host/$path";
@define('PROJECT_URL', $project_url);
@define('PROJECT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/' . $path);
$project_url = PROJECT_URL;

@define('RESOURCES_URL', $project_url . "res/");
@define('LANG_URL', PROJECT_URL . LANG . "/");

$uri = preg_replace('@^/+@', '', $_SERVER['REQUEST_URI']);
@define('FULL_URL', "$scheme://$host/$uri");
$uri = preg_replace('/[?#].*$/', '', $uri);
@define('FULL_URL_TRUNC', "$scheme://$host/$uri");


// matavimai
@define('IMAGE_MAX_SIZE', 5 * 1024 * 1024);
@define('EXPIRES_HEADER_TIME', 60 * 60 * 24 * 256);

@define('VERSION', '20190208');

define('LIVE_URL', '<your_domain>');

define('GALLERY_API_HOST','https://gallery-api.engine.lt');

// Gallery Image Login
define('api_email','uptest@gmail.com');
define('api_password','abcdef');