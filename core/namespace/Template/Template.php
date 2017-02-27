<?php
namespace Template;

class Template {
	private $filename   = '';
	private $parameters = [];

	#===============================================================================
	# Create template instance
	#===============================================================================
	public function __construct($filename) {
		$this->filename = $filename;

		if(!file_exists($filename)) {
			throw new Exception("Template {$filename} does not exists.");
		}
	}

	#===============================================================================
	# Set value to array path
	#===============================================================================
	public function set($name, $value) {
		if(!is_array($name)) {
			return $this->parameters[$name] = $value;
		}

		$current = &$this->parameters;

		foreach($name as $path) {
			if(!isset($current[$path])) {
				$current[$path] = [];
			}
			$current = &$current[$path];
		}

		return $current = $value;
	}

	#===============================================================================
	# Add value as item to array path
	#===============================================================================
	public function add($paths, $value) {
		if(!is_array($paths)) {
			return $this->parameters[$paths][] = $value;
		}

		$current = &$this->parameters;

		foreach($paths as $path) {
			if(!isset($current[$path])) {
				$current[$path] = [];
			}
			$current = &$current[$path];
		}

		return $current[] = $value;
	}

	#===============================================================================
	# Return parsed template content
	#===============================================================================
	public function __toString() {
		extract($this->parameters);

		ob_start();
		require $this->filename;
		return ob_get_clean();
	}
}
?>