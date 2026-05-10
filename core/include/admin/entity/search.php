<?php

$language = Application::getLanguage();

$repository = Application::getRepository($entity_ns);

$site_size = Application::get(sprintf('ADMIN.%s.LIST_SIZE', strtoupper($entity_ns)));
$site_sort = Application::get(sprintf('ADMIN.%s.LIST_SORT', strtoupper($entity_ns)));

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);
$offset = ($currentSite-1) * $site_size;

if($search = HTTP::GET('q')) {
	try {
		if($entity_ns === 'Post') {
			$filter = [
				'user' => HTTP::GET('user'),
				'category' => HTTP::GET('category')
			];
		}

		if(!$entities = $repository->search($search, $filter ?? [], $site_size, $offset)) {
			$messages[] = Application::getLanguage()->text(
				'search_no_results', htmlspecialchars($search));
		}

		foreach($entities as $entity) {
			$templates[] = createEntityItemTemplate($entity);
		}
	} catch(PDOException $exception) {
		$messages[] = $exception->getMessage();
	}
}

// Create pagination only if there are search results
if($count = $repository->getLastSearchOverallCount()) {
	$last = ceil($count / $site_size);

	$pagination_data = [
		'THIS' => $currentSite,
		'LAST' => $last,
		'HTML' => createPaginationTemplate(
			$currentSite, $last, Application::getAdminURL(sprintf('%s/search.php', $entity_str))
		)
	];
}

$form_data = [
	'INFO' => $messages ?? [],
	'DATA' => []
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

		$form_data['USER_LIST'] = $userList ?? [];
	}
}

if($entity_ns === 'Post') {
	$form_data['DATA']['USER'] = HTTP::GET('user');
	$form_data['DATA']['CATEGORY'] = HTTP::GET('category');

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

$searchTemplate = Template\Factory::build(sprintf('%s/search', $entity_str));
$searchTemplate->set('QUERY', $search);
$searchTemplate->set($list_key_plural, $templates ?? []);
$searchTemplate->set('FORM', $form_data);
$searchTemplate->set('PAGINATION', $pagination_data ?? []);

$template = Template\Factory::build('main');
$template->set('NAME', $language->text(sprintf('title_%s_search', $entity_str)));
$template->set('HTML', $searchTemplate);

echo $template;
