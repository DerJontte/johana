<?php

class Controller_Base extends Controller {

	public function action_index() {
		Auth::login(null, null);
//		Auth::required(array('logged', 'admin'));
	}

	public function action_404() {
		echo '404 error';
	}
}