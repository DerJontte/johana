<?php

/**
 * Define Your routes here.
 */

Route::set('404', '/notfound', array(
	'controller' => 'base',
	'action' => '404',
));

Route::set('home', '/', array(
	'controller' => 'base',
	'action'     => 'index',
));
