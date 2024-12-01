<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
const ADMINISTRATION = true;
const AUTHENTICATION = true;

#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require '../core/application.php';

#===============================================================================
# Execute database command(s)
#===============================================================================
if(HTTP::issetPOST('command')) {
	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$Statement = $Database->query(HTTP::POST('command'));

			do {
				$result[] = print_r($Statement->fetchAll(), true);
			} while($Statement->nextRowset());
		} catch(PDOException $Exception) {
			$messages[] = $Exception->getMessage();
		}
	} else {
		$messages[] = $Language->text('error_security_csrf');
	}
}

#===============================================================================
# Build document
#===============================================================================
$DatabaseTemplate = Template\Factory::build('database');
$DatabaseTemplate->set('FORM', [
	'INFO' => $messages ?? [],
	'TOKEN' => Application::getSecurityToken(),
	'RESULT' => implode(null, $result ?? []),
	'COMMAND' => HTTP::POST('command'),
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', 'SQL');
$MainTemplate->set('HTML', $DatabaseTemplate);
echo $MainTemplate;
