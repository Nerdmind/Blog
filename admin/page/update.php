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

	if(HTTP::issetPOST('user', 'slug', 'name', 'body', 'argv', 'time_insert', 'time_update', 'update')) {
		$Attribute->set('user', HTTP::POST('user'));
		$Attribute->set('slug', HTTP::POST('slug') ? HTTP::POST('slug') : generateSlug(HTTP::POST('name')));
		$Attribute->set('name', HTTP::POST('name') ? HTTP::POST('name') : NULL);
		$Attribute->set('body', HTTP::POST('body') ? HTTP::POST('body') : NULL);
		$Attribute->set('argv', HTTP::POST('argv') ? HTTP::POST('argv') : NULL);
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

	$userIDs = $Database->query(sprintf('SELECT id FROM %s ORDER BY fullname ASC', User\Attribute::TABLE));

	foreach($userIDs->fetchAll($Database::FETCH_COLUMN) as $userID) {
		$User = User\Factory::build($userID);
		$userAttributes[] = [
			'ID' => $User->attr('id'),
			'FULLNAME' => $User->attr('fullname'),
			'USERNAME' => $User->attr('username'),
		];
	}

	#===============================================================================
	# Build document
	#===============================================================================
	$FormTemplate = Template\Factory::build('page/form');
	$FormTemplate->set('FORM', [
		'TYPE' => 'UPDATE',
		'INFO' => $messages ?? [],
		'DATA' => array_change_key_case($Attribute->getAll(), CASE_UPPER),
		'USER_LIST' => $userAttributes ??  [],
		'TOKEN' => Application::getSecurityToken()
	]);

	$PageUpdateTemplate = Template\Factory::build('page/update');
	$PageUpdateTemplate->set('HTML', $FormTemplate);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', $Language->text('title_page_update'));
	$MainTemplate->set('HTML', $PageUpdateTemplate);
	echo $MainTemplate;
}

#===============================================================================
# CATCH: Page\Exception
#===============================================================================
catch(Page\Exception $Exception) {
	Application::error404();
}
