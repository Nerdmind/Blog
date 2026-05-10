<?php

$language = Application::getLanguage();
$database = Application::getDatabase();

if(HTTP::issetPOST('command')) {
	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$statement = $database->query(HTTP::POST('command'));

			do {
				$result[] = print_r($statement->fetchAll(), true);
			} while($statement->nextRowset());
		} catch(PDOException $exception) {
			$messages[] = $exception->getMessage();
		}
	} else {
		$messages[] = $language->text('error_security_csrf');
	}
}

$dbEditorTemplate = Template\Factory::build('database');
$dbEditorTemplate->set('FORM', [
	'INFO' => $messages ?? [],
	'TOKEN' => Application::getSecurityToken(),
	'RESULT' => implode(null, $result ?? []),
	'COMMAND' => HTTP::POST('command'),
]);

$template = Template\Factory::build('main');
$template->set('NAME', 'SQL');
$template->set('HTML', $dbEditorTemplate);

echo $template;
