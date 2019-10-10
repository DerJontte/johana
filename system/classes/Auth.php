<?php


class Auth {
	public static function login($username, $password) {
		Http::require_https();

	}

	public static function required(array $roles) {
		foreach ($roles as $role) {
			if(!in_array($role, $_SESSION['roles'])) {
				throw new Exception('Unauthorized user.');
			}
		}
	}
}