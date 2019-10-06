<?php

class Controller_Base extends Controller {

	public function action_index() {
		$fruits = array(
			'apple' => 'good',
			'orange' => 'yellow',
			'pear' => 'green',
			'lemon' => 'sour'
		);

		$data = array('fruits' => $fruits);

		echo View::factory('base/index', $data);
	}

	public function action_echo_id() {
		$data = array(
			'id' => $this->request->id,
		);

		echo View::factory('base/echo_id', $data);
	}
}