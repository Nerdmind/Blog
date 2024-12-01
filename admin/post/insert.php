<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
const ADMINISTRATION = true;
const AUTHENTICATION = true;

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
# Instantiate new Post entity
#===============================================================================
$Post = new ORM\Entities\Post;

#===============================================================================
# Check for insert request
#===============================================================================
if(HTTP::issetPOST('insert')) {
	$Post->set('category', HTTP::POST('category') ?: null);
	$Post->set('user', HTTP::POST('user'));
	$Post->set('slug', HTTP::POST('slug') ?: generateSlug(HTTP::POST('name')));
	$Post->set('name', HTTP::POST('name') ?: null);
	$Post->set('body', HTTP::POST('body') ?: null);
	$Post->set('argv', HTTP::POST('argv') ?: null);
	$Post->set('time_insert', HTTP::POST('time_insert') ?: date('Y-m-d H:i:s'));
	$Post->set('time_update', HTTP::POST('time_update') ?: date('Y-m-d H:i:s'));

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$PostRepository->insert($Post);
			HTTP::redirect(Application::getAdminURL('post/'));
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
	'TYPE' => 'INSERT',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($Post->getAll(), CASE_UPPER),
	'USER_LIST' => $userList ??  [],
	'CATEGORY_LIST' => $categoryList ?? [],
	'CATEGORY_TREE' => generateCategoryDataTree($categoryList ?? []),
	'TOKEN' => Application::getSecurityToken()
]);

$InsertTemplate = Template\Factory::build('post/insert');
$InsertTemplate->set('HTML', $FormTemplate);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_post_insert'));
$MainTemplate->set('HTML', $InsertTemplate);
echo $MainTemplate;
