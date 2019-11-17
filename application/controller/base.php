<?php

class Controller_Base extends Controller {

	public function action_index() {
//		Auth::login(null, null);
//		Auth::required(array('logged', 'admin'));
		$users = new Model_User();
		$users->select('Host')->select('user'); // SENSITIVE DATA! NEVER EXPOSE IN PRODUCTION!
		$users = $users->find_all();

		$data = array(
			'message' => 'Hello world!',
			'users' => $users
		);

		$this->request->template = View::factory('base', $data);
	}

	public function action_get_css_file() {
		$file = $this->request->file;
		$file_path = STATIC_PATH . '/css/' . $file;

		$contents = File::get_contents($file_path);

		Http::send_raw_data('Content-type: text/css', $contents);
	}

	public function action_get_js_file() {
		$file = $this->request->file;
		$file_path = STATIC_PATH . '/js/' . $file;

		$contents = File::get_contents($file_path);

		Http::send_raw_data('Content-type: application/javascript', $contents);
	}
}