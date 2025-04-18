<?php

$Language = Application::getLanguage();

$Repository = Application::getRepository($entity_ns);

if(!$Entity = $Repository->find(HTTP::GET('id'))) {
	Application::error404();
}

if(HTTP::issetPOST('delete')) {
	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$Repository->delete($Entity);

			HTTP::redirect(
				Application::getAdminURL(sprintf('%s/', $entity_str)));
		} catch(PDOException $Exception) {
			$messages[] = $Exception->getMessage();
		}
	} else {
		$messages[] = $Language->text('error_security_csrf');
	}
}

if($entity_ns === 'User') {
	$exclude_list = ['password'];
}

$FormTemplate = Template\Factory::build(sprintf('%s/form', $entity_str));
$FormTemplate->set('HTML', parseEntityContent($Entity));
$FormTemplate->set('FORM', [
	'TYPE' => 'DELETE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($Entity->getAll($exclude_list ?? []), CASE_UPPER),
	'TOKEN' => Application::getSecurityToken()
]);

$DeleteTemplate = Template\Factory::build(sprintf('%s/delete', $entity_str));
$DeleteTemplate->set('HTML', $FormTemplate);

$Template = Template\Factory::build('main');
$Template->set('NAME', $Language->text(sprintf('title_%s_delete', $entity_str)));
$Template->set('HTML', $DeleteTemplate);

echo $Template;
