<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
define('ADMINISTRATION', TRUE);
define('AUTHENTICATION', TRUE);

#===============================================================================
# INCLUDE: Main configuration
#===============================================================================
require '../../core/application.php';

#===============================================================================
# TRY: User\Exception
#===============================================================================
try {
	$User = User\Factory::build(HTTP::GET('id'));
	$Attribute = $User->getAttribute();

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()], 'delete')) {
		try {
			if($Attribute->databaseDELETE($Database)) {
				HTTP::redirect(Application::getAdminURL('user/'));
			}
		} catch(PDOException $Exception) {
			$messages[] = $Exception->getMessage();
		}
	}

	#===============================================================================
	# TRY: Template\Exception
	#===============================================================================
	try {
		$FormTemplate = Template\Factory::build('user/form');
		$FormTemplate->set('HTML', $User->getHTML());
		$FormTemplate->set('FORM', [
			'TYPE' => 'DELETE',
			'INFO' => $messages ?? [],
			'DATA' => [
				'ID'   => $Attribute->get('id'),
				'BODY' => $Attribute->get('body'),
				'TIME_INSERT' => $Attribute->get('time_insert'),
				'TIME_UPDATE' => $Attribute->get('time_update'),
			],
			'TOKEN' => Application::getSecurityToken()
		]);

		$DeleteTemplate = Template\Factory::build('user/delete');
		$DeleteTemplate->set('HTML', $FormTemplate);

		$MainTemplate = Template\Factory::build('main');
		$MainTemplate->set('NAME', $Language->text('title_user_delete'));
		$MainTemplate->set('HTML', $DeleteTemplate);
		echo $MainTemplate;
	}

	#===============================================================================
	# CATCH: Template\Exception
	#===============================================================================
	catch(Template\Exception $Exception) {
		Application::exit($Exception->getMessage());
	}
}

#===============================================================================
# CATCH: User\Exception
#===============================================================================
catch(User\Exception $Exception) {
	Application::error404();
}
?>

