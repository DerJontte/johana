<?php

/**
 * Class DB_Connection
 * Creates a database connection that can be accessed through the get_instance()-function.
 */

const PK = "PRIMARY KEY";
const NOT_NULL = "NOT NULL";
const AUTO_INCREMENT = "AUTO INCREMENT";
const FK = "FOREIGN KEY REFERENCES";

class DB {
	private static $connection = null;

	/**
	 * DB_Connection constructor is private because singleton.
	 */
	private function __construct() {
	}

	public static function connection() {
		if(is_null(self::$connection)) {
			self::$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if(isset(self::$connection->connect_error)) {
				throw new Exception(self::$connection->connect_error);
			}
		}
		return self::$connection;
	}

	public static function create_table($name, $columns) {
		$connection = self::connection();

		$query = sprintf("CREATE TABLE IF NOT EXISTS %s (", $name);

		$column_count = count($columns) - 1;
		foreach ($columns as $idx => $column) {
			$name = $column[0];
			$type = $column[1];
			$options = '';
			$foreign_keys = '';

			if(count($column) == 3) {
				$option_arr = $column[2];
				while (!empty($option_arr)) {
					$element = array_shift($option_arr);
					if($element == FK) {
						$reference = str_replace('.', '(', array_shift($option_arr)) . ')';
						$foreign_keys .= ', FOREIGN KEY (' . $name . ')' . ' REFERENCES ' . $reference;
					} else {
						$options .= ' ' . $element;
					}
				}
			}
			$query .= $name . ' ' . $type . $options . $foreign_keys . ($idx == $column_count ? ');' : ', ');
		}

		echo $query . PHP_EOL;
		$result = $connection->query($query);
	}
}