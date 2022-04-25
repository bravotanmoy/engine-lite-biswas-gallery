<?php
//if ('elab' != $_SERVER['PHP_AUTH_USER'] || 'demo123' != $_SERVER['PHP_AUTH_PW']) {
//    header('WWW-Authenticate: Basic realm="Restricted area"');
//    header('HTTP/1.0 401 Unauthorized');
//    echo 'Forbidden.';
//    exit;
//}
require_once 'config/core.cfg.php';
require_once 'vendor/autoload.php';
\Elab\Lite\Engine::run();
