<?php
// Version
define('VERSION', '3.0.2.0');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
} else {
	echo 'Could not find configuration file!';
	exit;
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

start('catalog');