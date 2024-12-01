<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
const ADMINISTRATION = true;
const AUTHENTICATION = true;

#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require '../../core/application.php';

#===============================================================================
# Get repositories
#===============================================================================
$UserRepository = Application::getRepository('User');

#===============================================================================
# Throw 404 error if user could not be found
#===============================================================================
if(!$User = $UserRepository->find(HTTP::GET('id'))) {
	Application::error404();
}

#===============================================================================
# Check for update request
#===============================================================================
if(HTTP::issetPOST('update')) {
	$User->set('slug',     HTTP::POST('slug') ?: generateSlug(HTTP::POST('username')));
	$User->set('username', HTTP::POST('username') ?: null);
	$User->set('password', HTTP::POST('password') ? password_hash(HTTP::POST('password'), PASSWORD_BCRYPT, ['cost' => 10]) : false);
	$User->set('fullname', HTTP::POST('fullname') ?: null);
	$User->set('mailaddr', HTTP::POST('mailaddr') ?: null);
	$User->set('body',     HTTP::POST('body') ?: null);
	$User->set('argv',     HTTP::POST('argv') ?: null);
	$User->set('time_insert', HTTP::POST('time_insert') ?: date('Y-m-d H:i:s'));
	$User->set('time_update', HTTP::POST('time_update') ?: date('Y-m-d H:i:s'));

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$UserRepository->update($User);
		} catch(PDOException $Exception) {
			$messages[] = $Exception->getMessage();
		}
	}

	else {
		$messages[] = $Language->text('error_security_csrf');
	}
}

#===============================================================================
# Build document
#===============================================================================
$FormTemplate = Template\Factory::build('user/form');
$FormTemplate->set('FORM', [
	'TYPE' => 'UPDATE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($User->getAll(['password']), CASE_UPPER),
	'TOKEN' => Application::getSecurityToken()
]);

$UpdateTemplate = Template\Factory::build('user/update');
$UpdateTemplate->set('USER', generateItemTemplateData($User));
$UpdateTemplate->set('HTML', $FormTemplate);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_user_update'));
$MainTemplate->set('HTML', $UpdateTemplate);
echo $MainTemplate;
