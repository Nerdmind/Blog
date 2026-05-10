<?php

$language = Application::getLanguage();

$repository = Application::getRepository($entityNs);

if(!$entity = $repository->find(HTTP::GET('id'))) {
	Application::error404();
}

if(HTTP::issetPOST('update')) {
	$slugFrom = 'name';

	if($entityNs === 'Category') {
		// Modify parent field only if it's not a self-reference
		if(HTTP::POST('parent') != $entity->getID()) {
			$entity->set('parent', HTTP::POST('parent') ?: null);
		}
	}

	else if($entityNs === 'Post') {
		$entity->set('category', HTTP::POST('category') ?: null);
	}

	else if($entityNs === 'User') {
		$slugFrom = 'username';

		$entity->set('fullname', HTTP::POST('fullname') ?: null);
		$entity->set('username', HTTP::POST('username') ?: null);
		$entity->set('mailaddr', HTTP::POST('mailaddr') ?: null);
		$entity->set('password', HTTP::POST('password') ? password_hash(HTTP::POST('password'), PASSWORD_BCRYPT, ['cost' => 10]) : false);
	}

	if(in_array($entityNs, ['Category', 'Page', 'Post'])) {
		$entity->set('name', HTTP::POST('name') ?: null);
	}

	if(in_array($entityNs, ['Page', 'Post'])) {
		$entity->set('user', HTTP::POST('user'));
	}

	// Common for all entity types
	$entity->set('body', HTTP::POST('body') ?: null);
	$entity->set('argv', HTTP::POST('argv') ?: null);
	$entity->set('slug', HTTP::POST('slug') ?: generateSlug(HTTP::POST($slugFrom)));
	$entity->set('time_insert', HTTP::POST('time_insert') ?: date('Y-m-d H:i:s'));
	$entity->set('time_update', HTTP::POST('time_update') ?: date('Y-m-d H:i:s'));

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$repository->update($entity);
		} catch(PDOException $exception) {
			$messages[] = $exception->getMessage();
		}
	}

	else {
		$messages[] = $language->text('error_security_csrf');
	}
}

$formData = [
	'TYPE' => 'UPDATE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($entity->getAll(), CASE_UPPER),
	'TOKEN' => Application::getSecurityToken()
];

// For pages and posts we have a user
if(in_array($entityNs, ['Page', 'Post'])) {
	$userRepository = Application::getRepository('User');

	foreach($userRepository->getAll([], 'fullname ASC') as $user) {
		$userList[] = [
			'ID' => $user->getID(),
			'FULLNAME' => $user->get('fullname'),
			'USERNAME' => $user->get('username'),
		];
	}

	$formData['USER_LIST'] = $userList ?? [];
}

// For posts and categories, we also have a category list
if(in_array($entityNs, ['Category', 'Post'])) {
	$categoryRepository = Application::getRepository('Category');

	foreach($categoryRepository->getAll([], 'name ASC') as $category) {
		$categoryList[] = [
			'ID' => $category->getID(),
			'NAME' => $category->get('name'),
			'PARENT' => $category->get('parent'),
		];
	}

	$formData['CATEGORY_LIST'] = $categoryList ?? [];
	$formData['CATEGORY_TREE'] = generateCategoryDataTree($categoryList ?? []);
}

$formTemplate = Template\Factory::build(sprintf('%s/form', $entityStr));
$formTemplate->set('FORM', $formData);

$updateTemplate = Template\Factory::build(sprintf('%s/update', $entityStr));
$updateTemplate->set(strtoupper($entityNs), generateItemTemplateData($entity));
$updateTemplate->set('HTML', $formTemplate);

$template = Template\Factory::build('main');
$template->set('NAME', $language->text(sprintf('title_%s_update', $entityStr)));
$template->set('HTML', $updateTemplate);

echo $template;
