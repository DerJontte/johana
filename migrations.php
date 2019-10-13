<?php

namespace db_migrations;

use mysql_xdevapi\Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

require_once 'config/database.php';
require_once 'system/classes/DB.php';
require_once 'system/classes/Model.php';

const PK = "PRIMARY KEY";
const NOT_NULL = "NOT NULL";
const AUTO_INCREMENT = "AUTO INCREMENT";
const FK = "FOREIGN KEY REFERENCES";

$migrations = array(
	'users_adresses' => array(
		array('user_id', 'int', array(FK, 'users.ID', NOT_NULL)),
		array('address_id', 'int', array(NOT_NULL, FK, 'addresses.ID')),
	),
);

foreach ($migrations as $class_name => $table_columns) {
//	\DB::create_table($table_name, $table_columns);
}

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('application/model'));
foreach ($objects as $object) {
	if(preg_match('/.*\.php$/', $object)) {
		require_once $object;
		$class_name = str_replace('/', '_', ltrim(rtrim($object, '.php'), 'application/'));

		$table_name = ltrim($class_name, 'model_');
		$table_name .= substr($table_name, -1) == 's' ? 'es' : 's';

		$class = new $class_name;
		$table_columns = $class->get_fields();
		\DB::create_table($table_name, $table_columns);
	}
}


