<?php
abstract class ExceptionHandler extends Exception {
	public function defaultHandler($code = 503) {
		http_response_code(503);
		exit(parent::getMessage());
	}
}
?>