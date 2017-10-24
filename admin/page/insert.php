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

$Attribute = new Page\Attribute();

if(HTTP::issetPOST('id', 'user', 'slug', 'name', 'body', 'argv', 'time_insert', 'time_update', 'insert')) {
	$Attribute->set('id',   HTTP::POST('id') ? HTTP::POST('id') : FALSE);
	$Attribute->set('user', HTTP::POST('user'));
	$Attribute->set('slug', HTTP::POST('slug') ? HTTP::POST('slug') : generateSlug(HTTP::POST('name')));
	$Attribute->set('name', HTTP::POST('name') ? HTTP::POST('name') : NULL);
	$Attribute->set('body', HTTP::POST('body') ? HTTP::POST('body') : NULL);
	$Attribute->set('argv', HTTP::POST('argv') ? HTTP::POST('argv') : NULL);
	$Attribute->set('time_insert', HTTP::POST('time_insert') ?: date('Y-m-d H:i:s'));
	$Attribute->set('time_update', HTTP::POST('time_update') ?: date('Y-m-d H:i:s'));

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			if($Attribute->databaseINSERT($Database)) {
				HTTP::redirect(Application::getAdminURL('page/'));
			}
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
	$userIDs = $Database->query(sprintf('SELECT id FROM %s ORDER BY fullname ASC', User\Attribute::TABLE));

	foreach($userIDs->fetchAll($Database::FETCH_COLUMN) as $userID) {
		$User = User\Factory::build($userID);
		$userAttributes[] = [
			'ID' => $User->attr('id'),
			'FULLNAME' => $User->attr('fullname'),
			'USERNAME' => $User->attr('username'),
		];
	}

	$FormTemplate = Template\Factory::build('page/form');
	$FormTemplate->set('FORM', [
		'TYPE' => 'INSERT',
		'INFO' => $messages ?? [],
		'DATA' => array_change_key_case($Attribute->getAll(), CASE_UPPER),
		'USER_LIST' => $userAttributes ??  [],
		'TOKEN' => Application::getSecurityToken()
	]);

	$InsertTemplate = Template\Factory::build('page/insert');
	$InsertTemplate->set('HTML', $FormTemplate);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', $Language->text('title_page_insert'));
	$MainTemplate->set('HTML', $InsertTemplate);
	echo $MainTemplate;
}

#===============================================================================
# CATCH: Template\Exception
#===============================================================================
catch(Template\Exception $Exception) {
	Application::exit($Exception->getMessage());
}
?>