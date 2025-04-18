<?php

$Language = Application::getLanguage();

$Database = Application::getDatabase();

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

$DbEditorTemplate = Template\Factory::build('database');
$DbEditorTemplate->set('FORM', [
	'INFO' => $messages ?? [],
	'TOKEN' => Application::getSecurityToken(),
	'RESULT' => implode(null, $result ?? []),
	'COMMAND' => HTTP::POST('command'),
]);

$Template = Template\Factory::build('main');
$Template->set('NAME', 'SQL');
$Template->set('HTML', $DbEditorTemplate);

echo $Template;
