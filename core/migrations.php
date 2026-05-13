<?php
#===============================================================================
# Get Migrator singleton
#===============================================================================
$migrator = Application::getMigrator();

#===============================================================================
# Check for outstanding database schema migrations
#===============================================================================
if($migrator->isMigrationNeeded()) {
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	Application::set('TEMPLATE.NAME', Application::get('ADMIN.TEMPLATE'));
	Application::set('TEMPLATE.LANG', Application::get('ADMIN.LANGUAGE'));

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()], 'run')) {
		if(!$migrated = $migrator->runMigrations()) {
			Application::exit('CONFUSED: No migrations were performed?!');
		}
	}

	$template = Template\Factory::build('migration');
	$template->set('MIGRATION', [
		'LIST' => $migrator->getMigrations(),
		'SUCCESSFUL' => $migrated ?? [],
		'SCHEMA_VERSION' => [
			'DATABASE' => $migrator->getVersionFromTable(),
			'CODEBASE' => $migrator::CURRENT_SCHEMA_VERSION
		],
	]);

	Application::exit($template);
}

#===============================================================================
# Check for an unsupported downgrade attempt
#===============================================================================
elseif($migrator->isDowngradeAttempt()) {
	throw new Exception('MIGRATOR: The schema version used by *your* database is
		higher than the schema version defined in the codebase. It is officially
		not supported to automatically downgrade the database schema version!');
}
