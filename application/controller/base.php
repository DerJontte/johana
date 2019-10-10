<?php

class Controller_Base extends Controller {

	public function action_index() {
		Auth::login(null, null);
		$users = new Model_User();
		$users->select('Host')->select('User');
		$users->join('db')->select('db.User as dbuser');
		$users = $users->find_all();

		// TODO: REMOVE
		print_r($users);echo '<br><br>';
		foreach ($users as $user){
			print_r($user);echo '<br><br>';
		}
//		Auth::required(array('logged', 'admin'));
	}

	public function action_404() {
		echo '404 error';
	}
}