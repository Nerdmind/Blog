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
$CategoryRepository = Application::getRepository('Category');

#===============================================================================
# Throw 404 error if category could not be found
#===============================================================================
if(!$Category = $CategoryRepository->find(HTTP::GET('id'))) {
	Application::error404();
}

#===============================================================================
# Check for delete request
#===============================================================================
if(HTTP::issetPOST(['token' => Application::getSecurityToken()], 'delete')) {
	try {
		if($CategoryRepository->delete($Category)) {
			HTTP::redirect(Application::getAdminURL('category/'));
		}
	} catch(PDOException $Exception) {
		$messages[] = $Exception->getMessage();
	}
}

#===============================================================================
# Build document
#===============================================================================
$FormTemplate = Template\Factory::build('category/form');
$FormTemplate->set('HTML', parseEntityContent($Category));
$FormTemplate->set('FORM', [
	'TYPE' => 'DELETE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($Category->getAll(), CASE_UPPER),
	'TOKEN' => Application::getSecurityToken()
]);

$DeleteTemplate = Template\Factory::build('category/delete');
$DeleteTemplate->set('HTML', $FormTemplate);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_category_delete'));
$MainTemplate->set('HTML', $DeleteTemplate);
echo $MainTemplate;
