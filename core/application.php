<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Application initialization                 [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# This file brings the application up!                                         #
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
# Include configuration
#===============================================================================
require 'configuration.php';

#===============================================================================
# Overwrite configuration if admin
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
	# Overwrite configuration
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
# TRY: PDOException
#===============================================================================
try {
	$Language = Application::getLanguage();
	$Database = Application::getDatabase();

	$Database->setAttribute($Database::ATTR_DEFAULT_FETCH_MODE, $Database::FETCH_ASSOC);
	$Database->setAttribute($Database::ATTR_ERRMODE, $Database::ERRMODE_EXCEPTION);
}

#===============================================================================
# CATCH: PDOException
#===============================================================================
catch(PDOException $Exception) {
	Application::exit($Exception->getMessage());
}

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
?>