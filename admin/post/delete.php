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
# TRY: Post\Exception
#===============================================================================
try {
	$Post = Post\Factory::build(HTTP::GET('id'));
	$Attribute = $Post->getAttribute();

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()], 'delete')) {
		try {
			if($Attribute->databaseDELETE($Database)) {
				HTTP::redirect(Application::getAdminURL('post/'));
			}
		} catch(PDOException $Exception) {
			$messages[] = $Exception->getMessage();
		}
	}

	#===============================================================================
	# TRY: Template\Exception
	#===============================================================================
	try {
		$FormTemplate = Template\Factory::build('post/form');
		$FormTemplate->set('HTML', $Post->getHTML());
		$FormTemplate->set('FORM', [
			'TYPE' => 'DELETE',
			'INFO' => $messages ?? [],
			'DATA' => array_change_key_case($Attribute->getAll(), CASE_UPPER),
			'TOKEN' => Application::getSecurityToken()
		]);

		$DeleteTemplate = Template\Factory::build('post/delete');
		$DeleteTemplate->set('HTML', $FormTemplate);

		$MainTemplate = Template\Factory::build('main');
		$MainTemplate->set('NAME', $Language->text('title_post_delete'));
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
# CATCH: Post\Exception
#===============================================================================
catch(Post\Exception $Exception) {
	Application::error404();
}
?>