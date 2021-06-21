<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
define('ADMINISTRATION', TRUE);
define('AUTHENTICATION', TRUE);

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
if(HTTP::issetPOST('slug', 'username', 'password', 'fullname', 'mailaddr', 'body', 'argv', 'time_insert', 'time_update', 'update')) {
	$User->set('slug',     HTTP::POST('slug') ? HTTP::POST('slug') : generateSlug(HTTP::POST('username')));
	$User->set('username', HTTP::POST('username') ? HTTP::POST('username') : NULL);
	$User->set('password', HTTP::POST('password') ? password_hash(HTTP::POST('password'), PASSWORD_BCRYPT, ['cost' => 10]) : FALSE);
	$User->set('fullname', HTTP::POST('fullname') ? HTTP::POST('fullname') : NULL);
	$User->set('mailaddr', HTTP::POST('mailaddr') ? HTTP::POST('mailaddr') : NULL);
	$User->set('body',     HTTP::POST('body') ? HTTP::POST('body') : NULL);
	$User->set('argv',     HTTP::POST('argv') ? HTTP::POST('argv') : NULL);
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

$InsertTemplate = Template\Factory::build('user/update');
$InsertTemplate->set('HTML', $FormTemplate);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_user_update'));
$MainTemplate->set('HTML', $InsertTemplate);
echo $MainTemplate;
