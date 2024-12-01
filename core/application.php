<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Application initialization                 [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# This file brings the application up and defines default configuration values #
# for the application which can be overridden in configuration.php.            #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

#===============================================================================
# Check for minimum required PHP version
#===============================================================================
if(!version_compare(PHP_VERSION, '7.3', '>=')) {
	exit('ABORT: This application requires at least PHP 7.3 to run.');
}

#===============================================================================
# Document root
#===============================================================================
define('ROOT', dirname(__DIR__).'/');

#===============================================================================
# Register autoloader for classes
#===============================================================================
spl_autoload_register(function($className) {
	$classPath = str_replace('\\', '/', $className);
	$classPath = ROOT."core/namespace/{$classPath}.php";

	if(is_file($classPath)) {
		require $classPath;
	}
});

#===============================================================================
# Exception handler for non-caught exceptions
#===============================================================================
set_exception_handler(function(Throwable $Exception) {
	Application::exit($Exception->getMessage());
});

#===============================================================================
# Initialize HTTP class and remove all arrays from $_GET and $_POST
#===============================================================================
HTTP::init($_GET, $_POST, $_FILES, true);

#===============================================================================
# Set default configuration
#===============================================================================
foreach([
	'CORE.LANGUAGE' => 'en',
	'CORE.SEND_304' => false,
	'BLOGMETA.NAME' => 'Example blog',
	'BLOGMETA.DESC' => 'This is an example blog.',
	'BLOGMETA.HOME' => 'Home',
	'BLOGMETA.MAIL' => 'mail@example.org',
	'BLOGMETA.LANG' => 'en',
	'DATABASE.HOSTNAME' => 'localhost',
	'DATABASE.BASENAME' => 'blog',
	'DATABASE.USERNAME' => 'blog',
	'DATABASE.PASSWORD' => '',
	'MIGRATOR.ENABLED' => true,
	'TEMPLATE.NAME' => 'default',
	'TEMPLATE.LANG' => 'en',
	'ADMIN.TEMPLATE' => 'admin',
	'ADMIN.LANGUAGE' => 'en',
	'PATHINFO.PROT' => $_SERVER['REQUEST_SCHEME'] ?? 'https',
	'PATHINFO.HOST' => $_SERVER['HTTP_HOST'] ?? 'localhost',
	'PATHINFO.BASE' => '',
	'WRAP_EMOTICONS' => true,
	'CATEGORY.DIRECTORY' => 'category',
	'PAGE.DIRECTORY' => 'page',
	'POST.DIRECTORY' => 'post',
	'USER.DIRECTORY' => 'user',
	'CATEGORY.SLUG_URLS' => true,
	'PAGE.SLUG_URLS' => true,
	'POST.SLUG_URLS' => true,
	'USER.SLUG_URLS' => true,
	'CATEGORY.LIST_SIZE' => 10,
	'PAGE.LIST_SIZE' => 10,
	'POST.LIST_SIZE' => 10,
	'USER.LIST_SIZE' => 10,
	'POST.FEED_SIZE' => 10,
	'CATEGORY.DESCRIPTION_SIZE' => 200,
	'PAGE.DESCRIPTION_SIZE' => 200,
	'POST.DESCRIPTION_SIZE' => 200,
	'USER.DESCRIPTION_SIZE' => 200,
	'CATEGORY.REDIRECT_SINGLE' => false,
	'PAGE.REDIRECT_SINGLE' => false,
	'POST.REDIRECT_SINGLE' => false,
	'USER.REDIRECT_SINGLE' => false,
	'CATEGORY.LIST_SORT' => 'name ASC',
	'PAGE.LIST_SORT' => 'time_insert DESC',
	'POST.LIST_SORT' => 'time_insert DESC',
	'USER.LIST_SORT' => 'time_insert DESC',
	'POST.FEED_SORT' => 'time_insert DESC'
] as $name => $value) {
	Application::set($name, $value);
}

#===============================================================================
# Set default configuration (for admin prefixes)
#===============================================================================
foreach([
	'ADMIN.CATEGORY.LIST_SIZE' => 12, # for 1/2/3-column grid layout
	'ADMIN.PAGE.LIST_SIZE' => 12, # for 1/2/3-column grid layout
	'ADMIN.POST.LIST_SIZE' => 12, # for 1/2/3-column grid layout
	'ADMIN.USER.LIST_SIZE' => Application::get('USER.LIST_SIZE'),
	'ADMIN.PAGE.LIST_SORT' => Application::get('PAGE.LIST_SORT'),
	'ADMIN.POST.LIST_SORT' => Application::get('POST.LIST_SORT'),
	'ADMIN.USER.LIST_SORT' => Application::get('USER.LIST_SORT')
] as $name => $value) {
	Application::set($name, $value);
}

#===============================================================================
# Include custom configuration
#===============================================================================
require 'configuration.php';

#===============================================================================
# Include functions
#===============================================================================
require 'functions.php';

#===============================================================================
# Include migration detection
#===============================================================================
if(Application::get('MIGRATOR.ENABLED')) {
	require 'migrations.php';
}

#===============================================================================
# Override configuration if admin
#===============================================================================
if(defined('ADMINISTRATION') and ADMINISTRATION === true) {

	#===========================================================================
	# Enable sessions
	#===========================================================================
	session_start();

	#===========================================================================
	# Authentication check
	#===========================================================================
	if(defined('AUTHENTICATION') and !Application::isAuthenticated()) {
		HTTP::redirect(Application::getAdminURL('auth.php'));
	}

	#===========================================================================
	# Override configuration
	#===========================================================================
	Application::set('CORE.LANGUAGE', Application::get('ADMIN.LANGUAGE'));
	Application::set('TEMPLATE.NAME', Application::get('ADMIN.TEMPLATE'));
	Application::set('TEMPLATE.LANG', Application::get('ADMIN.LANGUAGE'));
}

#===============================================================================
# Get Language and Database singletons
#===============================================================================
$Language = Application::getLanguage();
$Database = Application::getDatabase();

#===============================================================================
# Check if "304 Not Modified" and ETag header should be sent
#===============================================================================
if(Application::get('CORE.SEND_304') and !defined('ADMINISTRATION')) {

	#===========================================================================
	# Fetch timestamps of the last modified entities
	#===========================================================================
	$query = '(SELECT time_update FROM %s ORDER BY time_update DESC LIMIT 1) AS %s';

	foreach([
		ORM\Repositories\CategoryRepository::getTableName(),
		ORM\Repositories\PageRepository::getTableName(),
		ORM\Repositories\PostRepository::getTableName(),
		ORM\Repositories\UserRepository::getTableName()
    ] as $table) {
		$parts[] = sprintf($query, $table, $table);
	}

	$Statement = $Database->query('SELECT '.implode(',', $parts));

	#===========================================================================
	# Define HTTP ETag header identifier
	#===========================================================================
	$etag = md5(implode($Statement->fetch()));

	#===========================================================================
	# Send ETag header within the HTTP response
	#===========================================================================
	HTTP::responseHeader(HTTP::HEADER_ETAG, "\"{$etag}\"");

	#===========================================================================
	# Return "304 Not Modified" if the clients ETag value matches
	#===========================================================================
	if(strpos($_SERVER['HTTP_IF_NONE_MATCH'] ?? '', $etag) !== false) {
		Application::exit(null, 304);
	}
}
