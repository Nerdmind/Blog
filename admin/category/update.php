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
# TRY: Page\Exception
#===============================================================================
try {
	$Category = Category\Factory::build(HTTP::GET('id'));
	$Attribute = $Category->getAttribute();

	if(HTTP::issetPOST('name', 'slug', 'time_insert', 'time_update', 'update')) {
        $Attribute->set('name', HTTP::POST('name') ? HTTP::POST('name') : NULL);
		$Attribute->set('slug', HTTP::POST('slug') ? HTTP::POST('slug') : generateSlug(HTTP::POST('name')));
		$Attribute->set('time_insert', HTTP::POST('time_insert') ?: date('Y-m-d H:i:s'));
		$Attribute->set('time_update', HTTP::POST('time_update') ?: date('Y-m-d H:i:s'));

		if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
			try {
				$Attribute->databaseUPDATE($Database);
			} catch(PDOException $Exception) {
				$messages[] = $Exception->getMessage();
			}
		}

		else {
			$messages[] = $Language->text('error_security_csrf');
		}
	}

	#===============================================================================
	# TRY: Template\Exception
	#===============================================================================
	try {
		$FormTemplate = Template\Factory::build('category/form');
		$FormTemplate->set('FORM', [
			'TYPE' => 'UPDATE',
			'INFO' => $messages ?? [],
			'DATA' => array_change_key_case($Attribute->getAll(), CASE_UPPER),
			'TOKEN' => Application::getSecurityToken()
		]);

		$CategoryUpdateTemplate = Template\Factory::build('category/update');
		$CategoryUpdateTemplate->set('HTML', $FormTemplate);

		$MainTemplate = Template\Factory::build('main');
		$MainTemplate->set('NAME', $Language->text('title_category_update'));
		$MainTemplate->set('HTML', $CategoryUpdateTemplate);
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
# CATCH: Page\Exception
#===============================================================================
catch(Page\Exception $Exception) {
	Application::error404();
}
?>