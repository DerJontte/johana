<?php

class Query_Builder {
	private $connection = null;
	private $has_where = false;

	private $query = '';
	private $distinct = '';
	private $query_columns = array();
	private $from_table = '';
	private $join_array = array();
	private $where_array = array();
	private $order_by_array = array();

	public function __construct($query_type, $args = null) {
		$driver = DB_DRIVER;
		$this->connection = new $driver(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if(isset($this->connection->connect_error)) {
			print_r($this->connection->connect_error);
			die();
		}

		$this->query = $query_type;

		if(!is_null($args)) {
			$this->query_columns = is_array($args) ? $args : array($args);
		} else {
			$this->$this->query_columns = array('*');
		}
	}

	public function distinct() {
		$this->distinct = ' DISTINCT ';
		return $this;
	}

	public function from($table) {
		$this->from_table = $table;
		return $this;
	}

	public function where($column, $condition, $op) {
		$condition = $this->validate_condition($condition);

		$cmd = !$this->has_where ? ' WHERE ' : ' AND ';
		$this->has_where = true;

		if(strpos($column, '.') === false) {
			$column = $this->from_table . '.' . $column;
		}

		$statement = sprintf(' %s %s %s ', $cmd, $column, $condition);

		if($condition === 'BETWEEN' && is_array($op) && count($op) == 2) {
			$op[0] = is_numeric($op[0]) ? $op[0] : '"' . $op[0] . '"';
			$op[1] = is_numeric($op[1]) ? $op[1] : '"' . $op[1] . '"';
			$statement .= sprintf('%s AND %s', $op[0], $op[1]);
		} else {
			$op = is_numeric($op) ? $op : '"' . $op . '"';
			$statement .= sprintf('%s', $op);
		}
		$this->where_array[] = $statement;
		return $this;
	}

	public function join($other_table, $option = null) {
		$type = !is_null($option) && $this->validate_join($option) ? ' ' . $option : '';
		$this->join_array[] = sprintf('%s JOIN %s ', $type, $other_table);
		return $this;
	}

	public function order_by($column, $direction) {
		$this->validate_order_by($direction);
		$statement = sprintf(' ORDER BY %s %s', $column, $direction);
		$this->order_by_array[] = $statement;
		return $this;
	}

	public function execute() {
		$this->query .= $this->distinct;

		foreach ($this->query_columns as $column) {
			if(strpos($column, '.') == false) {
				$column = $this->from_table . '.' . $column;
			}
			$this->query .= ' '.$column;
		}

		$this->query .= ' FROM ' . $this->from_table;

		foreach ($this->join_array as $join) {
			$this->query .= $join;
		}

		foreach ($this->where_array as $where) {
			$this->query .= $where;
		}

		foreach ($this->order_by_array as $order) {
			$this->query .= $order;
		}

		print_r($this->query);
		echo '<br>';
		$result = $this->connection->query($this->query);
		return $result;
	}

	public function __toString() {
		return htmlspecialchars($this->query);
	}

	public function validate_condition($condition) {
		$condition = strtoupper($condition);
		$valid_array = array('=', '<=', '<', '>=', '>', '!=', '<>', 'LIKE', 'NOT LIKE', 'IN', 'BETWEEN');

		if(!in_array($condition, $valid_array)) {
			throw new InvalidArgumentException('Illegal operator in where clause: ' . $condition);
		}
		return $condition;
	}

	public function validate_join($join) {
		$join = strtoupper($join);
		$valid_array = array('INNER', 'LEFT', 'RIGHT', 'FULL');

		if(!in_array($join, $valid_array)) {
			throw new InvalidArgumentException('Illegal operator in join clause: ' . $join);
		}
		return $join;
	}
	public function validate_order_by($direction) {
		$direction = strtoupper($direction);
		$valid_array = array('ASC', 'DESC');

		if(!in_array($direction, $valid_array)) {
			throw new InvalidArgumentException('Illegal argument in order by clause: ' . $direction);
		}
		return $direction;
	}
}