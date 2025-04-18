<?php
#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require 'core/application.php';

function setupAdminEnv(bool $auth_required = true) {
	session_start();

	if($auth_required and !Application::isAuthenticated()) {
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

foreach(['Category', 'Page', 'Post', 'User'] as $entity_ns) {
	$entity_str = strtolower($entity_ns);
	$plural_map = [
		'category' => 'categories',
		'page' => 'pages',
		'post' => 'posts',
		'user' => 'users'
	];

	$list_key_plural = strtoupper($plural_map[$entity_str]);

	Router::add(sprintf('admin/%s/', $entity_str),
		function() use($entity_ns, $entity_str, $list_key_plural) {
			setupAdminEnv();
			require 'core/include/admin/entity/list.php';
		}
	);

	// Ensure trailing slash for list controllers
	Router::addRedirect(sprintf('admin/%s', $entity_str),
		Application::getURL(sprintf('admin/%s/', $entity_str)));

	$route_template = sprintf('admin/%s/%%s.php', $entity_str);

	// Routes for create/update/delete
	foreach(['insert', 'update', 'delete'] as $action) {
		Router::add(sprintf($route_template, $action),
			function() use($entity_ns, $entity_str, $list_key_plural, $action) {
				setupAdminEnv();
				require sprintf('core/include/admin/entity/%s.php', $action);
			}
		);
	}

	// Routes for post and page search
	if(in_array($entity_ns, ['Page', 'Post'])) {
		Router::add(sprintf($route_template, 'search'),
			function() use($entity_ns, $entity_str, $list_key_plural) {
				setupAdminEnv();
				require 'core/include/admin/entity/search.php';
			}
		);
	}
}

// Ensure trailing slash for admin index controller
Router::addRedirect('admin', 'admin/');

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
# ROUTE: Feed and Search
#===============================================================================
Router::add('(feed|search)/', function($slug) {
	require "core/include/{$slug}/main.php";
});

#===============================================================================
# REDIRECT: Feed and Search (trailing slash)
#===============================================================================
Router::addRedirect('(feed|search)', Application::getURL('$1/'));

#===============================================================================
# REDIRECT: Favicon
#===============================================================================
Router::addRedirect('favicon.ico', Application::getTemplateURL('static/favicon.ico'));

#===============================================================================
# BACKWARD COMPATIBILITY: Redirect to the new post feed URL
#===============================================================================
Router::addRedirect('feed/post/', Application::getURL('feed/'), 301);

#===============================================================================
# Execute router and route requests
#===============================================================================
Router::execute(parse_url(HTTP::requestURI(), PHP_URL_PATH));
