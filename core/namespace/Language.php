<?php
class Language {
	private $code = '';
	private $text = [];

	public function __construct($code) {
		$this->code = $code;
	}

	#===============================================================================
	# Return the language code
	#===============================================================================
	public function getCode() {
		return $this->code;
	}

	#===============================================================================
	# Load another language file
	#===============================================================================
	public function load($filename) {
		if(file_exists($filename) and is_readable($filename)) {
			require $filename;
			$this->text = array_merge($this->text, $LANGUAGE ?? []);
		}
	}

	#===============================================================================
	# Set language string
	#===============================================================================
	public function set($name, $value) {
		return $this->text[$name] = $value;
	}

	#===============================================================================
	# Return language string with included arguments
	#===============================================================================
	public function text($name, $arguments = null): string {
		if(!isset($this->text[$name])) {
			return "{{$name}}";
		}

		return vsprintf($this->text[$name], (array) $arguments);
	}
}
