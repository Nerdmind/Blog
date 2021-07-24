<?php
#===============================================================================
# Get instances
#===============================================================================
$Language = Application::getLanguage();

#===============================================================================
# Get repositories
#===============================================================================
$CategoryRepository = Application::getRepository('Category');
$PostRepository = Application::getRepository('Post');

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('CATEGORY.LIST_SIZE');

$count = $CategoryRepository->getCount();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Single redirect
#===============================================================================
if(Application::get('CATEGORY.REDIRECT_SINGLE') === TRUE AND $count === 1) {
	$Category = $CategoryRepository->getLast();
	HTTP::redirect(Application::getEntityURL($Category));
}

#===============================================================================
# Get paginated category list
#===============================================================================
$categories = $CategoryRepository->getPaginatedTree(
	$site_size,
	($currentSite-1) * $site_size
);

foreach($categories as $Category) {
	$templates[] = generateCategoryItemTemplate($Category, TRUE);
}

#===============================================================================
# Build document
#===============================================================================
$ListTemplate = Template\Factory::build('category/list');
$ListTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getCategoryURL())
]);
$ListTemplate->set('LIST', [
	'CATEGORIES' => $templates ?? []
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('HTML', $ListTemplate);
$MainTemplate->set('HEAD', [
	'NAME' => $Language->text('title_category_overview', $currentSite)
]);

echo $MainTemplate;
