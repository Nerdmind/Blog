<?php
#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require 'core/application.php';

#===============================================================================
# ROUTE: Item controllers
#===============================================================================
foreach(['category', 'page', 'post', 'user'] as $item) {
	$slug = Application::get(strtoupper($item).'.DIRECTORY');

	# Item list controller
	Router::add("{$slug}/", function() use($item) {
		require "core/include/{$item}/list.php";
	});

	# Item show controller
	Router::add("{$slug}/([^/]+)/", function($param) use($item) {
		require "core/include/{$item}/main.php";
	});

	# Item controllers (ensure trailing slashes)
	Router::addRedirect($slug, Application::getURL("{$slug}/"));
	Router::addRedirect("{$slug}/([^/]+)", Application::getURL("{$slug}/$1/"));
}

#===============================================================================
# ROUTE: Home
#===============================================================================
Router::add('', function() {
	require 'core/include/home.php';
});

#===============================================================================
# ROUTE: Feed
#===============================================================================
Router::add('feed/', function() {
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
Router::addRedirect('feed', Application::getURL('feed/'));

#===============================================================================
# REDIRECT: Search (trailing slash)
#===============================================================================
Router::addRedirect('search', Application::getURL('search/'));

#===============================================================================
# REDIRECT: Favicon
#===============================================================================
Router::addRedirect('favicon.ico', Application::getTemplateURL('rsrc/favicon.ico'));

#===============================================================================
# BACKWARD COMPATIBILITY: Redirect to the new post feed URL
#===============================================================================
Router::addRedirect('feed/post/', Application::getURL('feed/'), 301);

#===============================================================================
# Execute router and route requests
#===============================================================================
Router::execute(parse_url(HTTP::requestURI(), PHP_URL_PATH));
