<?php


class Model_Address extends Model {
	protected $fields = array(
		array('ID', 'int', array(PK)),
		array('street', 'varchar(255)', array(NOT_NULL)),
		array('number', 'int'),
		array('zip', 'int'),
		array('city', 'varchar(128)'),
	);
}