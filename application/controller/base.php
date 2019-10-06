<?php

class Controller_Base extends Controller {

	public function action_index() {
		$data = array();
		echo View::factory('index', $data);
	}
}