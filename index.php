<?php
#===============================================================================
# INCLUDE: Main configuration
#===============================================================================
require_once 'core/application.php';

#===============================================================================
# Item base directory paths
#===============================================================================
$PAGEPATH = Application::get('PAGE.DIRECTORY');
$POSTPATH = Application::get('POST.DIRECTORY');
$USERPATH = Application::get('USER.DIRECTORY');

#===============================================================================
# ROUTE: Item
#===============================================================================
Router::add("{$PAGEPATH}/([^/]+)/", function($param) { require 'system/page/main.php'; });
Router::add("{$POSTPATH}/([^/]+)/", function($param) { require 'system/post/main.php'; });
Router::add("{$USERPATH}/([^/]+)/", function($param) { require 'system/user/main.php'; });

#===============================================================================
# ROUTE: Item overview
#===============================================================================
Router::add("{$PAGEPATH}/", function() { require 'system/page/list.php'; });
Router::add("{$POSTPATH}/", function() { require 'system/post/list.php'; });
Router::add("{$USERPATH}/", function() { require 'system/user/list.php'; });

#===============================================================================
# REDIRECT: Item (trailing slash)
#===============================================================================
Router::addRedirect("{$PAGEPATH}/([^/]+)", Application::getPageURL('$1/'));
Router::addRedirect("{$POSTPATH}/([^/]+)", Application::getPostURL('$1/'));
Router::addRedirect("{$USERPATH}/([^/]+)", Application::getUserURL('$1/'));

#===============================================================================
# REDIRECT: Item overview (trailing slash)
#===============================================================================
Router::addRedirect("{$PAGEPATH}", Application::getPageURL());
Router::addRedirect("{$POSTPATH}", Application::getPostURL());
Router::addRedirect("{$USERPATH}", Application::getUserURL());

#===============================================================================
# ROUTE: Home
#===============================================================================
Router::add('', function() {
	require 'system/home.php';
});

#===============================================================================
# ROUTE: Feed
#===============================================================================
Router::add('feed/', function() {
	require 'system/feed/main.php';
});

#===============================================================================
# ROUTE: Feed [item type only]
#===============================================================================
Router::add('feed/(page|post)/', function($param) {
	require 'system/feed/main.php';
});

#===============================================================================
# ROUTE: Search
#===============================================================================
Router::add('search/', function() {
	require 'system/search/main.php';
});

#===============================================================================
# REDIRECT: Feed (trailing slash)
#===============================================================================
Router::addRedirect('feed', Application::getURL('feed/'));

#===============================================================================
# REDIRECT: Feed [posts or pages] (trailing slash)
#===============================================================================
Router::addRedirect('feed/(page|post)', Application::getURL('feed/$1/'));

#===============================================================================
# REDIRECT: Search (trailing slash)
#===============================================================================
Router::addRedirect('search', Application::getURL('search/'));

#===============================================================================
# Execute router and route requests
#===============================================================================
Router::execute(parse_url(HTTP::requestURI(), PHP_URL_PATH));
?>