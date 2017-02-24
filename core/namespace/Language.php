<?php
class Language {
	private $language = [];
	private $template = [];

	public function __construct($lang) {
		require ROOT."core/language/{$lang}.php";
		$this->language = $LANGUAGE;
	}

	public function loadLanguage($filename) {
		require $filename;
		$this->template = $LANGUAGE;
	}

	public function template($name, $params = FALSE) {
		if(isset($this->template[$name])) {
			if($params) {
				return vsprintf($this->template[$name], $params);
			}

			return $this->template[$name];
		}

		return "{{$name}}";
	}

	private function get($name, $params = FALSE) {
		if(isset($this->language[$name])) {
			if($params) {
				return vsprintf($this->language[$name], $params);
			}

			return $this->language[$name];
		}

		return "{{$name}}";
	}

	public function text($name, $params = FALSE) {
		return $this->get($name, $params);
	}

	public function set($name, $value) {
		return $this->language[$name] = $value;
	}
}
?>