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
$PageRepository = Application::getRepository('Page');

#===============================================================================
# Throw 404 error if page could not be found
#===============================================================================
if(!$Page = $PageRepository->find(HTTP::GET('id'))) {
	Application::error404();
}

#===============================================================================
# Check for delete request
#===============================================================================
if(HTTP::issetPOST('delete')) {
	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$PageRepository->delete($Page);
			HTTP::redirect(Application::getAdminURL('page/'));
		} catch(PDOException $Exception) {
			$messages[] = $Exception->getMessage();
		}
	} else {
		$messages[] = $Language->text('error_security_csrf');
	}
}

#===============================================================================
# Build document
#===============================================================================
$FormTemplate = Template\Factory::build('page/form');
$FormTemplate->set('HTML', parseEntityContent($Page));
$FormTemplate->set('FORM', [
	'TYPE' => 'DELETE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($Page->getAll(), CASE_UPPER),
	'TOKEN' => Application::getSecurityToken()
]);

$DeleteTemplate = Template\Factory::build('page/delete');
$DeleteTemplate->set('HTML', $FormTemplate);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_page_delete'));
$MainTemplate->set('HTML', $DeleteTemplate);
echo $MainTemplate;
