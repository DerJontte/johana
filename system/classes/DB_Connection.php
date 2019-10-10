<?php

class DB_Connection {
	private $connection;

	public function __construct() {
		$driver = DB_DRIVER;
		$this->connection = new $driver(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if(isset($this->connection->connect_error)) {
			throw new Exception($this->connection->connect_error);
		}
	}

	public function query($query) {
		return $this->connection->query($query);
	}
}