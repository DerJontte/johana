<?php

class Controller_Base extends Controller {

	public function action_index() {
//		Auth::login(null, null);
//		Auth::required(array('logged', 'admin'));
		$users = new Model_User();
		$users->select('Host')->select('User')->join('db')->select('db.User')->order_by('user.User', 'asc');
		$users = $users->find_all();

		$data = array(
			'message' => 'Hello world!',
			'users' => $users
		);

		$this->request->template = View::factory('base', $data);
	}

	public function action_404() {
		echo '404 error';
	}
}