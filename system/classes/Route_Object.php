<?php

class Route_Object {

	private $name;
	private $uri;
	private $exploded;
	private $action;
	private $controller;

	public function __construct($props) {
		foreach ($props as $key => $value) {
			if($key == 'options') {
				foreach ($value as $option_key => $option_value) {
					$this->$option_key = $option_value;
				}
			}
			$this->$key = $value;
		}
		return $this;
	}

	public function uri($props = null) {
		$uri = '';

		foreach ($this->exploded as $var) {
			if(substr($var, 0, 1) === '<' && substr($var, -1) === '>') {
				$var_name = preg_replace("/[^a-zA-Z0-9]/", "", $var);
				if(isset($props[$var_name])) {
					$var = $props[$var_name];
				}
			}
			$uri .= '/' . $var;
		}
		return $uri;
	}

	/**
	 * @return mixed
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function get_uri() {
		return htmlspecialchars($this->uri);
	}

	/**
	 * @return mixed
	 */
	public function get_exploded() {
		return $this->exploded;
	}

	/**
	 * @return mixed
	 */
	public function get_action() {
		return 'action_' . $this->action;
	}

	/**
	 * @return mixed
	 */
	public function get_controller() {
		return 'Controller_' . $this->controller;
	}

	public function __toString() {
		return $this->get_uri();
	}
}