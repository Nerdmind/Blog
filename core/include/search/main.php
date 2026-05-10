<?php
#===============================================================================
# Get instances
#===============================================================================
$language = Application::getLanguage();

#===============================================================================
# Get repositories
#===============================================================================
$postRepository = Application::getRepository('Post');

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('POST.LIST_SIZE');
$site_sort = Application::get('POST.LIST_SORT');

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);
$offset = ($currentSite-1) * $site_size;

if($search = HTTP::GET('q')) {
	$filter = [
		'day'   => HTTP::GET('d'),
		'month' => HTTP::GET('m'),
		'year'  => HTTP::GET('y')
	];

	try {
		if (!$posts = $postRepository->search($search, $filter, $site_size, $offset)) {
			$message = $language->text('search_no_results', htmlspecialchars($search));
		}
	} catch(PDOException $exception) {
		$message = $exception->getMessage();
	}
}

$form_data = [
	'SELECT' => [
		'D' => HTTP::GET('d'),
		'M' => HTTP::GET('m'),
		'Y' => HTTP::GET('y'),
	],
	'OPTIONS' => [
		'D' => $postRepository->getDistinctDays(),
		'M' => $postRepository->getDistinctMonths(),
		'Y' => $postRepository->getDistinctYears(),
	]
];

$search_data = [
	'TEXT' => $search,
	'INFO' => isset($message) ? $message : false,
];

#===============================================================================
# Build document
#===============================================================================
if(!empty($posts)) {
	foreach($posts as $post) {
		$templates[] = createEntityItemTemplate($post);
	}

	$count = $postRepository->getLastSearchOverallCount();
	$last = ceil($count / $site_size);

	$resultTemplate = Template\Factory::build('search/result');
	$resultTemplate->set('FORM', $form_data);
	$resultTemplate->set('SEARCH', $search_data);
	$resultTemplate->set('RESULT', [
		'LIST' => $templates ?? []
	]);

	$resultTemplate->set('PAGINATION', [
		'THIS' => $currentSite,
		'LAST' => $last,
		'HTML' => createPaginationTemplate(
			$currentSite, $last, Application::getURL('search/')
		)
	]);

	$mainTemplate = Template\Factory::build('main');
	$mainTemplate->set('HTML', $resultTemplate);
	$mainTemplate->set('HEAD', [
		'NAME' => $language->text('title_search_results', htmlspecialchars($search)),
		'PERM' => Application::getURL('search/')
	]);
}

else {
	$searchTemplate = Template\Factory::build('search/main');
	$searchTemplate->set('FORM', $form_data);
	$searchTemplate->set('SEARCH', $search_data);

	$mainTemplate = Template\Factory::build('main');
	$mainTemplate->set('HTML', $searchTemplate);
	$mainTemplate->set('HEAD', [
		'NAME' => $language->text('title_search_request'),
		'PERM' => Application::getURL('search/')
	]);
}

echo $mainTemplate;
