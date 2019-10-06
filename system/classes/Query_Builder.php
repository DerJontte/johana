<?php

class Query_Builder {
	private $query = '';
	private $connection = null;

	public function __construct($query_type = null, $args = null) {
		$driver = DB_DRIVER;
		$this->connection = new $driver(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if(isset($this->connection->connect_error)) {
			print_r($this->connection->connect_error);
			die();
		}

		if(!is_null($query_type) && (is_array($args) || is_string($args))) {
			$this->query .= $query_type;
			if(is_array($args)) foreach ($args as $arg) {
				$this->query .= ' ' . $arg;
			} else {
				$this->query .= ' ' . $args;
			}
		}
	}

	public function from($table) {
		$this->query .= ' FROM ' . $table;
		return $this;
	}

	public function where($op1, $condition, $op2) {
		if(!$condition = $this->validate_condition($condition)) return;
		$this->query .= ' WHERE ' . $op1 . ' ' . $condition . ' "' . $op2.'"';
		return $this;
	}

	public function execute() {
		$result = $this->connection->query($this->query);
		return $result;
	}

	public function __toString() {
		return htmlspecialchars($this->query);
	}

	public function validate_condition($condition) {
		$condition = strtoupper($condition);
		$valid_array = array('=', '!=', 'LIKE', 'NOT LIKE', 'IN');
		return in_array($condition, $valid_array) ? $condition : false;
	}
}