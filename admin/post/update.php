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
$CategoryRepository = Application::getRepository('Category');
$PostRepository = Application::getRepository('Post');
$UserRepository = Application::getRepository('User');

#===============================================================================
# Throw 404 error if post could not be found
#===============================================================================
if(!$Post = $PostRepository->find(HTTP::GET('id'))) {
	Application::error404();
}

#===============================================================================
# Check for update request
#===============================================================================
if(HTTP::issetPOST('update')) {
	$Post->set('category', HTTP::POST('category') ?: NULL);
	$Post->set('user', HTTP::POST('user'));
	$Post->set('slug', HTTP::POST('slug') ?: generateSlug(HTTP::POST('name')));
	$Post->set('name', HTTP::POST('name') ?: NULL);
	$Post->set('body', HTTP::POST('body') ?: NULL);
	$Post->set('argv', HTTP::POST('argv') ?: NULL);
	$Post->set('time_insert', HTTP::POST('time_insert') ?: date('Y-m-d H:i:s'));
	$Post->set('time_update', HTTP::POST('time_update') ?: date('Y-m-d H:i:s'));

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$PostRepository->update($Post);
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
# Generate category list
#===============================================================================
foreach($CategoryRepository->getAll([], 'name ASC') as $Category) {
	$categoryList[] = [
		'ID' => $Category->getID(),
		'NAME' => $Category->get('name'),
		'PARENT' => $Category->get('parent'),
	];
}

#===============================================================================
# Build document
#===============================================================================
$FormTemplate = Template\Factory::build('post/form');
$FormTemplate->set('FORM', [
	'TYPE' => 'UPDATE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($Post->getAll(), CASE_UPPER),
	'USER_LIST' => $userList ??  [],
	'CATEGORY_LIST' => $categoryList ?? [],
	'CATEGORY_TREE' => generateCategoryDataTree($categoryList ?? []),
	'TOKEN' => Application::getSecurityToken()
]);

$UpdateTemplate = Template\Factory::build('post/update');
$UpdateTemplate->set('POST', generateItemTemplateData($Post));
$UpdateTemplate->set('HTML', $FormTemplate);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_post_update'));
$MainTemplate->set('HTML', $UpdateTemplate);
echo $MainTemplate;
