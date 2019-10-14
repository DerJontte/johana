<?php

/***
 * This is the entrypoint of the app. Nothing is rendered here, just a bunch of
 * configuration stuff. At the last line a dispathcer is called, which parses the
 * URI in the location bar and invokes the correct controller and methods.
 */

// Global constants
const IN_PRODUCTION = true;
const VERBOSE_ERRORS = true;
const REQUIRE_HTTPS = false;

// Application paths
define('APP_PATH', getcwd() . '/');
define('MODEL_DIR', APP_PATH . 'application/model/');
define('VIEW_DIR', APP_PATH . 'application/view/');
define('CONTROLLER_DIR', APP_PATH . 'application/controller/');

define('STATIC_PATH', APP_PATH . 'static/');

// Sistem include directories. The order of the directories is very important. Change them at Your own risk!
$include_dirs = array(
	'system/classes',
	'config',
	'application/controller',
	'application/model',
);

foreach ($include_dirs as $dir) {
	$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
	foreach ($objects as $object) {
		if(preg_match('/.*\.php$/', $object)) require_once $object;
	}
}

try {
	Route::dispatch();
} catch (Exception $e) {
	if (VERBOSE_ERRORS) {
		echo $e->getMessage() . '<br>';
		echo 'Line ' . $e->getLine() . ' in ' . $e->getFile() . '<br>';
	} else {
		Http::redirect(Route::get('404'));
	}
}

