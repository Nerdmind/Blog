<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
define('ADMINISTRATION', TRUE);
define('AUTHENTICATION', TRUE);

#===============================================================================
# INCLUDE: Main configuration
#===============================================================================
require_once '../../core/application.php';

$Attribute = new Post\Attribute();

if(HTTP::issetPOST('id', 'user', 'slug', 'name', 'body', 'time_insert', 'time_update', 'insert')) {
	$Attribute->set('id',   HTTP::POST('id') ? HTTP::POST('id') : FALSE);
	$Attribute->set('user', HTTP::POST('user'));
	$Attribute->set('slug', HTTP::POST('slug') ? HTTP::POST('slug') : makeSlugURL(HTTP::POST('name')));
	$Attribute->set('name', HTTP::POST('name') ? HTTP::POST('name') : NULL);
	$Attribute->set('body', HTTP::POST('body') ? HTTP::POST('body') : NULL);
	$Attribute->set('time_insert', HTTP::POST('time_insert') ? HTTP::POST('time_insert') : date('Y-m-d H:i:s'));
	$Attribute->set('time_update', HTTP::POST('time_update') ? HTTP::POST('time_update') : date('Y-m-d H:i:s'));

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			if($Attribute->databaseINSERT($Database)) {
				HTTP::redirect(Application::getAdminURL('post/'));
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

	$FormTemplate = Template\Factory::build('post/form');
	$FormTemplate->set('FORM', [
		'TYPE' => 'INSERT',
		'INFO' => $messages ?? [],
		'DATA' => [
			'ID'   => $Attribute->get('id'),
			'USER' => $Attribute->get('user'),
			'SLUG' => $Attribute->get('slug'),
			'NAME' => $Attribute->get('name'),
			'BODY' => $Attribute->get('body'),
			'TIME_INSERT' => $Attribute->get('time_insert'),
			'TIME_UPDATE' => $Attribute->get('time_update'),
		],
		'USER_LIST' => $userAttributes ??  [],
		'TOKEN' => Application::getSecurityToken()
	]);

	$InsertTemplate = Template\Factory::build('post/insert');
	$InsertTemplate->set('HTML', $FormTemplate);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', $Language->text('title_post_insert'));
	$MainTemplate->set('HTML', $InsertTemplate);
	echo $MainTemplate;
}

#===============================================================================
# CATCH: Template\Exception
#===============================================================================
catch(Template\Exception $Exception) {
	$Exception->defaultHandler();
}
?>