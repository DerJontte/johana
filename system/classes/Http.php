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
}