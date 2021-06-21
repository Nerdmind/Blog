<?php
class Application {

	#===============================================================================
	# Singleton instances
	#===============================================================================
	private static $Database;
	private static $Language;
	private static $repositories = [];

	#===============================================================================
	# Configuration array
	#===============================================================================
	private static $configuration = [];

	#===============================================================================
	# Set configuration value
	#===============================================================================
	public static function set($config, $value) {
		return self::$configuration[$config] = $value;
	}

	#===============================================================================
	# Get configuration value
	#===============================================================================
	public static function get($config) {
		return self::$configuration[$config] ?? "{$config}";
	}

	#===============================================================================
	# Get configuration
	#===============================================================================
	public static function getConfiguration(): array {
		return self::$configuration;
	}

	#===============================================================================
	# Return singleton Database instance
	#===============================================================================
	public static function getDatabase($force = FALSE): Database {
		if(!self::$Database instanceof Database OR $force === TRUE) {
			$hostname = self::get('DATABASE.HOSTNAME');
			$basename = self::get('DATABASE.BASENAME');
			$username = self::get('DATABASE.USERNAME');
			$password = self::get('DATABASE.PASSWORD');

			self::$Database = new Database($hostname, $basename, $username, $password);
		}

		return self::$Database;
	}

	#===============================================================================
	# Return singleton Language instance
	#===============================================================================
	public static function getLanguage($force = FALSE): Language {
		if(!self::$Language instanceof Language OR $force === TRUE) {
			$template_name = self::get('TEMPLATE.NAME');
			$template_lang = self::get('TEMPLATE.LANG');

			$Language = new Language(self::get('CORE.LANGUAGE'));
			$Language->load(sprintf(ROOT.'core/language/%s.php', Application::get('CORE.LANGUAGE')));
			$Language->load(sprintf(ROOT.'theme/%s/lang/%s.php', $template_name, $template_lang));

			self::$Language = $Language;
		}

		return self::$Language;
	}

	#===============================================================================
	# Return singleton repository instance
	#===============================================================================
	public function getRepository(string $namespace): Repository {
		$identifier = strtolower($namespace);
		$repository = "$namespace\Repository";

		if(!isset(self::$repositories[$identifier])) {
			$Repository = new $repository(self::getDatabase());
			self::$repositories[$identifier] = $Repository;
		}

		return self::$repositories[$identifier];
	}

	#===============================================================================
	# Return unique CSRF token for the current session
	#===============================================================================
	public static function getSecurityToken(): string {
		if(!isset($_SESSION['token'])) {
			$_SESSION['token'] = bin2hex(random_bytes(16));
		}

		return $_SESSION['token'];
	}

	#===============================================================================
	# Return boolean if successfully authenticated
	#===============================================================================
	public static function isAuthenticated(): bool {
		return isset($_SESSION['auth']);
	}

	#===============================================================================
	# Return absolute base URL
	#===============================================================================
	public static function getURL($more = ''): string {
		$prot = self::get('PATHINFO.PROT');
		$host = self::get('PATHINFO.HOST');
		$base = self::get('PATHINFO.BASE');

		return "{$prot}://{$host}/{$base}{$more}";
	}

	#===============================================================================
	# Return absolute post URL
	#===============================================================================
	public static function getPostURL($more = ''): string {
		return self::getURL(self::get('POST.DIRECTORY')."/{$more}");
	}

	#===============================================================================
	# Return absolute page URL
	#===============================================================================
	public static function getPageURL($more = ''): string {
		return self::getURL(self::get('PAGE.DIRECTORY')."/{$more}");
	}

	#===============================================================================
	# Return absolute user URL
	#===============================================================================
	public static function getUserURL($more = ''): string {
		return self::getURL(self::get('USER.DIRECTORY')."/{$more}");
	}

	#===============================================================================
	# Return absolute file URL
	#===============================================================================
	public static function getFileURL($more = ''): string {
		return self::getURL("rsrc/{$more}");
	}

	#===============================================================================
	# Return absolute admin URL
	#===============================================================================
	public static function getAdminURL($more = ''): string {
		return self::getURL("admin/{$more}");
	}

	#===============================================================================
	# Return absolute template URL
	#===============================================================================
	public static function getTemplateURL($more = ''): string {
		$template = self::get('TEMPLATE.NAME');
		return self::getURL("theme/{$template}/{$more}");
	}

	#===============================================================================
	# Return absolute URL of a specifc entity
	#===============================================================================
	public function getEntityURL(EntityInterface $Entity) {
		switch($class = get_class($Entity)) {
			case 'Page\Entity':
				$attr = self::get('PAGE.SLUG_URLS') ? 'slug' : 'id';
				return self::getPageURL($Entity->get($attr).'/');
			case 'Post\Entity':
				$attr = self::get('POST.SLUG_URLS') ? 'slug' : 'id';
				return self::getPostURL($Entity->get($attr).'/');
			case 'User\Entity':
				$attr = self::get('USER.SLUG_URLS') ? 'slug' : 'id';
				return self::getUserURL($Entity->get($attr).'/');
			default:
				$error = 'Unknown URL handler for <code>%s</code> entities.';
				throw new Exception(sprintf($error, $class));
		}
	}

	#===============================================================================
	# Exit application with a custom message and status code
	#===============================================================================
	public static function exit($message = '', $code = 503): void {
		http_response_code($code);
		exit($message);
	}

	#===============================================================================
	# Exit application with the 403 error page
	#===============================================================================
	public static function error403(): void {
		require ROOT.'403.php';
		exit();
	}

	#===============================================================================
	# Exit application with the 404 error page
	#===============================================================================
	public static function error404(): void {
		require ROOT.'404.php';
		exit();
	}
}
