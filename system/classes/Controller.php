<?php

/**
 * Class Controller
 *
 * This is the base controller class that all controllers should extend from in order
 * to have access to the request-object.
 */

class Controller {
	public function __construct() {
		$data = $_SESSION['data'];

		if(!is_null($data)) {
			$this->request = new ArrayObject();
			foreach ($data as $key => $value) {
				$this->request->$key = $value;
			}
		}
	}
}