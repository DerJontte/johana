<?php

class File {
	public static function get_contents($file_path) {
		$handle = fopen($file_path, "r");
		$contents = fread($handle, filesize($file_path));
		fclose($handle);
		return $contents;
	}
}