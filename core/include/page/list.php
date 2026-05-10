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
$pageSize = Application::get('PAGE.LIST_SIZE');
$sortOrder = Application::get('PAGE.LIST_SORT');

$count = $pageRepository->getCount();
$pageCount = ceil($count / $pageSize);

$pageNumber = HTTP::GET('site') ?? 1;
$pageNumber = intval($pageNumber);

if($pageNumber < 1 or ($pageNumber > $pageCount and $pageCount > 0)) {
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
	$sortOrder,
	$pageSize,
	($pageNumber-1) * $pageSize
);

foreach($pages as $page) {
	$templates[] = createEntityItemTemplate($page);
}

#===============================================================================
# Build document
#===============================================================================
$listTemplate = Template\Factory::build('page/list');
$listTemplate->set('PAGINATION', [
	'THIS' => $pageNumber,
	'LAST' => $pageCount,
	'HTML' => createPaginationTemplate(
		$pageNumber, $pageCount, Application::getPageURL()
	)
]);
$listTemplate->set('LIST', [
	'PAGES' => $templates ?? []
]);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('HTML', $listTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => $language->text('title_page_overview', $pageNumber)
]);

echo $mainTemplate;
