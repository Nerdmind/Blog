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

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('POST.LIST_SIZE');
$site_sort = Application::get('POST.LIST_SORT');

$count = $PostRepository->getCount();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Single redirect
#===============================================================================
if(Application::get('POST.REDIRECT_SINGLE') === TRUE AND $count === 1) {
	$Post = $PostRepository->getLast();
	HTTP::redirect(Application::getEntityURL($Post));
}

#===============================================================================
# Get paginated post list
#===============================================================================
$posts = $PostRepository->getPaginated(
	$site_sort,
	$site_size,
	($currentSite-1) * $site_size
);

foreach($posts as $Post) {
	$User = $UserRepository->find($Post->get('user'));
	$templates[] = generatePostItemTemplate($Post, $User);
}

#===============================================================================
# Build document
#===============================================================================
$ListTemplate = Template\Factory::build('post/list');
$ListTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getPostURL())
]);
$ListTemplate->set('LIST', [
	'POSTS' => $templates ?? []
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('HTML', $ListTemplate);
$MainTemplate->set('HEAD', [
	'NAME' => $Language->text('title_post_overview', $currentSite)
]);

echo $MainTemplate;
