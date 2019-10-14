<?php

require_once 'config/database.php';
require_once 'system/classes/DB.php';
require_once 'system/classes/Model.php';

class db_migrations {
	private static $migration_file = 'migrations.json';

	/**
	 * Define migrations that are not automatically compiled from Models in
	 * this array.
	 *
	 * The migrations are specified in a nested array, of which the top level
	 * is associative.
	 *
	 * @var array('table_name' => array(array(column), array(column), ...))
	 * @column array('column_name', 'column_type', array(column_constraints))
	 * @column_constraints PK, NOT_NULL, AUTO_INCREMENT, [FK, table.column]
	 */
	private static $manual_migrations = array(
		'users_adresses' => array(
			array('user_id', 'int', array(FK, 'users.ID', NOT_NULL)),
			array('address_id', 'int', array(NOT_NULL, FK, 'addresses.ID')),
		),
	);

	/**
	 * Merge the manually created migrations with migrations created from the Models.
	 * @return array
	 */
	static function makemigrations() {
		$migrations_array = self::$manual_migrations;

		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('application/model'));
		foreach ($files as $file) {
			if(preg_match('/.*\.php$/', $file)) {
				require_once $file;

				$class_name = str_replace('/', '_', ltrim(rtrim($file, '.php'), 'application/'));
				$table_name = ltrim($class_name, 'model_');
				$table_name .= substr($table_name, -1) == 's' ? 'es' : 's';

				$class = new $class_name;
				$table_columns = $class->get_fields();

				$migrations_array[$table_name] = $table_columns;
			}
		}
		return $migrations_array;
	}

	/**
	 * Recursively rearranges the order of the migrations so that foreign keys are created after
	 * the keys they reference.
	 * @param $migrations
	 * @param array $ordered_migrations
	 * @return array
	 */
	static function arrange_migrations($migrations, $ordered_migrations = array()) {
		$unordered_migrations = array();
		foreach ($migrations as $table => $columns) {
			if(is_null($fk = self::get_foreign_keys($columns))) {
				$ordered_migrations[] = $table;
			}
			// TODO: If any and all parents are in the arranged array, add this migration to the array as well.
			//  If a parent is not in the arranged array, put it in the unordered array and add this element to
			//  the unordered array after it's parent(s).
			//  ALSO: Find a better algorithm.
		}
		if(!empty($unordered_migrations)) self::arrange_migrations($unordered_migrations, $ordered_migrations);
		return $ordered_migrations;
	}

	static function get_foreign_keys() {
		// TODO: Implementation
	}

	/**
	 * Outputs an array with migrations to a json-file.
	 * @param $migrations
	 */
	static function save_migrations($migrations) {
		file_put_contents(self::$migration_file, json_encode($migrations));
	}

	/**
	 * Loads a file with migrations in json format and returns them as a nested array.
	 * @return array
	 */
	static function load_migrations() {
		$migration_file_contents = file_get_contents(self::$migration_file);
		$migrations = json_decode($migration_file_contents);
		return $migrations;
	}

	/**
	 * Deletes the json-file with migrations.
	 */
	static function clear_migrations() {
		if(is_file(self::$migration_file)) unlink(self::$migration_file);
	}

	/**
	 * Creates the database tables specified in the migrations-array.
	 * @param $migrations
	 */
	static function migrate($migrations) {
		echo 'Migrating...' . PHP_EOL;
		foreach ($migrations as $table_name => $table_columns) {
			DB::create_table($table_name, $table_columns);
		}
	}
}

function padded_message($message) {
	return str_pad(' <<< ' . strtoupper($message) . ' >>> ', 120, '-', STR_PAD_BOTH) . PHP_EOL;
}

if(in_array('makemigrations', $argv)) {
	echo padded_message('creating migrations');
	$migrations = db_migrations::makemigrations();
	foreach ($migrations as $table_name => $table_columns) {
		$table_string = $table_name . ': ';
		foreach ($table_columns as $column) $table_string .= sprintf("%s(%s) ", $column[0], $column[1]);
		echo $table_string;
	}

	echo padded_message('optimising migrations');
	db_migrations::arrange_migrations($migrations);

	echo padded_message('saving migrations');
	db_migrations::save_migrations($migrations);

	echo padded_message('done');
}

if(in_array('migrate', $argv)) {
	echo padded_message('loading migrations');
	$migrations = db_migrations::load_migrations();

	echo padded_message('creating database tables');
	db_migrations::migrate($migrations);

	echo padded_message('done');
}

if(in_array('clearmigrations', $argv)) {
	echo padded_message('clearing migrations');
	db_migrations::clear_migrations();

	echo padded_message('done');
}