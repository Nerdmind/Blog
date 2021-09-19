<?php
use ORM\EntityInterface;
use Parsers\FunctionParser;

class Application {

	#===============================================================================
	# Singleton instances
	#===============================================================================
	private static $Database;
	private static $Language;
	private static $Migrator;
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
		return self::$configuration[$config] ?? NULL;
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

			$Database = new Database($hostname, $basename, $username, $password);

			$Database->setAttribute(
				$Database::ATTR_DEFAULT_FETCH_MODE,
				$Database::FETCH_ASSOC
			);

			$Database->setAttribute(
				$Database::ATTR_ERRMODE,
				$Database::ERRMODE_EXCEPTION
			);

			self::$Database = $Database;
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
	# Return singleton Migrator instance
	#===============================================================================
	public static function getMigrator(): Migrator {
		if(!self::$Migrator instanceof Migrator) {
			$Migrator = new Migrator(self::getDatabase());
			$Migrator->setMigrationsDir(ROOT.'core/db/migrations/');
			self::$Migrator = $Migrator;
		}

		return self::$Migrator;
	}

	#===============================================================================
	# Return singleton repository instance
	#===============================================================================
	public static function getRepository(string $entity): ORM\Repository {
		$identifier = strtolower($entity);

		if(!isset(self::$repositories[$identifier])) {
			$className = sprintf('ORM\Repositories\%sRepository', $entity);
			$Repository = new $className(self::getDatabase());
			self::$repositories[$identifier] = $Repository;
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
	# Return absolute URL of a specific entity
	#===============================================================================
	public static function getEntityURL(EntityInterface $Entity) {
		switch($class = get_class($Entity)) {
			case 'ORM\Entities\Category':
				$attr = self::get('CATEGORY.SLUG_URLS') ? 'slug' : 'id';
				return self::getCategoryURL($Entity->get($attr).'/');
			case 'ORM\Entities\Page':
				$attr = self::get('PAGE.SLUG_URLS') ? 'slug' : 'id';
				return self::getPageURL($Entity->get($attr).'/');
			case 'ORM\Entities\Post':
				$attr = self::get('POST.SLUG_URLS') ? 'slug' : 'id';
				return self::getPostURL($Entity->get($attr).'/');
			case 'ORM\Entities\User':
				$attr = self::get('USER.SLUG_URLS') ? 'slug' : 'id';
				return self::getUserURL($Entity->get($attr).'/');
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

		FunctionParser::register($name, $callback);
	}

	#===============================================================================
	# Exit application with a custom message and status code
	#===============================================================================
	public static function exit(?string $message = NULL, int $code = 503): void {
		http_response_code($code);
		exit($message);
	}

	#===============================================================================
	# Exit application with the 403 error page
	#===============================================================================
	public static function error403(): void {
		$Template = Template\Factory::build('main');
		$Template->set('NAME', '403 Forbidden');
		$Template->set('HEAD', ['NAME' => $Template->get('NAME')]);
		$Template->set('HTML', Template\Factory::build('403'));
		self::exit($Template, 403);
	}

	#===============================================================================
	# Exit application with the 404 error page
	#===============================================================================
	public static function error404(): void {
		$Template = Template\Factory::build('main');
		$Template->set('NAME', '404 Not Found');
		$Template->set('HEAD', ['NAME' => $Template->get('NAME')]);
		$Template->set('HTML', Template\Factory::build('404'));
		self::exit($Template, 404);
	}
}
