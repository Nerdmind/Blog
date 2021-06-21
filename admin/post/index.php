<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
define('ADMINISTRATION', TRUE);
define('AUTHENTICATION', TRUE);

#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require '../../core/application.php';

#===============================================================================
# Get repositories
#===============================================================================
$PostRepository = Application::getRepository('Post');
$UserRepository = Application::getRepository('User');

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('ADMIN.POST.LIST_SIZE');
$site_sort = Application::get('ADMIN.POST.LIST_SORT');

$count = $PostRepository->getCount();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
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
$PaginationTemplate = Template\Factory::build('pagination');
$PaginationTemplate->set('THIS', $currentSite);
$PaginationTemplate->set('LAST', $lastSite);
$PaginationTemplate->set('HREF', Application::getAdminURL('post/?site=%d'));

$ListTemplate = Template\Factory::build('post/index');
$ListTemplate->set('LIST', [
	'POSTS' => $templates ?? []
]);

$ListTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => $PaginationTemplate
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_post_overview', $currentSite));
$MainTemplate->set('HTML', $ListTemplate);
echo $MainTemplate;
