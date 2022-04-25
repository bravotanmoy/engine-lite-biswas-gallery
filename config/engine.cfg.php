<?php

@include 'config/constants.cfg.php';

@define('PROJECT_DOMAIN', preg_replace('/^www\./', '', $_SERVER['SERVER_NAME']));
$_domain = PROJECT_DOMAIN;
$_domain_www = "www.$_domain";
$_domain_underscore = str_replace('.', '_', $_domain);
$_domain_underscore_www = 'www_' . $_domain_underscore;

$config = array(
    'domain_name' => $_domain_www, // www.svetaine.lt,
    'project_name' => ucfirst($_domain), //'Svetaine.lt',
    'info_email' => "no-reply@$_domain",
    'backend_session_name' => $_domain_underscore . '_e_ngine', // svetaine_lt_e_ngine_lite
    'frontend_session_name' => $_domain_underscore_www, // www_svetaine_lt
    'remember_cookie_name' => $_domain_underscore . '_remember', // svetaine_lt_remember
    'rc4_key' => 'FFFF5iTIYNl42LO9',
    'session_timeout' => 24 * 60 * 60, // sekundemis, 0 - sesijos skaitliukas išjungtas
);
