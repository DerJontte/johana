<?php

/**
 * Define Your routes here.
 */

Route::set('css_folder', '/static/css/<file>', array(
	'controller' => 'base',
	'action' => 'get_css_file',
));

Route::set('javascript_folder', '/static/js/<file>', array(
	'controller' => 'base',
	'action' => 'get_js_file',
));

Route::set('home', '/', array(
	'controller' => 'base',
	'action'     => 'index',
));

Route::set('404', '/notfound', array(
	'controller' => 'base',
	'action' => '404',
));

