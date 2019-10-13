<?php


class Http {
	public static function Error($message = null) {
		if(!is_null($message)) {
			echo 'Error: ' . $message;
		} else {
			echo 'Sorry dude. 404!';
		}
		die();
	}

	/**
	 * Immediately redirect the browser to a new location.
	 *
	 * @param $url - the URL to redirect to
	 */
	public static function redirect($url) {
		header("Location: " . $url);
	}

	/**
	 * Send data exactly as is.
	 * With great power comes great responsibility!
	 *
	 * @param $application_type - MMIME type
	 * @param $data - the data to be sent
	 */
	public static function send_raw_data($application_type, $data) {
		header($application_type);
		echo $data;
		die();
	}

	/**
	 * Function to check that the user is on a secure connection. Use for passwords and other
	 * sensitive information.
	 *
	 * This function has no return value, since it throws an exception if the neccessary
	 * security measures is not in place.
	 *
	 * @throws Exception
	 */
	public static function require_https() {
		if ((empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'off') && REQUIRE_HTTPS) {
			throw new Exception('Credentials can only be sent over a secure connection, please use HTTPS.');
		}
	}
}