<?php

$language = Application::getLanguage();

$repository = Application::getRepository($entityNs);

if(!$entity = $repository->find(HTTP::GET('id'))) {
	Application::error404();
}

if(HTTP::issetPOST('delete')) {
	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$repository->delete($entity);

			HTTP::redirect(
				Application::getAdminURL(sprintf('%s/', $entityStr)));
		} catch(PDOException $exception) {
			$messages[] = $exception->getMessage();
		}
	} else {
		$messages[] = $language->text('error_security_csrf');
	}
}

if($entityNs === 'User') {
	$excludeList = ['password'];
}

$formTemplate = Template\Factory::build(sprintf('%s/form', $entityStr));
$formTemplate->set('HTML', parseEntityContent($entity));
$formTemplate->set('FORM', [
	'TYPE' => 'DELETE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($entity->getAll($excludeList ?? []), CASE_UPPER),
	'TOKEN' => Application::getSecurityToken()
]);

$deleteTemplate = Template\Factory::build(sprintf('%s/delete', $entityStr));
$deleteTemplate->set('HTML', $formTemplate);

$template = Template\Factory::build('main');
$template->set('NAME', $language->text(sprintf('title_%s_delete', $entityStr)));
$template->set('HTML', $deleteTemplate);

echo $template;
