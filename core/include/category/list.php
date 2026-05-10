<?php
#===============================================================================
# Get instances
#===============================================================================
$language = Application::getLanguage();

#===============================================================================
# Get repositories
#===============================================================================
$categoryRepository = Application::getRepository('Category');
$postRepository = Application::getRepository('Post');

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('CATEGORY.LIST_SIZE');

$count = $categoryRepository->getCount();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 or ($currentSite > $lastSite and $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Single redirect
#===============================================================================
if(Application::get('CATEGORY.REDIRECT_SINGLE') === true and $count === 1) {
	$category = $categoryRepository->getLast();
	HTTP::redirect(Application::getEntityURL($category));
}

#===============================================================================
# Get paginated category list
#===============================================================================
$categories = $categoryRepository->getPaginatedTree(
	$site_size,
	($currentSite-1) * $site_size
);

foreach($categories as $category) {
	$templates[] = createEntityItemTemplate($category, ['is_root' => true]);
}

#===============================================================================
# Build document
#===============================================================================
$listTemplate = Template\Factory::build('category/list');
$listTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getCategoryURL())
]);
$listTemplate->set('LIST', [
	'CATEGORIES' => $templates ?? []
]);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('HTML', $listTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => $language->text('title_category_overview', $currentSite)
]);

echo $mainTemplate;
