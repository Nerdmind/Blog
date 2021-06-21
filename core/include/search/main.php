<?php
#===============================================================================
# Get instances
#===============================================================================
$Language = Application::getLanguage();

#===============================================================================
# Get repositories
#===============================================================================
$PostRepository = Application::getRepository('Post');
$UserRepository = Application::getRepository('User');

if($search = HTTP::GET('q')) {
	$filter = [
		'day'   => HTTP::GET('d'),
		'month' => HTTP::GET('m'),
		'year'  => HTTP::GET('y')
	];

	if(!$posts = $PostRepository->search($search, $filter)) {
		$message = $Language->text('search_no_results', escapeHTML($search));
	}
}

$form_data = [
	'SELECT' => [
		'D' => HTTP::GET('d'),
		'M' => HTTP::GET('m'),
		'Y' => HTTP::GET('y'),
	],
	'OPTIONS' => [
		'D' => $PostRepository->getDistinctDays(),
		'M' => $PostRepository->getDistinctMonths(),
		'Y' => $PostRepository->getDistinctYears(),
	]
];

$search_data = [
	'TEXT' => $search,
	'INFO' => isset($message) ? $message : FALSE,
];

#===============================================================================
# Build document
#===============================================================================
if(!empty($posts)) {
	foreach($posts as $Post) {
		$User = $UserRepository->find($Post->get('user'));
		$templates[] = generatePostItemTemplate($Post, $User);
	}

	$ResultTemplate = Template\Factory::build('search/result');
	$ResultTemplate->set('FORM', $form_data);
	$ResultTemplate->set('SEARCH', $search_data);
	$ResultTemplate->set('RESULT', [
		'LIST' => $templates ?? []
	]);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('HTML', $ResultTemplate);
	$MainTemplate->set('HEAD', [
		'NAME' => $Language->text('title_search_results', escapeHTML($search)),
		'PERM' => Application::getURL('search/')
	]);
}

else {
	$SearchTemplate = Template\Factory::build('search/main');
	$SearchTemplate->set('FORM', $form_data);
	$SearchTemplate->set('SEARCH', $search_data);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('HTML', $SearchTemplate);
	$MainTemplate->set('HEAD', [
		'NAME' => $Language->text('title_search_request'),
		'PERM' => Application::getURL('search/')
	]);
}

echo $MainTemplate;
