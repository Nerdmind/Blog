<?php
#===============================================================================
# Get repositories
#===============================================================================
$PostRepository = Application::getRepository('Post');

#===============================================================================
# Get paginated post list
#===============================================================================
$posts = $PostRepository->getPaginated(
	Application::get('POST.LIST_SORT'),
	Application::get('POST.LIST_SIZE')
);

foreach($posts as $Post) {
	$templates[] = createEntityItemTemplate($Post);
}

#===============================================================================
# Pagination
#===============================================================================
$count = $PostRepository->getCount();
$lastSite = ceil($count / Application::get('POST.LIST_SIZE'));

#===============================================================================
# Build document
#===============================================================================
$HomeTemplate = Template\Factory::build('home');
$HomeTemplate->set('PAGINATION', [
	'HTML' => createPaginationTemplate(
		1, $lastSite, Application::getPostURL())
]);
$HomeTemplate->set('LIST', [
	'POSTS' => $templates ?? []
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('HTML', $HomeTemplate);
$MainTemplate->set('HEAD', [
	'NAME' => Application::get('BLOGMETA.HOME'),
	'DESC' => Application::get('BLOGMETA.NAME').' – '
		.Application::get('BLOGMETA.DESC'),
	'PERM' => Application::getURL()
]);

echo $MainTemplate;
