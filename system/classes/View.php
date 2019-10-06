<?php

class View {

	private $path;
	private $data;

	public static function factory($view_file, $data = null) {
		$view = new self();

		$view->path = VIEW_DIR . $view_file . '.php';
		$view->data = $data;

		return $view;
	}

	public function render() {
		extract($this->data);
		include $this->path;
	}

	public function __toString() {
		return (string) $this->render();
	}
}