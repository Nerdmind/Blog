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
$pageSize = Application::get('CATEGORY.LIST_SIZE');

$count = $categoryRepository->getCount();
$pageCount = ceil($count / $pageSize);

$pageNumber = HTTP::GET('site') ?? 1;
$pageNumber = intval($pageNumber);

if($pageNumber < 1 or ($pageNumber > $pageCount and $pageCount > 0)) {
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
	$pageSize,
	($pageNumber-1) * $pageSize
);

foreach($categories as $category) {
	$templates[] = createEntityItemTemplate($category, ['is_root' => true]);
}

#===============================================================================
# Build document
#===============================================================================
$listTemplate = Template\Factory::build('category/list');
$listTemplate->set('PAGINATION', [
	'THIS' => $pageNumber,
	'LAST' => $pageCount,
	'HTML' => createPaginationTemplate(
		$pageNumber, $pageCount, Application::getCategoryURL())
]);
$listTemplate->set('LIST', [
	'CATEGORIES' => $templates ?? []
]);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('HTML', $listTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => $language->text('title_category_overview', $pageNumber)
]);

echo $mainTemplate;
