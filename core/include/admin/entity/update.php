<?php

$Language = Application::getLanguage();

$Repository = Application::getRepository($entity_ns);

if(!$Entity = $Repository->find(HTTP::GET('id'))) {
	Application::error404();
}

if(HTTP::issetPOST('update')) {
	$slug_from = 'name';

	if($entity_ns === 'Category') {
		// Modify parent field only if it's not a self-reference
		if(HTTP::POST('parent') != $Entity->getID()) {
			$Entity->set('parent', HTTP::POST('parent') ?: null);
		}
	}

	else if($entity_ns === 'Post') {
		$Entity->set('category', HTTP::POST('category') ?: null);
	}

	else if($entity_ns === 'User') {
		$slug_from = 'username';

		$Entity->set('fullname', HTTP::POST('fullname') ?: null);
		$Entity->set('username', HTTP::POST('username') ?: null);
		$Entity->set('mailaddr', HTTP::POST('mailaddr') ?: null);
		$Entity->set('password', HTTP::POST('password') ? password_hash(HTTP::POST('password'), PASSWORD_BCRYPT, ['cost' => 10]) : false);
	}

	if(in_array($entity_ns, ['Category', 'Page', 'Post'])) {
		$Entity->set('name', HTTP::POST('name') ?: null);
	}

	if(in_array($entity_ns, ['Page', 'Post'])) {
		$Entity->set('user', HTTP::POST('user'));
	}

	// Common for all entity types
	$Entity->set('body', HTTP::POST('body') ?: null);
	$Entity->set('argv', HTTP::POST('argv') ?: null);
	$Entity->set('slug', HTTP::POST('slug') ?: generateSlug(HTTP::POST($slug_from)));
	$Entity->set('time_insert', HTTP::POST('time_insert') ?: date('Y-m-d H:i:s'));
	$Entity->set('time_update', HTTP::POST('time_update') ?: date('Y-m-d H:i:s'));

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$Repository->update($Entity);
		} catch(PDOException $Exception) {
			$messages[] = $Exception->getMessage();
		}
	}

	else {
		$messages[] = $Language->text('error_security_csrf');
	}
}

$form_data = [
	'TYPE' => 'UPDATE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($Entity->getAll(), CASE_UPPER),
	'TOKEN' => Application::getSecurityToken()
];

// For pages and posts we have a user
if(in_array($entity_ns, ['Page', 'Post'])) {
	$UserRepository = Application::getRepository('User');

	foreach($UserRepository->getAll([], 'fullname ASC') as $User) {
		$userList[] = [
			'ID' => $User->getID(),
			'FULLNAME' => $User->get('fullname'),
			'USERNAME' => $User->get('username'),
		];
	}

	$form_data['USER_LIST'] = $userList ?? [];
}

// For posts and categories, we also have a category list
if(in_array($entity_ns, ['Category', 'Post'])) {
	$CategoryRepository = Application::getRepository('Category');

	foreach($CategoryRepository->getAll([], 'name ASC') as $Category) {
		$categoryList[] = [
			'ID' => $Category->getID(),
			'NAME' => $Category->get('name'),
			'PARENT' => $Category->get('parent'),
		];
	}

	$form_data['CATEGORY_LIST'] = $categoryList ?? [];
	$form_data['CATEGORY_TREE'] = generateCategoryDataTree($categoryList ?? []);
}

$FormTemplate = Template\Factory::build(sprintf('%s/form', $entity_str));
$FormTemplate->set('FORM', $form_data);

$UpdateTemplate = Template\Factory::build(sprintf('%s/update', $entity_str));
$UpdateTemplate->set(strtoupper($entity_ns), generateItemTemplateData($Entity));
$UpdateTemplate->set('HTML', $FormTemplate);

$Template = Template\Factory::build('main');
$Template->set('NAME', $Language->text(sprintf('title_%s_update', $entity_str)));
$Template->set('HTML', $UpdateTemplate);

echo $Template;
