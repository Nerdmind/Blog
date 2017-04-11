<?php
class Router {
	private static $routes = [];

	#===============================================================================
	# Add route
	#===============================================================================
	public static function add($pattern, callable $callback) {
		$pattern = Application::get('PATHINFO.BASE').$pattern;

		return self::$routes[] = [
			'type' => 'route',
			'pattern' => $pattern,
			'callback' => $callback
		];
	}

	#===============================================================================
	# Add redirect
	#===============================================================================
	public static function addRedirect($pattern, $location, $code = 302) {
		$pattern = Application::get('PATHINFO.BASE').$pattern;

		return self::$routes[] = [
			'type' => 'redirect',
			'code' => $code,
			'pattern' => $pattern,
			'location' => $location
		];
	}

	#===============================================================================
	# Execute routing
	#===============================================================================
	public static function execute($path) {
		$path = ltrim($path, '/');
		$route_found = FALSE;

		foreach(self::$routes as $route) {
			if($route['type'] === 'redirect') {
				$location = preg_replace("#^{$route['pattern']}$#", $route['location'], $path, -1, $count);

				if($count) {
					HTTP::redirect($location, $route['code']);
				}
			}

			else {
				if(preg_match("#^{$route['pattern']}$#", $path, $matches)) {
					# Remove the first element from matches which contains the whole string.
					array_shift($matches);

					$route_found = TRUE;
					call_user_func_array($route['callback'], $matches);
				}
			}
		}

		if($route_found === FALSE) {
			Application::error404();
		}
	}
}
?>