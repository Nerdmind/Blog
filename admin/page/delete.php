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
# TRY: Page\Exception
#===============================================================================
try {
	$Page = Page\Factory::build(HTTP::GET('id'));
	$Attribute = $Page->getAttribute();

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()], 'delete')) {
		try {
			if($Attribute->delete($Database)) {
				HTTP::redirect(Application::getAdminURL('page/'));
			}
		} catch(PDOException $Exception) {
			$messages[] = $Exception->getMessage();
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
		'DATA' => array_change_key_case($Attribute->getAll(), CASE_UPPER),
		'TOKEN' => Application::getSecurityToken()
	]);

	$DeleteTemplate = Template\Factory::build('page/delete');
	$DeleteTemplate->set('HTML', $FormTemplate);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', $Language->text('title_page_delete'));
	$MainTemplate->set('HTML', $DeleteTemplate);
	echo $MainTemplate;
}

#===============================================================================
# CATCH: Page\Exception
#===============================================================================
catch(Page\Exception $Exception) {
	Application::error404();
}
