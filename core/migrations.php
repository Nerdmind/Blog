<?php
#===============================================================================
# Get Migrator singleton
#===============================================================================
$Migrator = Application::getMigrator();

#===============================================================================
# Check for outstanding database schema migrations
#===============================================================================
if($Migrator->isMigrationNeeded()) {
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	Application::set('TEMPLATE.NAME', Application::get('ADMIN.TEMPLATE'));
	Application::set('TEMPLATE.LANG', Application::get('ADMIN.LANGUAGE'));

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()], 'run')) {
		if(!$migrated = $Migrator->runMigrations()) {
			Application::exit('CONFUSED: No migrations were performed?!');
		}
	}

	$Template = Template\Factory::build('migration');
	$Template->set('MIGRATION', [
		'LIST' => $Migrator->getMigrations(),
		'SUCCESSFUL' => $migrated ?? [],
		'SCHEMA_VERSION' => [
			'DATABASE' => $Migrator->getVersionFromTable(),
			'CODEBASE' => $Migrator::CURRENT_SCHEMA_VERSION
		],
	]);

	Application::exit($Template);
}

#===============================================================================
# Check for an unsupported downgrade attempt
#===============================================================================
else if($Migrator->isDowngradeAttempt()) {
	throw new Exception('MIGRATOR: The schema version used by *your* database is
		higher than the schema version defined in the codebase. It is officially
		not supported to automatically downgrade the database schema version!');
}
