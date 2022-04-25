<?php

define('PROJECT_MODE', 'development'); // prod: production, dev: development
define('DEBUG_MODE', 1); // prod: 0, dev: 1

/**
 * Error handler configs
 */
define ('ERROR_REPORTING_MODE', E_ALL); // prod: E_ERROR | E_PARSE, dev: E_ALL
ini_set('display_errors','On'); // prod: Off, dev: On
ini_set('log_errors', 'On');
ini_set('error_log', 'logs/engine.error.log');
error_reporting(ERROR_REPORTING_MODE);