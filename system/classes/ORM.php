<?php

class ORM {
	public $connection = null;
	public $query = null;
	public $distinct = null;
	public $query_columns = array();
	public $from_table = null;
	public $join_array = array();
	public $where_array = array();
	public $order_by_array = array();
	public $limit = null;
	public $model = null;
	public $group_by = null;

	private $has_multiple_where = false;
	private $has_multiple_select = false;
	private $last_command = null;

	public function __construct($query_type = null, $args = null, $db_table = null) {
		$this->connection = DB::connection();

		$this->model = !empty(get_called_class()) ? get_called_class() : null;
		$this->from_table = !is_null($this->model) ? strtolower(trim($this->model, 'Model_')) : $db_table;

		if(!is_null($query_type)) {
			$this->$query_type($args);
		} else {
			$this->select();
		}
	}

	public function __toString() {
		return htmlspecialchars($this->query);
	}

	public function select($columns = null) {
		if($this->query_columns == array('*')) $this->query_columns = array();

		if(!is_null($columns)) {
			$columns = is_array($columns) ? $columns : array($columns);

			foreach ($columns as $idx => $column) {
				if(strpos($column, '*') === false) {
					if(strpos($column, '.') === false) {
						$column = $this->from_table . '.' . $column;
					}
					if(!strpos($column, 'AS')) {
						$column .= ' AS ' . str_replace('.', '_', $column);
					}
				}
				$columns[$idx] = $column;
			}
		} else {
			$columns = array('*');
		}

		$this->query_columns = array_merge($this->query_columns, $columns);

		if(!$this->has_multiple_select) {
			$this->query = 'SELECT ';
			$this->has_multiple_select = true;
		}

		$this->last_command = __FUNCTION__;
		return $this;
	}

	public function where($column, $condition, $op) {
		$condition = $this->validate_condition($condition);

		$cmd = !$this->has_multiple_where ? ' WHERE ' : ' AND ';
		$this->has_multiple_where = true;

		$statement = sprintf(' %s %s %s ', $cmd, $column, $condition);

		if($condition === 'BETWEEN' && is_array($op) && count($op) == 2) {
			$op[0] = is_numeric($op[0]) ? $op[0] : '"' . $op[0] . '"';
			$op[1] = is_numeric($op[1]) ? $op[1] : '"' . $op[1] . '"';
			$statement .= sprintf('%s AND %s', $op[0], $op[1]);
		} else {
			$statement .= sprintf('%s', $op);
		}
		$this->where_array[] = $statement;
		$this->last_command = __FUNCTION__;
		return $this;
	}

	public function distinct() {
		$this->distinct = ' DISTINCT ';
		$this->last_command = __FUNCTION__;
		return $this;
	}

	public function from($table) {
		$this->from_table = $table;
		$this->last_command = __FUNCTION__;
		return $this;
	}

	public function join($other_table, $option = null) {
		$type = !is_null($option) && $this->validate_join($option) ? ' ' . $option : '';
		$this->join_array[] = sprintf('%s JOIN %s ', $type, $other_table);
		$this->last_command = __FUNCTION__;
		return $this;
	}

	public function order_by($column, $direction) {
		$this->validate_order_by($direction);
		$statement = sprintf(' ORDER BY %s %s', $column, $direction);
		$this->order_by_array[] = $statement;
		$this->last_command = __FUNCTION__;
		return $this;
	}

	public function limit($limit = null) {
		if(!is_null($limit) && is_numeric($limit)) {
			$this->limit = $limit;
		}
		$this->last_command = __FUNCTION__;
		return $this;
	}

	public function group_by($variable) {
		if(!is_null($variable)) {
			if(strpos($variable, '.') === false) $variable = $this->from_table . '.' . $variable;
			$this->group_by = $variable;
		}
		$this->last_command = __FUNCTION__;
		return $this;
	}

	public function count() {
		if($this->last_command == 'select') {
			$last_element = array_pop($this->query_columns);
			$this->query_columns[] = ' COUNT(' . $last_element . ') ';
		}
		$this->last_command = __FUNCTION__;
		return $this;
	}

	public function find() {
		$this->last_command = __FUNCTION__;
		return $this->limit(1)->execute();
	}

	public function find_all() {
		$this->last_command = __FUNCTION__;
		return $this->execute();
	}

	public function execute() {
		$this->query .= $this->distinct;

		foreach ($this->query_columns as $idx => $column) {
			if(strpos($column, '.') === false && strpos($column, 'COUNT') === false) {
				$this->query_columns[$idx] = $this->from_table . '.' . $column;
			}
		}
		$this->query .= ' ' . implode(', ', $this->query_columns);

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

		if(!is_null($this->group_by)) {
			$this->query .= ' GROUP BY ' . $this->group_by;
		}

		if(!is_null($this->limit)) $this->query .= ' LIMIT ' . $this->limit;

		$result = $this->connection->query($this->query);

		$this->last_command = __FUNCTION__;
		return $result;
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
