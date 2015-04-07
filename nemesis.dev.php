<?php
/*
	Bootstrap Dev
*/

require_once 'nemesis.php';

// ERRORS
if (!file_exists(NEMESIS_PATH.'errors.log'))
  touch(NEMESIS_PATH.'errors.log');

error_reporting(E_ALL);
ini_set('log_errors', true);
ini_set('ignore_repeated_errors', true);
ini_set('error_log', NEMESIS_PATH.'errors.log');