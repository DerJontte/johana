<?php

/***
 * This is the entrypoint of the app. Nothing is rendered here, just a bunch of
 * configuration stuff. At the last line a dispathcer is called, which parses the
 * URI in the location bar and invokes the correct controller and methods.
 */

// Global constants.
define('APP_PATH', getcwd() . '/');
define('VIEW_DIR', APP_PATH.'application/view/');
define('CONTROLLER_DIR', APP_PATH.'application/controller/');

// The order of the include dir's is very important. Change them at Your own risk!
$include_dirs = array(
	'system',
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

$result = DB::select('*')->from('db')->where('Db', '=', 'sys')->execute();

foreach ($result as $row) {
	echo print_r($row).'<br>';
}
//Route::dispatch();