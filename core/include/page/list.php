<?php
#===============================================================================
# Get instances
#===============================================================================
$Language = Application::getLanguage();

#===============================================================================
# Get repositories
#===============================================================================
$PageRepository = Application::getRepository('Page');
$UserRepository = Application::getRepository('User');

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('PAGE.LIST_SIZE');
$site_sort = Application::get('PAGE.LIST_SORT');

$count = $PageRepository->getCount();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Single redirect
#===============================================================================
if(Application::get('PAGE.REDIRECT_SINGLE') === TRUE AND $count === 1) {
	$Page = $PageRepository->getLast();
	HTTP::redirect(Application::getEntityURL($Page));
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
$ListTemplate = Template\Factory::build('page/list');
$ListTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getPageURL()
	)
]);
$ListTemplate->set('LIST', [
	'PAGES' => $templates ?? []
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('HTML', $ListTemplate);
$MainTemplate->set('HEAD', [
	'NAME' => $Language->text('title_page_overview', $currentSite)
]);

echo $MainTemplate;
