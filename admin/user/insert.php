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

$Attribute = new User\Attribute();

if(HTTP::issetPOST('id', 'slug', 'username', 'password', 'fullname', 'mailaddr', 'body', 'argv', 'time_insert', 'time_update', 'insert')) {
	$Attribute->set('id',       HTTP::POST('id') ? HTTP::POST('id') : FALSE);
	$Attribute->set('slug',     HTTP::POST('slug') ? HTTP::POST('slug') : generateSlug(HTTP::POST('username')));
	$Attribute->set('username', HTTP::POST('username') ? HTTP::POST('username') : NULL);
	$Attribute->set('password', HTTP::POST('password') ? password_hash(HTTP::POST('password'), PASSWORD_BCRYPT, ['cost' => 10]) : FALSE);
	$Attribute->set('fullname', HTTP::POST('fullname') ? HTTP::POST('fullname') : NULL);
	$Attribute->set('mailaddr', HTTP::POST('mailaddr') ? HTTP::POST('mailaddr') : NULL);
	$Attribute->set('body',     HTTP::POST('body') ? HTTP::POST('body') : NULL);
	$Attribute->set('argv',     HTTP::POST('argv') ? HTTP::POST('argv') : NULL);
	$Attribute->set('time_insert', HTTP::POST('time_insert') ?: date('Y-m-d H:i:s'));
	$Attribute->set('time_update', HTTP::POST('time_update') ?: date('Y-m-d H:i:s'));

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			if($Attribute->databaseINSERT($Database)) {
				HTTP::redirect(Application::getAdminURL('user/'));
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
	$FormTemplate = Template\Factory::build('user/form');
	$FormTemplate->set('FORM', [
		'TYPE' => 'INSERT',
		'INFO' => $messages ?? [],
		'DATA' => array_change_key_case($Attribute->getAll(['password']), CASE_UPPER),
		'TOKEN' => Application::getSecurityToken()
	]);

	$InsertTemplate = Template\Factory::build('user/insert');
	$InsertTemplate->set('HTML', $FormTemplate);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', $Language->text('title_user_insert'));
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