<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
const ADMINISTRATION = TRUE;
const AUTHENTICATION = TRUE;

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

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);
$offset = ($currentSite-1) * $site_size;

#===============================================================================
# Check for search request
#===============================================================================
if($search = HTTP::GET('q')) {
	try {
		foreach ($PostRepository->search($search, [], $site_size, $offset) as $Post) {
			$User = $UserRepository->find($Post->get('user'));
			$templates[] = generatePostItemTemplate($Post, $User);
		}
	} catch(PDOException $Exception) {
		$messages[] = $Exception->getMessage();
	}
}

#===============================================================================
# Create pagination only if there are results
#===============================================================================
if($count = $PostRepository->getLastSearchOverallCount()) {
	$last = ceil($count / $site_size);

	$pagination_data = [
		'THIS' => $currentSite,
		'LAST' => $last,
		'HTML' => createPaginationTemplate(
			$currentSite, $last, Application::getAdminURL('post/search.php')
		)
	];
}

#===============================================================================
# Build document
#===============================================================================
$SearchTemplate = Template\Factory::build('post/search');
$SearchTemplate->set('QUERY', $search);
$SearchTemplate->set('POSTS', $templates ?? []);
$SearchTemplate->set('FORM', [
	'INFO' => $messages ?? []
]);
$SearchTemplate->set('PAGINATION', $pagination_data ?? []);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_post_search'));
$MainTemplate->set('HTML', $SearchTemplate);

echo $MainTemplate;
