<?php

/**
 * Class Controller
 *
 * This is the base controller class that all controllers should extend from in order
 * to have access to the request-object.
 */

abstract class Controller {
	protected $request;

	public function __construct() {
		$data = $_SESSION['data'];

		$this->request = new ArrayObject();

		// Populate request-object with default values
		$this->request->stylesheets = array(CSS_DIR . 'default.css');
		$this->request->inline_scripts = array();
		$this->request->scripts = array();

		// If the route contains variables in the URI, store them in the request.
		if(!is_null($data)) {
			foreach ($data as $key => $value) {
				$this->request->$key = $value;
			}
		}
	}

	public function render() {
		$data = array (
			'body' => $this->request->template,
			'stylesheets' => $this->request->stylesheets,
			'inline_scripts' => $this->request->inline_scripts,
			'scripts' => $this->request->scripts,
		);
		echo View::factory('default/template', $data);
	}
}