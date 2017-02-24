<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
define('ADMINISTRATION', TRUE);
define('AUTHENTICATION', TRUE);

#===============================================================================
# INCLUDE: Main configuration
#===============================================================================
require_once '../core/application.php';

#===============================================================================
# Execute database command(s)
#===============================================================================
if(HTTP::issetPOST(['token' => Application::getSecurityToken()], 'command')) {
	try {
		$Statement = $Database->query(HTTP::POST('command'));
		$result = print_r($Statement->fetchAll(), TRUE);
	} catch(PDOException $Exception) {
		$messages[] = $Exception->getMessage();
	}
}

#===============================================================================
# TRY: Template\Exception
#===============================================================================
try {
	$DatabaseTemplate = Template\Factory::build('database');
	$DatabaseTemplate->set('FORM', [
		'INFO' => $messages ?? [],
		'TOKEN' => Application::getSecurityToken(),
		'RESULT' => $result ?? NULL,
		'COMMAND' => HTTP::POST('command'),
	]);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', 'SQL');
	$MainTemplate->set('HTML', $DatabaseTemplate);
	echo $MainTemplate;
}

#===============================================================================
# CATCH: Template\Exception
#===============================================================================
catch(Template\Exception $Exception) {
	$Exception->defaultHandler();
}
?>