<?php
namespace Template;

class Template {
	private $filename   = '';
	private $parameters = [];

	#===============================================================================
	# Create instance
	#===============================================================================
	public function __construct($filename) {
		$this->filename = $filename;

		if(!file_exists($filename)) {
			throw new Exception("Template file \"{$filename}\" does not exist.");
		}
	}

	#===============================================================================
	# Set parameter
	#===============================================================================
	public function set($name, $value) {
		return $this->parameters[$name] = $value;
	}

	#===============================================================================
	# Get parameter
	#===============================================================================
	public function get($name) {
		return $this->parameters[$name] ?? NULL;
	}

	#===============================================================================
	# Return HTML
	#===============================================================================
	public function __toString() {
		extract($this->parameters);

		ob_start();
		require $this->filename;
		return ob_get_clean();
	}
}
?>