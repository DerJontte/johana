<?php


use const db_migrations\NOT_NULL;
use const db_migrations\PK;

class Model_User extends Model {
	protected $fields = array(
		array('ID', 'int', array(PK)),
		array('name', 'varchar(255)', array(NOT_NULL)),
		array('sex', 'tinyint'),
		array('salary', 'int')
	);
}