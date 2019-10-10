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

	public static function redirect($url) {
		header("Location: " . $url);
	}

	public static function require_https() {
		if ((empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'off') && REQUIRE_HTTPS) {
			throw new Exception('Credentials can only be sent over a secure connection, please use HTTPS.');
		}
	}
}