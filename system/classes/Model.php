<?php
require_once('ORM.php');

use const db_migrations\NOT_NULL;
use const db_migrations\PK;

class Model extends ORM {
	protected $fields = array();

	public function get_fields() {
		return $this->fields;
	}
}