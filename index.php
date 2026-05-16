<?php
#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require 'core/application.php';

function setupAdminEnv(bool $authRequired = true) {
	session_start();

	if($authRequired and !Application::isAuthenticated()) {
		HTTP::redirect(Application::getAdminURL('auth.php'));
	}

	Application::set('CORE.LANGUAGE', Application::get('ADMIN.LANGUAGE'));
	Application::set('TEMPLATE.NAME', Application::get('ADMIN.TEMPLATE'));
	Application::set('TEMPLATE.LANG', Application::get('ADMIN.LANGUAGE'));
}

Router::add('admin/', function() {
	setupAdminEnv();
	require 'core/include/admin/index.php';
});

Router::add('admin/database.php', function() {
	setupAdminEnv();
	require 'core/include/admin/db_editor.php';
});

Router::add('admin/auth.php', function() {
	setupAdminEnv(false);
	require 'core/include/admin/login.php';
});

foreach(['Category', 'Page', 'Post', 'User'] as $entityClassName) {
	$entityType = strtolower($entityClassName);
	$listKeyPlural = strtoupper([
		'category' => 'categories',
		'page' => 'pages',
		'post' => 'posts',
		'user' => 'users'
	][$entityType]);

	$entityBasePath = Application::get(strtoupper($entityType).'.DIRECTORY');
	$adminRouteTemplate = sprintf('admin/%s/%%s.php', $entityType);

	// Entity list routes (frontend)
	Router::add("{$entityBasePath}/", function() use($entityType) {
		require "core/include/{$entityType}/list.php";
	});

	// Entity show routes (frontend)
	Router::add("{$entityBasePath}/([^/]+)/", function($param) use($entityType) {
		require "core/include/{$entityType}/main.php";
	});

	// Entity insert/update/delete routes (backend)
	foreach(['insert', 'update', 'delete'] as $action) {
		Router::add(sprintf($adminRouteTemplate, $action),
			function() use($entityClassName, $entityType, $listKeyPlural, $action) {
				setupAdminEnv();
				require sprintf('core/include/admin/entity/%s.php', $action);
			}
		);
	}

	// Entity list routes (backend)
	Router::add(sprintf('admin/%s/', $entityType),
		function() use($entityClassName, $entityType, $listKeyPlural) {
			setupAdminEnv();
			require 'core/include/admin/entity/list.php';
		}
	);

	// Entity search routes for pages and posts (backend)
	if(in_array($entityClassName, ['Page', 'Post'])) {
		Router::add(sprintf($adminRouteTemplate, 'search'),
			function() use($entityClassName, $entityType, $listKeyPlural) {
				setupAdminEnv();
				require 'core/include/admin/entity/search.php';
			}
		);
	}

	// Enforce trailing slashes
	Router::addRedirect($entityBasePath,
		Application::getURL("{$entityBasePath}/"));

	Router::addRedirect("{$entityBasePath}/([^/]+)",
		Application::getURL("{$entityBasePath}/$1/"));

	Router::addRedirect(sprintf('admin/%s', $entityType),
		Application::getURL(sprintf('admin/%s/', $entityType)));

	Router::addRedirect(sprintf('admin/%s', $entityType),
		Application::getURL(sprintf('admin/%s/', $entityType)));
}

#===============================================================================
# ROUTE: Home
#===============================================================================
Router::add('', function() {
	require 'core/include/home.php';
});

#===============================================================================
# ROUTE: Feed and Search
#===============================================================================
Router::add('(feed|search)/', function($param) {
	require "core/include/{$param}/main.php";
});

#===============================================================================
# REDIRECT: Favicon
#===============================================================================
Router::addRedirect('favicon.ico',
	Application::getTemplateURL('static/favicon.ico'));

#===============================================================================
# REDIRECT: Enforce trailing slashes
#===============================================================================
Router::addRedirect('(admin|feed|search)',
	Application::getURL('$1/'));

#===============================================================================
# BACKWARD COMPATIBILITY: Redirect to the new post feed URL
#===============================================================================
Router::addRedirect('feed/post/',
	Application::getURL('feed/'), 301);

#===============================================================================
# Route request
#===============================================================================
Router::execute(parse_url(HTTP::requestURI(), PHP_URL_PATH));
