<?php
#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require 'core/application.php';

#===============================================================================
# Item base directory paths
#===============================================================================
$CATEGORYPATH = Application::get('CATEGORY.DIRECTORY');
$PAGEPATH = Application::get('PAGE.DIRECTORY');
$POSTPATH = Application::get('POST.DIRECTORY');
$USERPATH = Application::get('USER.DIRECTORY');

#===============================================================================
# ROUTE: Item
#===============================================================================
Router::add("{$CATEGORYPATH}/([^/]+)/", function($param) { require 'core/include/category/main.php'; });
Router::add("{$PAGEPATH}/([^/]+)/", function($param) { require 'core/include/page/main.php'; });
Router::add("{$POSTPATH}/([^/]+)/", function($param) { require 'core/include/post/main.php'; });
Router::add("{$USERPATH}/([^/]+)/", function($param) { require 'core/include/user/main.php'; });

#===============================================================================
# ROUTE: Item overview
#===============================================================================
Router::add("{$CATEGORYPATH}/", function() { require 'core/include/category/list.php'; });
Router::add("{$PAGEPATH}/", function() { require 'core/include/page/list.php'; });
Router::add("{$POSTPATH}/", function() { require 'core/include/post/list.php'; });
Router::add("{$USERPATH}/", function() { require 'core/include/user/list.php'; });

#===============================================================================
# REDIRECT: Item (trailing slash)
#===============================================================================
Router::addRedirect("{$CATEGORYPATH}/([^/]+)", Application::getCategoryURL('$1/'));
Router::addRedirect("{$PAGEPATH}/([^/]+)", Application::getPageURL('$1/'));
Router::addRedirect("{$POSTPATH}/([^/]+)", Application::getPostURL('$1/'));
Router::addRedirect("{$USERPATH}/([^/]+)", Application::getUserURL('$1/'));

#===============================================================================
# REDIRECT: Item overview (trailing slash)
#===============================================================================
Router::addRedirect("{$CATEGORYPATH}", Application::getCategoryURL());
Router::addRedirect("{$PAGEPATH}", Application::getPageURL());
Router::addRedirect("{$POSTPATH}", Application::getPostURL());
Router::addRedirect("{$USERPATH}", Application::getUserURL());

#===============================================================================
# ROUTE: Home
#===============================================================================
Router::add('', function() {
	require 'core/include/home.php';
});

#===============================================================================
# ROUTE: Feed
#===============================================================================
Router::add('feed/(?:(page|post)/)?', function($param = NULL) {
	require 'core/include/feed/main.php';
});

#===============================================================================
# ROUTE: Search
#===============================================================================
Router::add('search/', function() {
	require 'core/include/search/main.php';
});

#===============================================================================
# REDIRECT: Feed (trailing slash)
#===============================================================================
Router::addRedirect('feed(/(?:page|post))?', Application::getURL('feed$1/'));

#===============================================================================
# REDIRECT: Search (trailing slash)
#===============================================================================
Router::addRedirect('search', Application::getURL('search/'));

#===============================================================================
# REDIRECT: Favicon
#===============================================================================
Router::addRedirect('favicon.ico', Application::getTemplateURL('rsrc/favicon.ico'));

#===============================================================================
# Execute router and route requests
#===============================================================================
Router::execute(parse_url(HTTP::requestURI(), PHP_URL_PATH));
