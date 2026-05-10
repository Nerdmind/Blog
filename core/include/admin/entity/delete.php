<?php

$language = Application::getLanguage();

$repository = Application::getRepository($entity_ns);

if(!$entity = $repository->find(HTTP::GET('id'))) {
	Application::error404();
}

if(HTTP::issetPOST('delete')) {
	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$repository->delete($entity);

			HTTP::redirect(
				Application::getAdminURL(sprintf('%s/', $entity_str)));
		} catch(PDOException $exception) {
			$messages[] = $exception->getMessage();
		}
	} else {
		$messages[] = $language->text('error_security_csrf');
	}
}

if($entity_ns === 'User') {
	$exclude_list = ['password'];
}

$formTemplate = Template\Factory::build(sprintf('%s/form', $entity_str));
$formTemplate->set('HTML', parseEntityContent($entity));
$formTemplate->set('FORM', [
	'TYPE' => 'DELETE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($entity->getAll($exclude_list ?? []), CASE_UPPER),
	'TOKEN' => Application::getSecurityToken()
]);

$deleteTemplate = Template\Factory::build(sprintf('%s/delete', $entity_str));
$deleteTemplate->set('HTML', $formTemplate);

$template = Template\Factory::build('main');
$template->set('NAME', $language->text(sprintf('title_%s_delete', $entity_str)));
$template->set('HTML', $deleteTemplate);

echo $template;
