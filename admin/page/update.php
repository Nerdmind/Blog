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
$UserRepository = Application::getRepository('User');

#===============================================================================
# Throw 404 error if page could not be found
#===============================================================================
if(!$Page = $PageRepository->find(HTTP::GET('id'))) {
	Application::error404();
}

#===============================================================================
# Check for update request
#===============================================================================
if(HTTP::issetPOST('update')) {
	$Page->set('user', HTTP::POST('user'));
	$Page->set('slug', HTTP::POST('slug') ?: generateSlug(HTTP::POST('name')));
	$Page->set('name', HTTP::POST('name') ?: NULL);
	$Page->set('body', HTTP::POST('body') ?: NULL);
	$Page->set('argv', HTTP::POST('argv') ?: NULL);
	$Page->set('time_insert', HTTP::POST('time_insert') ?: date('Y-m-d H:i:s'));
	$Page->set('time_update', HTTP::POST('time_update') ?: date('Y-m-d H:i:s'));

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$PageRepository->update($Page);
		} catch(PDOException $Exception) {
			$messages[] = $Exception->getMessage();
		}
	}

	else {
		$messages[] = $Language->text('error_security_csrf');
	}
}

#===============================================================================
# Generate user list
#===============================================================================
foreach($UserRepository->getAll([], 'fullname ASC') as $User) {
	$userList[] = [
		'ID' => $User->getID(),
		'FULLNAME' => $User->get('fullname'),
		'USERNAME' => $User->get('username'),
	];
}

#===============================================================================
# Build document
#===============================================================================
$FormTemplate = Template\Factory::build('page/form');
$FormTemplate->set('FORM', [
	'TYPE' => 'UPDATE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($Page->getAll(), CASE_UPPER),
	'USER_LIST' => $userList ??  [],
	'TOKEN' => Application::getSecurityToken()
]);

$UpdateTemplate = Template\Factory::build('page/update');
$UpdateTemplate->set('PAGE', generateItemTemplateData($Page));
$UpdateTemplate->set('HTML', $FormTemplate);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_page_update'));
$MainTemplate->set('HTML', $UpdateTemplate);
echo $MainTemplate;
