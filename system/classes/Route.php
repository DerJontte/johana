<?php

class Route {

	private static $routings = array();

	private function __construct() {
	}

	public static function set($name, $uri, $options) {

		// If a route with the same name exists, we can't create a new one. Return false.
		if(self::get($name)) return false;

		// Create a new Route_Object with the given parameters and add it to the static array.
		self::$routings[] = new Route_Object(array(
												 'name'     => $name,
												 'uri'      => $uri,
												 'exploded' => explode('/', trim($uri, '/')),
												 'options'  => $options,
											 ));

	}

	/**
	 * Loop through the routes and return the first match, if any.
	 * @param $name
	 * @return bool|mixed
	 */
	public static function get($name) {
		foreach (self::$routings as $route) {
			if($route->get_name() === $name) return $route;
		}
		return false;
	}

	/**
	 * Dispatch a parsed uri to the correct controller and action. Any variables extracted from
	 * the uri is sent via a session variable to the controller.
	 */
	public static function dispatch() {
		session_start();

		if(!$location = self::parse_location()) Http::Error();

		$_SESSION['data'] = $location['data'];
		$route = $location['route'];

		$class = $route->get_controller();
		$instance = new $class();
		$method = $route->get_action();
		$instance->$method();
	}

	/**
	 * Parse the location in the browser's address bar and check if it matches a set route.
	 * If a match is found, return an array consisting of the matching Route_Object and an
	 * array with the variables and their values embedded in the route, if any.
	 *
	 * @return array|bool
	 */
	public static function parse_location() {
		$uri = trim($_SERVER['REQUEST_URI'], '/');
		$uri_arr = explode('/', $uri);

		foreach (self::$routings as $route) {
			$match = true;
			$data = array();
			if(count($uri_arr) != count($route->get_exploded())) continue;
			foreach ($route->get_exploded() as $key => $value) {
				if(substr($value, 0, 1) === '<' && substr($value, -1) === '>') {
					$var_name = preg_replace("/[^a-zA-Z0-9]/", "", $value);
					$data[$var_name] = $uri_arr[$key];
				} else if($value != $uri_arr[$key]) {
					$match = false;
					break;
				}
			}
			if($match) return array('route' => $route, 'data' => $data);
		}
		return false;
	}
}