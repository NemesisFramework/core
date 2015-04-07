<?php
/*
	Bootstrap Dev
*/

require_once 'nemesis.php';

// ERRORS
error_reporting(E_ALL);
ini_set('log_errors', true);
ini_set('ignore_repeated_errors', true);
touch(NEMESIS_PATH.'errors.log');
ini_set('error_log', NEMESIS_PATH.'errors.log');