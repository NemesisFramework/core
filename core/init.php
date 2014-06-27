<?php

define('NEMESIS_PATH', str_replace('\\', '/', str_replace('//', '/', str_replace('core', '', __DIR__))));

// BASE URL
define('NEMESIS_ROOT', str_replace('//', '/', dirname($_SERVER['SCRIPT_NAME']) . '/'));
define('NEMESIS_SCHEME', ( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://');
define('NEMESIS_PORT', (isset($_SERVER['SERVER_PORT']) && (($_SERVER['SERVER_PORT'] != '80' && NEMESIS_SCHEME == 'http://') || ($_SERVER['SERVER_PORT'] != '443' && NEMESIS_SCHEME == 'https://')) && strpos($_SERVER['HTTP_HOST'], ':') === false) ? ':'.$_SERVER['SERVER_PORT'] : '');
define('NEMESIS_HOST', preg_replace('/:'.NEMESIS_PORT.'$/', '', $_SERVER['HTTP_HOST']));
define('NEMESIS_URL', str_replace('\\', '', (trim( urldecode( NEMESIS_SCHEME . NEMESIS_HOST )). str_replace('//', '/', NEMESIS_ROOT.'/'))));

// PATHS
define('CORE', NEMESIS_PATH.'core/');
define('PLUGINS', NEMESIS_PATH.'plugins/');
define('APPS', NEMESIS_PATH.'apps/');
define('CACHE', NEMESIS_PATH.'cache/');
define('LOGS', NEMESIS_PATH.'logs/');


// ERRORS
error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('log_errors', 'On');
ini_set('error_log', LOGS.'errors.log');
ini_set('ignore_repeated_errors', 'On');

// Loader
require_once CORE . 'class.Loader.php';
$NEMESIS = Loader::getInstance();
