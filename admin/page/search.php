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
$PageRepository = Application::getRepository('Page');
$UserRepository = Application::getRepository('User');

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('ADMIN.PAGE.LIST_SIZE');
$site_sort = Application::get('ADMIN.PAGE.LIST_SORT');

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);
$offset = ($currentSite-1) * $site_size;

#===============================================================================
# Check for search request
#===============================================================================
if($search = HTTP::GET('q')) {
	try {
		foreach($PageRepository->search($search, [], $site_size, $offset) as $Page) {
			$User = $UserRepository->find($Page->get('user'));
			$templates[] = generatePageItemTemplate($Page, $User);
		}
	} catch(PDOException $Exception) {
		$messages[] = $Exception->getMessage();
	}
}

#===============================================================================
# Create pagination only if there are results
#===============================================================================
if($count = $PageRepository->getLastSearchOverallCount()) {
	$last = ceil($count / $site_size);

	$pagination_data = [
		'THIS' => $currentSite,
		'LAST' => $last,
		'HTML' => createPaginationTemplate(
			$currentSite, $last, Application::getAdminURL('page/search.php')
		)
	];
}

#===============================================================================
# Build document
#===============================================================================
$SearchTemplate = Template\Factory::build('page/search');
$SearchTemplate->set('QUERY', $search);
$SearchTemplate->set('PAGES', $templates ?? []);
$SearchTemplate->set('FORM', [
	'INFO' => $messages ?? []
]);
$SearchTemplate->set('PAGINATION', $pagination_data ?? []);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_page_search'));
$MainTemplate->set('HTML', $SearchTemplate);

echo $MainTemplate;
