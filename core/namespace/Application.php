<?php
use ORM\Entity\EntityInterface;
use ORM\Repository\RepositoryInterface;

class Application {

	#===============================================================================
	# Singleton instances
	#===============================================================================
	private static $database;
	private static $language;
	private static $migrator;
	private static $repositories = [];
	private static $contentFunctions = [];

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
		return self::$configuration[$config] ?? null;
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
	public static function getDatabase($force = false): Database {
		if(!self::$database instanceof Database or $force === true) {
			$hostname = self::get('DATABASE.HOSTNAME');
			$basename = self::get('DATABASE.BASENAME');
			$username = self::get('DATABASE.USERNAME');
			$password = self::get('DATABASE.PASSWORD');

			$database = new Database($hostname, $basename, $username, $password);

			$database->setAttribute(
				$database::ATTR_DEFAULT_FETCH_MODE,
				$database::FETCH_ASSOC
			);

			$database->setAttribute(
				$database::ATTR_ERRMODE,
				$database::ERRMODE_EXCEPTION
			);

			self::$database = $database;
		}

		return self::$database;
	}

	#===============================================================================
	# Return singleton Language instance
	#===============================================================================
	public static function getLanguage($force = false): Language {
		if(!self::$language instanceof Language or $force === true) {
			$template_name = self::get('TEMPLATE.NAME');
			$template_lang = self::get('TEMPLATE.LANG');

			$language = new Language(self::get('CORE.LANGUAGE'));
			$language->load(sprintf(ROOT.'core/language/%s.php', Application::get('CORE.LANGUAGE')));
			$language->load(sprintf(ROOT.'theme/%s/lang/%s.php', $template_name, $template_lang));

			self::$language = $language;
		}

		return self::$language;
	}

	#===============================================================================
	# Return singleton Migrator instance
	#===============================================================================
	public static function getMigrator(): Migrator {
		if(!self::$migrator instanceof Migrator) {
			$migrator = new Migrator(self::getDatabase());
			$migrator->setMigrationsDir(ROOT.'core/db/migrations/');
			self::$migrator = $migrator;
		}

		return self::$migrator;
	}

	#===============================================================================
	# Return singleton repository instance
	#===============================================================================
	public static function getRepository(string $entity): RepositoryInterface {
		$identifier = strtolower($entity);

		if(!isset(self::$repositories[$identifier])) {
			$className = sprintf('ORM\Repository\%sRepository', $entity);
			$repository = new $className(self::getDatabase());
			self::$repositories[$identifier] = $repository;
		}

		return self::$repositories[$identifier];
	}

	#===============================================================================
	# Return unique CSRF token for the current session
	#===============================================================================
	public static function getSecurityToken(): string {
		if(!isset($_SESSION['CSRF_TOKEN'])) {
			$_SESSION['CSRF_TOKEN'] = bin2hex(random_bytes(16));
		}

		return $_SESSION['CSRF_TOKEN'];
	}

	#===============================================================================
	# Return boolean if successfully authenticated
	#===============================================================================
	public static function isAuthenticated(): bool {
		return isset($_SESSION['USER_ID']);
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
	# Return absolute category URL
	#===============================================================================
	public static function getCategoryURL($more = ''): string {
		return self::getURL(self::get('CATEGORY.DIRECTORY')."/{$more}");
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
		return self::getURL("static/{$more}");
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
	# Return absolute URL of a specific entity
	#===============================================================================
	public static function getEntityURL(EntityInterface $entity) {
		switch($class = get_class($entity)) {
			case 'ORM\Entity\Category':
				$attr = self::get('CATEGORY.SLUG_URLS') ? 'slug' : 'id';
				return self::getCategoryURL($entity->get($attr).'/');
			case 'ORM\Entity\Page':
				$attr = self::get('PAGE.SLUG_URLS') ? 'slug' : 'id';
				return self::getPageURL($entity->get($attr).'/');
			case 'ORM\Entity\Post':
				$attr = self::get('POST.SLUG_URLS') ? 'slug' : 'id';
				return self::getPostURL($entity->get($attr).'/');
			case 'ORM\Entity\User':
				$attr = self::get('USER.SLUG_URLS') ? 'slug' : 'id';
				return self::getUserURL($entity->get($attr).'/');
			default:
				$error = 'Unknown URL handler for <code>%s</code> entities.';
				throw new Exception(sprintf($error, $class));
		}
	}

	#===============================================================================
	# Add a custom content function
	#===============================================================================
	public static function addContentFunction(string $name, callable $callback): void {
		if(!preg_match('#^([0-9A-Z_]+)$#', $name)) {
			throw new Exception('The name for adding a content function must
				contain only numbers, uppercase letters and underscores!');
		}

		self::$contentFunctions[$name] = $callback;
	}

	#===============================================================================
	# Return all known content functions
	#===============================================================================
	public static function getContentFunctions(): array {
		return self::$contentFunctions;
	}

	#===============================================================================
	# Exit application with a custom message and status code
	#===============================================================================
	public static function exit(?string $message = null, int $code = 503): void {
		http_response_code($code);
		exit($message);
	}

	#===============================================================================
	# Exit application with the 403 error page
	#===============================================================================
	public static function error403(): void {
		$template = Template\Factory::build('main');
		$template->set('NAME', '403 Forbidden');
		$template->set('HEAD', ['NAME' => $template->get('NAME')]);
		$template->set('HTML', Template\Factory::build('403'));
		self::exit($template, 403);
	}

	#===============================================================================
	# Exit application with the 404 error page
	#===============================================================================
	public static function error404(): void {
		$template = Template\Factory::build('main');
		$template->set('NAME', '404 Not Found');
		$template->set('HEAD', ['NAME' => $template->get('NAME')]);
		$template->set('HTML', Template\Factory::build('404'));
		self::exit($template, 404);
	}
}
