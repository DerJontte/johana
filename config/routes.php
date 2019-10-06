<?php

/**
 * Define Your routes here.
 */

Route::set('home', '/', array(
	'controller' => 'base',
	'action'     => 'index',
));

Route::set('test', '/test', array(
	'controller' => 'base',
	'action'     => 'index',
));

Route::set('echo_given_id', '/id/<id>', array(
	'controller' => 'base',
	'action'     => 'echo_id',
));
