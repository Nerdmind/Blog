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
$PostRepository = Application::getRepository('Post');

#===============================================================================
# Throw 404 error if post could not be found
#===============================================================================
if(!$Post = $PostRepository->find(HTTP::GET('id'))) {
	Application::error404();
}

#===============================================================================
# Check for delete request
#===============================================================================
if(HTTP::issetPOST(['token' => Application::getSecurityToken()], 'delete')) {
	try {
		if($PostRepository->delete($Post)) {
			HTTP::redirect(Application::getAdminURL('post/'));
		}
	} catch(PDOException $Exception) {
		$messages[] = $Exception->getMessage();
	}
}

#===============================================================================
# Build document
#===============================================================================
$FormTemplate = Template\Factory::build('post/form');
$FormTemplate->set('HTML', parseEntityContent($Post));
$FormTemplate->set('FORM', [
	'TYPE' => 'DELETE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($Post->getAll(), CASE_UPPER),
	'TOKEN' => Application::getSecurityToken()
]);

$DeleteTemplate = Template\Factory::build('post/delete');
$DeleteTemplate->set('HTML', $FormTemplate);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_post_delete'));
$MainTemplate->set('HTML', $DeleteTemplate);
echo $MainTemplate;
