<?php


class Auth {
	public static function login($username, $password) {
		if ((empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'off') && IN_PRODUCTION) {
			throw new Exception('Credentials can only be sent over a secure connection, please use HTTPS.');
		}
	}

	public static function required(array $roles) {
		foreach ($roles as $role) {
			if(!in_array($role, $_SESSION['roles'])) {
				throw new Exception('Unauthorized user.');
			}
		}
	}
}