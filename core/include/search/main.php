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
$pageSize = Application::get('POST.LIST_SIZE');
$sortOrder = Application::get('POST.LIST_SORT');

$pageNumber = HTTP::GET('site') ?? 1;
$pageNumber = intval($pageNumber);
$offset = ($pageNumber-1) * $pageSize;

if($search = HTTP::GET('q')) {
	$filter = [
		'day'   => HTTP::GET('d'),
		'month' => HTTP::GET('m'),
		'year'  => HTTP::GET('y')
	];

	try {
		if (!$posts = $postRepository->search($search, $filter, $pageSize, $offset)) {
			$message = $language->text('search_no_results', htmlspecialchars($search));
		}
	} catch(PDOException $exception) {
		$message = $exception->getMessage();
	}
}

$formData = [
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

$searchData = [
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
	$pageCount = ceil($count / $pageSize);

	$resultTemplate = Template\Factory::build('search/result');
	$resultTemplate->set('FORM', $formData);
	$resultTemplate->set('SEARCH', $searchData);
	$resultTemplate->set('RESULT', [
		'LIST' => $templates ?? []
	]);

	$resultTemplate->set('PAGINATION', [
		'THIS' => $pageNumber,
		'LAST' => $pageCount,
		'HTML' => createPaginationTemplate(
			$pageNumber, $pageCount, Application::getURL('search/')
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
	$searchTemplate->set('FORM', $formData);
	$searchTemplate->set('SEARCH', $searchData);

	$mainTemplate = Template\Factory::build('main');
	$mainTemplate->set('HTML', $searchTemplate);
	$mainTemplate->set('HEAD', [
		'NAME' => $language->text('title_search_request'),
		'PERM' => Application::getURL('search/')
	]);
}

echo $mainTemplate;
