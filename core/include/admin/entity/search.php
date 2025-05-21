<?php

$Language = Application::getLanguage();

$Repository = Application::getRepository($entity_ns);

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

		if(!$entities = $Repository->search($search, $filter ?? [], $site_size, $offset)) {
			$messages[] = Application::getLanguage()->text(
				'search_no_results', htmlspecialchars($search));
		}

		foreach($entities as $Entity) {
			$templates[] = createEntityItemTemplate($Entity);
		}
	} catch(PDOException $Exception) {
		$messages[] = $Exception->getMessage();
	}
}

// Create pagination only if there are search results
if($count = $Repository->getLastSearchOverallCount()) {
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
	$UserRepository = Application::getRepository('User');

	foreach($UserRepository->getAll([], 'fullname ASC') as $User) {
		$userList[] = [
			'ID' => $User->getID(),
			'FULLNAME' => $User->get('fullname'),
			'USERNAME' => $User->get('username'),
		];

		$form_data['USER_LIST'] = $userList ?? [];
	}
}

if($entity_ns === 'Post') {
	$form_data['DATA']['USER'] = HTTP::GET('user');
	$form_data['DATA']['CATEGORY'] = HTTP::GET('category');

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

$SearchTemplate = Template\Factory::build(sprintf('%s/search', $entity_str));
$SearchTemplate->set('QUERY', $search);
$SearchTemplate->set($list_key_plural, $templates ?? []);
$SearchTemplate->set('FORM', $form_data);
$SearchTemplate->set('PAGINATION', $pagination_data ?? []);

$Template = Template\Factory::build('main');
$Template->set('NAME', $Language->text(sprintf('title_%s_search', $entity_str)));
$Template->set('HTML', $SearchTemplate);

echo $Template;
