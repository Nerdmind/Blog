<?php

$language = Application::getLanguage();

$entity = new (sprintf('ORM\\Entity\\%s', $entity_ns));

if(HTTP::issetPOST('insert')) {
	$slug_from = 'name';

	if($entity_ns === 'Category') {
		$entity->set('parent', HTTP::POST('parent') ?: null);
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
			$repository = Application::getRepository($entity_ns);
			$repository->insert($entity);

			HTTP::redirect(
				Application::getAdminURL(sprintf('%s/', $entity_str)));
		} catch(PDOException $exception) {
			$messages[] = $exception->getMessage();
		}
	}

	else {
		$messages[] = $language->text('error_security_csrf');
	}
}

if($entity_ns === 'User') {
	$exclude_list = ['password'];
}

$form_data = [
	'TYPE' => 'INSERT',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($entity->getAll($exclude_list ?? []), CASE_UPPER),
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

$insertTemplate = Template\Factory::build(sprintf('%s/insert', $entity_str));
$insertTemplate->set('HTML', $formTemplate);

$template = Template\Factory::build('main');
$template->set('NAME', $language->text(sprintf('title_%s_insert', $entity_str)));
$template->set('HTML', $insertTemplate);

echo $template;
