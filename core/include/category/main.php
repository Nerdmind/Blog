<?php
#===============================================================================
# Get repositories
#===============================================================================
$categoryRepository = Application::getRepository('Category');
$postRepository = Application::getRepository('Post');

#===============================================================================
# Try to find category (with parents) by slug URL or unique ID
#===============================================================================
if(Application::get('CATEGORY.SLUG_URLS')) {
	if(!$categories = $categoryRepository->findWithParentsBy('slug', $param)) {
		if($categories = $categoryRepository->findWithParents($param)) {
			$redirectScheduled = true;
		}
	}
}

else {
	if(!$categories = $categoryRepository->findWithParents($param)) {
		if($categories = $categoryRepository->findWithParentsBy('slug', $param)) {
			$redirectScheduled = true;
		}
	}
}

#===============================================================================
# Throw 404 error if category (with parents) could not be found
#===============================================================================
if(empty($categories)) {
	Application::error404();
}

#===============================================================================
# The last element represents the current category
#===============================================================================
$category = $categories[array_key_last($categories)];

#===============================================================================
# If category with parents was found by alternative, redirect to correct URL
#===============================================================================
if(isset($redirectScheduled)) {
	HTTP::redirect(Application::getEntityURL($category));
}

#===============================================================================
# Generate category template data (including parents)
#===============================================================================
foreach($categories as $_Category) {
	$categoryList[] = generateItemTemplateData($_Category);
}

#===============================================================================
# Define data variable for current category
#===============================================================================
$categoryData = $categoryList[array_key_last($categoryList)];

#===============================================================================
# Generate category children list
#===============================================================================
$childCategories = $categoryRepository->getAll(
	['parent' => $category->getID()],
	Application::get('CATEGORY.LIST_SORT')
);

foreach($childCategories as $childCategory) {
	$childTemplates[] = createEntityItemTemplate($childCategory);
}

#===============================================================================
# Pagination (for posts in this category)
#===============================================================================
$pageSize = Application::get('POST.LIST_SIZE');
$sortOrder = Application::get('POST.LIST_SORT');

$count = $categoryRepository->getNumberOfPosts($category);
$pageCount = ceil($count / $pageSize);

$pageNumber = HTTP::GET('site') ?? 1;
$pageNumber = intval($pageNumber);

if($pageNumber < 1 or ($pageNumber > $pageCount and $pageCount > 0)) {
	Application::error404();
}

#===============================================================================
# Get paginated post list for this category
#===============================================================================
$posts = $postRepository->getAll(
	['category' => $category->getID()],
	$sortOrder,
	$pageSize,
	($pageNumber-1) * $pageSize
);

foreach($posts as $post) {
	$postTemplates[] = createEntityItemTemplate($post);
}

#===============================================================================
# Build document
#===============================================================================
$categoryTemplate = Template\Factory::build('category/main');
$categoryTemplate->set('CATEGORY', $categoryData);
$categoryTemplate->set('CATEGORIES', $categoryList ?? []);
$categoryTemplate->set('COUNT', [
	'POST' => $categoryRepository->getNumberOfPosts($category),
	'CHILDREN' => $categoryRepository->getNumberOfChildren($category)
]);
$categoryTemplate->set('PAGINATION', [
	'THIS' => $pageNumber,
	'LAST' => $pageCount,
	'HTML' => createPaginationTemplate(
		$pageNumber, $pageCount, Application::getEntityURL($category))
]);
$categoryTemplate->set('LIST', [
	'POSTS' => $postTemplates ?? [],
	'CATEGORIES' => $childTemplates ?? []
]);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('TYPE', 'CATEGORY');
$mainTemplate->set('CATEGORY', $categoryData);
$mainTemplate->set('CATEGORIES', $categoryList ?? []);
$mainTemplate->set('HTML', $categoryTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => Application::getLanguage()->text('title_category',
		[$categoryData['ATTR']['NAME'], $pageNumber]),
	'DESC' => description($categoryData['BODY']['HTML'](),
		Application::get('CATEGORY.DESCRIPTION_SIZE')),
	'PERM' => $categoryData['URL'],
	'OG_IMAGES' => $categoryData['FILE']['LIST']
]);

echo $mainTemplate;
