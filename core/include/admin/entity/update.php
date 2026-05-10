<?php

$language = Application::getLanguage();

$repository = Application::getRepository($entity_ns);

if(!$entity = $repository->find(HTTP::GET('id'))) {
	Application::error404();
}

if(HTTP::issetPOST('update')) {
	$slug_from = 'name';

	if($entity_ns === 'Category') {
		// Modify parent field only if it's not a self-reference
		if(HTTP::POST('parent') != $entity->getID()) {
			$entity->set('parent', HTTP::POST('parent') ?: null);
		}
	}

	else if($entity_ns === 'Post') {
		$entity->set('category', HTTP::POST('category') ?: null);
	}

	else if($entity_ns === 'User') {
		$slug_from = 'username';

		$entity->set('fullname', HTTP::POST('fullname') ?: null);
		$entity->set('username', HTTP::POST('username') ?: null);
		$entity->set('mailaddr', HTTP::POST('mailaddr') ?: null);
		$entity->set('password', HTTP::POST('password') ? password_hash(HTTP::POST('password'), PASSWORD_BCRYPT, ['cost' => 10]) : false);
	}

	if(in_array($entity_ns, ['Category', 'Page', 'Post'])) {
		$entity->set('name', HTTP::POST('name') ?: null);
	}

	if(in_array($entity_ns, ['Page', 'Post'])) {
		$entity->set('user', HTTP::POST('user'));
	}

	// Common for all entity types
	$entity->set('body', HTTP::POST('body') ?: null);
	$entity->set('argv', HTTP::POST('argv') ?: null);
	$entity->set('slug', HTTP::POST('slug') ?: generateSlug(HTTP::POST($slug_from)));
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

$form_data = [
	'TYPE' => 'UPDATE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($entity->getAll(), CASE_UPPER),
	'TOKEN' => Application::getSecurityToken()
];

// For pages and posts we have a user
if(in_array($entity_ns, ['Page', 'Post'])) {
	$userRepository = Application::getRepository('User');

	foreach($userRepository->getAll([], 'fullname ASC') as $user) {
		$userList[] = [
			'ID' => $user->getID(),
			'FULLNAME' => $user->get('fullname'),
			'USERNAME' => $user->get('username'),
		];
	}

	$form_data['USER_LIST'] = $userList ?? [];
}

// For posts and categories, we also have a category list
if(in_array($entity_ns, ['Category', 'Post'])) {
	$categoryRepository = Application::getRepository('Category');

	foreach($categoryRepository->getAll([], 'name ASC') as $category) {
		$categoryList[] = [
			'ID' => $category->getID(),
			'NAME' => $category->get('name'),
			'PARENT' => $category->get('parent'),
		];
	}

	$form_data['CATEGORY_LIST'] = $categoryList ?? [];
	$form_data['CATEGORY_TREE'] = generateCategoryDataTree($categoryList ?? []);
}

$formTemplate = Template\Factory::build(sprintf('%s/form', $entity_str));
$formTemplate->set('FORM', $form_data);

$updateTemplate = Template\Factory::build(sprintf('%s/update', $entity_str));
$updateTemplate->set(strtoupper($entity_ns), generateItemTemplateData($entity));
$updateTemplate->set('HTML', $formTemplate);

$template = Template\Factory::build('main');
$template->set('NAME', $language->text(sprintf('title_%s_update', $entity_str)));
$template->set('HTML', $updateTemplate);

echo $template;
