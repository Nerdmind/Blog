<?php
class HTTP {
	private static $GET  = NULL;
	private static $POST = NULL;
	private static $FILE = NULL;

	#===============================================================================
	# HTTP protocol versions
	#===============================================================================
	const VERSION_1_0 = 'HTTP/1.0';
	const VERSION_1_1 = 'HTTP/1.1';
	const VERSION_2_0 = 'HTTP/2.0';

	#===============================================================================
	# HTTP header fields
	#===============================================================================
	const HEADER_ETAG              = 'ETag';
	const HEADER_CONTENT_TYPE      = 'Content-Type';
	const HEADER_TRANSFER_ENCODING = 'Transfer-Encoding';
	const HEADER_ACCESS_CONTROL    = 'Access-Control-Allow-Origin';

	#===============================================================================
	# Values for HTTP header fields
	#===============================================================================
	const CONTENT_TYPE_JSCRIPT = 'application/x-javascript; charset=UTF-8';
	const CONTENT_TYPE_TEXT    = 'text/plain; charset=UTF-8';
	const CONTENT_TYPE_HTML    = 'text/html; charset=UTF-8';
	const CONTENT_TYPE_JSON    = 'application/json; charset=UTF-8';
	const CONTENT_TYPE_XML     = 'text/xml; charset=UTF-8';

	#===============================================================================
	# HTTP status codes
	#===============================================================================
	const RESPONSE_CODE = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Time-out',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Large',
		415 => 'Unsupported Media Type',
		416 => 'Requested range not satisfiable',
		417 => 'Expectation Failed',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Time-out'
	];

	#===============================================================================
	# Initialize $GET, $POST and $FILE
	#===============================================================================
	public static function init($GET, $POST, $FILE, $removeArrays = FALSE, $trimValues = TRUE) {
		self::$GET  = $GET;
		self::$POST = $POST;
		self::$FILE = $FILE;

		if($removeArrays) {
			self::$GET  = self::removeArrayValues(self::$GET);
			self::$POST = self::removeArrayValues(self::$POST);
		}

		self::$GET  = ($trimValues === TRUE ? self::trim(self::$GET)  : self::$GET );
		self::$POST = ($trimValues === TRUE ? self::trim(self::$POST) : self::$POST);
	}

	#===============================================================================
	# Remove all array values inside an array
	#===============================================================================
	private static function removeArrayValues(array $array) {
		return array_filter($array, function($value) {
			return !is_array($value);
		});
	}

	#===============================================================================
	# Trim all strings in argument
	#===============================================================================
	private static function trim($mixed) {
		if(is_array($mixed)) {
			return array_map('self::trim', $mixed);
		}

		return trim($mixed);
	}

	#===============================================================================
	# Checks if all elements of $arguments are set as key of $data
	#===============================================================================
	private static function issetData($data, $arguments): bool {
		foreach($arguments as $key) {
			if(is_array($key)) {
				if(!isset($data[key($key)]) OR $data[key($key)] !== $key[key($key)]) {
					return FALSE;
				}
			}

			else if(!isset($data[$key]) OR !is_string($data[$key])) {
				return FALSE;
			}
		}

		return TRUE;
	}

	#===============================================================================
	# Return GET value
	#===============================================================================
	public static function GET($parameter) {
		return self::$GET[$parameter] ?? NULL;
	}

	#===============================================================================
	# Return POST value
	#===============================================================================
	public static function POST($parameter) {
		return self::$POST[$parameter] ?? NULL;
	}

	#===============================================================================
	# Return FILE value
	#===============================================================================
	public static function FILE($parameter) {
		return self::$FILE[$parameter] ?? NULL;
	}

	#===============================================================================
	# Checks if all elements of $parameters are set as key in self::$GET
	#===============================================================================
	public static function issetGET(... $parameters): bool {
		return self::issetData(self::$GET, $parameters);
	}

	#===============================================================================
	# Checks if all elements of $parameters are set as key in self::$POST
	#===============================================================================
	public static function issetPOST(... $parameters): bool {
		return self::issetData(self::$POST, $parameters);
	}

	#===============================================================================
	# Checks if all elements of $parameters are set as key in self::$FILE
	#===============================================================================
	public static function issetFILE(... $parameters): bool {
		return self::issetData(self::$FILE, $parameters);
	}

	#===============================================================================
	# Set cookie
	#===============================================================================
	public static function setCookie($name, $value = '', $expire = 31536000): bool {
		return setcookie($name, $value, $expire + time(), '/');
	}

	#===============================================================================
	# Get cookie
	#===============================================================================
	public static function getCookie($name) {
		return $_COOKIE[$name] ?? NULL;
	}

	#===============================================================================
	# Return HTTP request method or check if request method equals with $method
	#===============================================================================
	public static function requestMethod($method = NULL) {
		if(!empty($method)) {
			return ($_SERVER['REQUEST_METHOD'] === $method);
		}

		return $_SERVER['REQUEST_METHOD'];
	}

	#===============================================================================
	# Return REQUEST_URI
	#===============================================================================
	public static function requestURI() {
		return $_SERVER['REQUEST_URI'] ?? FALSE;
	}

	#===============================================================================
	# Sends a HTTP header line to the client
	#===============================================================================
	public static function responseHeader($field, $value) {
		self::sendHeader("{$field}: {$value}");
	}

	#===============================================================================
	# Sends a HTTP redirect to the client
	#===============================================================================
	public static function redirect($location, $code = 303, $exit = TRUE) {
		http_response_code($code);
		self::sendHeader("Location: {$location}");
		$exit AND exit();
	}

	#===============================================================================
	# Sends a new HTTP header line to the client if headers are not already sent
	#===============================================================================
	private static function sendHeader($header) {
		if(!headers_sent()) {
			header($header);
		}
	}
}
?>