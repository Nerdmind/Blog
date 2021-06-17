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
# Document root
#===============================================================================
define('ROOT', dirname(__DIR__).'/');

#===============================================================================
# Autoload register for classes
#===============================================================================
spl_autoload_register(function($classname) {
	$classname = str_replace('\\', '/', $classname);
	require "namespace/{$classname}.php";
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
HTTP::init($_GET, $_POST, $_FILES, TRUE);

#===============================================================================
# Set default configuration
#===============================================================================
foreach([
	'CORE.LANGUAGE' => 'en',
	'CORE.SEND_304' => FALSE,
	'BLOGMETA.NAME' => 'Example blog',
	'BLOGMETA.DESC' => 'This is an example blog.',
	'BLOGMETA.HOME' => 'Home',
	'BLOGMETA.MAIL' => 'mail@example.org',
	'BLOGMETA.LANG' => 'en',
	'DATABASE.HOSTNAME' => 'localhost',
	'DATABASE.BASENAME' => 'blog',
	'DATABASE.USERNAME' => 'blog',
	'DATABASE.PASSWORD' => '',
	'TEMPLATE.NAME' => 'default',
	'TEMPLATE.LANG' => 'en',
	'ADMIN.TEMPLATE' => 'admin',
	'ADMIN.LANGUAGE' => 'en',
	'PATHINFO.PROT' => $_SERVER['REQUEST_SCHEME'] ?? 'https',
	'PATHINFO.HOST' => $_SERVER['HTTP_HOST'] ?? 'localhost',
	'PATHINFO.BASE' => '',
	'PAGE.DIRECTORY' => 'page',
	'POST.DIRECTORY' => 'post',
	'USER.DIRECTORY' => 'user',
	'PAGE.SLUG_URLS' => TRUE,
	'POST.SLUG_URLS' => TRUE,
	'USER.SLUG_URLS' => TRUE,
	'PAGE.EMOTICONS' => TRUE,
	'POST.EMOTICONS' => TRUE,
	'USER.EMOTICONS' => TRUE,
	'PAGE.LIST_SIZE' => 10,
	'POST.LIST_SIZE' => 10,
	'USER.LIST_SIZE' => 10,
	'PAGE.FEED_SIZE' => 25,
	'POST.FEED_SIZE' => 25,
	'PAGE.DESCRIPTION_SIZE' => 200,
	'POST.DESCRIPTION_SIZE' => 200,
	'USER.DESCRIPTION_SIZE' => 200,
	'PAGE.SINGLE_REDIRECT' => FALSE,
	'POST.SINGLE_REDIRECT' => FALSE,
	'USER.SINGLE_REDIRECT' => FALSE,
	'PAGE.LIST_SORT' => 'time_insert DESC',
	'POST.LIST_SORT' => 'time_insert DESC',
	'USER.LIST_SORT' => 'time_insert DESC',
	'PAGE.FEED_SORT' => 'time_insert DESC',
	'POST.FEED_SORT' => 'time_insert DESC',
	'PAGE.FEED_GUID' => ['id', 'time_insert'],
	'POST.FEED_GUID' => ['id', 'time_insert']
] as $name => $value) {
	Application::set($name, $value);
}

#===============================================================================
# Set default configuration (for admin prefixes)
#===============================================================================
foreach([
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
# Override configuration if admin
#===============================================================================
if(defined('ADMINISTRATION') AND ADMINISTRATION === TRUE) {

	#===========================================================================
	# Enable sessions
	#===========================================================================
	session_start();

	#===========================================================================
	# Authentication check
	#===========================================================================
	if(defined('AUTHENTICATION') AND !Application::isAuthenticated()) {
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
# Include functions
#===============================================================================
require 'functions.php';

#===============================================================================
# Get Language and Database singletons
#===============================================================================
$Language = Application::getLanguage();
$Database = Application::getDatabase();

#===============================================================================
# Set Database attributes
#===============================================================================
$Database->setAttribute(
	$Database::ATTR_DEFAULT_FETCH_MODE,
	$Database::FETCH_ASSOC
);

$Database->setAttribute(
	$Database::ATTR_ERRMODE,
	$Database::ERRMODE_EXCEPTION
);

#===============================================================================
# Check if "304 Not Modified" and ETag header should be sent
#===============================================================================
if(Application::get('CORE.SEND_304') === TRUE AND !defined('ADMINISTRATION')) {

	#===========================================================================
	# Select edit time from last edited items (page, post, user)
	#===========================================================================
	$execute = '(SELECT time_update FROM %s ORDER BY time_update DESC LIMIT 1) AS %s';

	$pageSQL = sprintf($execute, Page\Attribute::TABLE, Page\Attribute::TABLE);
	$postSQL = sprintf($execute, Post\Attribute::TABLE, Post\Attribute::TABLE);
	$userSQL = sprintf($execute, User\Attribute::TABLE, User\Attribute::TABLE);

	$Statement = $Database->query("SELECT {$pageSQL}, {$postSQL}, {$userSQL}");

	#===========================================================================
	# Define HTTP ETag header identifier
	#===========================================================================
	$HTTP_ETAG_IDENTIFIER = md5(implode($Statement->fetch()));

	#===========================================================================
	# Send ETag header within the HTTP response
	#===========================================================================
	HTTP::responseHeader(HTTP::HEADER_ETAG, "\"{$HTTP_ETAG_IDENTIFIER}\"");

	#===========================================================================
	# Validate ETag header from the HTTP request
	#===========================================================================
	if(isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
		$HTTP_IF_NONE_MATCH = $_SERVER['HTTP_IF_NONE_MATCH'];
		$HTTP_IF_NONE_MATCH = trim($HTTP_IF_NONE_MATCH, '"');

		# If the server adds the extensions to the response header
		$HTTP_IF_NONE_MATCH = rtrim($HTTP_IF_NONE_MATCH, '-br');
		$HTTP_IF_NONE_MATCH = rtrim($HTTP_IF_NONE_MATCH, '-gzip');

		if($HTTP_IF_NONE_MATCH === $HTTP_ETAG_IDENTIFIER) {
			Application::exit(NULL, 304);
		}
	}
}
