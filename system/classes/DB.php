<?php


class DB {

	public static function select($columns = null) {
		return new Query_Builder('SELECT', $columns);
	}
}