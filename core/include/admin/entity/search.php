<?php

$language = Application::getLanguage();

$repository = Application::getRepository($entityClassName);

$siteSize = Application::get(sprintf('ADMIN.%s.LIST_SIZE', strtoupper($entityClassName)));
$siteSort = Application::get(sprintf('ADMIN.%s.LIST_SORT', strtoupper($entityClassName)));

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);
$offset = ($currentSite-1) * $siteSize;

if($search = HTTP::GET('q')) {
	try {
		if($entityClassName === 'Post') {
			$filter = [
				'user' => HTTP::GET('user'),
				'category' => HTTP::GET('category')
			];
		}

		if(!$entities = $repository->search($search, $filter ?? [], $siteSize, $offset)) {
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
	$last = ceil($count / $siteSize);

	$paginationData = [
		'THIS' => $currentSite,
		'LAST' => $last,
		'HTML' => createPaginationTemplate(
			$currentSite, $last, Application::getAdminURL(sprintf('%s/search.php', $entityType))
		)
	];
}

$formData = [
	'INFO' => $messages ?? [],
	'DATA' => []
];

// For pages and posts we have a user
if(in_array($entityClassName, ['Page', 'Post'])) {
	$userRepository = Application::getRepository('User');

	foreach($userRepository->getAll([], 'fullname ASC') as $user) {
		$userList[] = [
			'ID' => $user->getID(),
			'FULLNAME' => $user->get('fullname'),
			'USERNAME' => $user->get('username'),
		];

		$formData['USER_LIST'] = $userList ?? [];
	}
}

if($entityClassName === 'Post') {
	$formData['DATA']['USER'] = HTTP::GET('user');
	$formData['DATA']['CATEGORY'] = HTTP::GET('category');

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

$searchTemplate = Template\Factory::build(sprintf('%s/search', $entityType));
$searchTemplate->set('QUERY', $search);
$searchTemplate->set($listKeyPlural, $templates ?? []);
$searchTemplate->set('FORM', $formData);
$searchTemplate->set('PAGINATION', $paginationData ?? []);

$template = Template\Factory::build('main');
$template->set('NAME', $language->text(sprintf('title_%s_search', $entityType)));
$template->set('HTML', $searchTemplate);

echo $template;
