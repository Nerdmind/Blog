<?php

$language = Application::getLanguage();

$repository = Application::getRepository($entityClassName);

if(!$entity = $repository->find(HTTP::GET('id'))) {
	Application::error404();
}

if(HTTP::issetPOST('delete')) {
	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$repository->delete($entity);

			HTTP::redirect(
				Application::getAdminURL(sprintf('%s/', $entityType)));
		} catch(PDOException $exception) {
			$messages[] = $exception->getMessage();
		}
	} else {
		$messages[] = $language->text('error_security_csrf');
	}
}

if($entityClassName === 'User') {
	$excludeList = ['password'];
}

$formTemplate = Template\Factory::build(sprintf('%s/form', $entityType));
$formTemplate->set('HTML', parseEntityContent($entity));
$formTemplate->set('FORM', [
	'TYPE' => 'DELETE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($entity->getAll($excludeList ?? []), CASE_UPPER),
	'TOKEN' => Application::getSecurityToken()
]);

$deleteTemplate = Template\Factory::build(sprintf('%s/delete', $entityType));
$deleteTemplate->set('HTML', $formTemplate);

$template = Template\Factory::build('main');
$template->set('NAME', $language->text(sprintf('title_%s_delete', $entityType)));
$template->set('HTML', $deleteTemplate);

echo $template;
