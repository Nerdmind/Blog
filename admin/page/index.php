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

$count = $PageRepository->getCount();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

#===============================================================================
# Redirect to page create form if no page exists
#===============================================================================
if(!$count) {
	HTTP::redirect(Application::getAdminURL('page/insert.php'));
}

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Get paginated page list
#===============================================================================
$pages = $PageRepository->getPaginated(
	$site_sort,
	$site_size,
	($currentSite-1) * $site_size
);

foreach($pages as $Page) {
	$User = $UserRepository->find($Page->get('user'));
	$templates[] = generatePageItemTemplate($Page, $User);
}

#===============================================================================
# Build document
#===============================================================================
$ListTemplate = Template\Factory::build('page/index');
$ListTemplate->set('LIST', [
	'PAGES' => $templates ?? []
]);

$ListTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getAdminURL('page/')
	)
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_page_overview', $currentSite));
$MainTemplate->set('HTML', $ListTemplate);
echo $MainTemplate;
