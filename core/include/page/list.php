<?php
#===============================================================================
# Get instances
#===============================================================================
$language = Application::getLanguage();

#===============================================================================
# Get repositories
#===============================================================================
$pageRepository = Application::getRepository('Page');

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('PAGE.LIST_SIZE');
$site_sort = Application::get('PAGE.LIST_SORT');

$count = $pageRepository->getCount();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 or ($currentSite > $lastSite and $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Single redirect
#===============================================================================
if(Application::get('PAGE.REDIRECT_SINGLE') === true and $count === 1) {
	$page = $pageRepository->getLast();
	HTTP::redirect(Application::getEntityURL($page));
}

#===============================================================================
# Get paginated page list
#===============================================================================
$pages = $pageRepository->getPaginated(
	$site_sort,
	$site_size,
	($currentSite-1) * $site_size
);

foreach($pages as $page) {
	$templates[] = createEntityItemTemplate($page);
}

#===============================================================================
# Build document
#===============================================================================
$listTemplate = Template\Factory::build('page/list');
$listTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getPageURL()
	)
]);
$listTemplate->set('LIST', [
	'PAGES' => $templates ?? []
]);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('HTML', $listTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => $language->text('title_page_overview', $currentSite)
]);

echo $mainTemplate;
