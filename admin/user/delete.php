<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
const ADMINISTRATION = TRUE;
const AUTHENTICATION = TRUE;

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
# Check for delete request
#===============================================================================
if(HTTP::issetPOST(['token' => Application::getSecurityToken()], 'delete')) {
	try {
		if($UserRepository->delete($User)) {
			HTTP::redirect(Application::getAdminURL('user/'));
		}
	} catch(PDOException $Exception) {
		$messages[] = $Exception->getMessage();
	}
}

#===============================================================================
# Build document
#===============================================================================
$FormTemplate = Template\Factory::build('user/form');
$FormTemplate->set('HTML', parseEntityContent($User));
$FormTemplate->set('FORM', [
	'TYPE' => 'DELETE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($User->getAll(['password']), CASE_UPPER),
	'TOKEN' => Application::getSecurityToken()
]);

$DeleteTemplate = Template\Factory::build('user/delete');
$DeleteTemplate->set('HTML', $FormTemplate);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_user_delete'));
$MainTemplate->set('HTML', $DeleteTemplate);
echo $MainTemplate;
