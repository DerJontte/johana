<?php

class Controller_Base extends Controller {

	public function action_index() {
//		Auth::login(null, null);
//		Auth::required(array('logged', 'admin'));
		$users = new Model_User();
		$users->select('Host')->select('User')->join('db')->select('db.User')->where('user.User', '=', '"phpmyadmin"');
		$users = $users->find_all();

		// TODO: REMOVE
		if(!empty($users)) foreach ($users as $user){
			$user = str_replace('_', '.', $user);
			print_r($user);echo '<br><br>';
		}
	}

	public function action_404() {
		echo '404 error';
	}
}