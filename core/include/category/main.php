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
			$redirect_scheduled = true;
		}
	}
}

else {
	if(!$categories = $categoryRepository->findWithParents($param)) {
		if($categories = $categoryRepository->findWithParentsBy('slug', $param)) {
			$redirect_scheduled = true;
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
if(isset($redirect_scheduled)) {
	HTTP::redirect(Application::getEntityURL($category));
}

#===============================================================================
# Generate category template data (including parents)
#===============================================================================
foreach($categories as $_Category) {
	$category_list[] = generateItemTemplateData($_Category);
}

#===============================================================================
# Define data variable for current category
#===============================================================================
$category_data = $category_list[array_key_last($category_list)];

#===============================================================================
# Generate category children list
#===============================================================================
$child_categories = $categoryRepository->getAll(
	['parent' => $category->getID()],
	Application::get('CATEGORY.LIST_SORT')
);

foreach($child_categories as $childCategory) {
	$child_templates[] = createEntityItemTemplate($childCategory);
}

#===============================================================================
# Pagination (for posts in this category)
#===============================================================================
$site_size = Application::get('POST.LIST_SIZE');
$site_sort = Application::get('POST.LIST_SORT');

$count = $categoryRepository->getNumberOfPosts($category);
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 or ($currentSite > $lastSite and $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Get paginated post list for this category
#===============================================================================
$posts = $postRepository->getAll(
	['category' => $category->getID()],
	$site_sort,
	$site_size,
	($currentSite-1) * $site_size
);

foreach($posts as $post) {
	$post_templates[] = createEntityItemTemplate($post);
}

#===============================================================================
# Build document
#===============================================================================
$categoryTemplate = Template\Factory::build('category/main');
$categoryTemplate->set('CATEGORY', $category_data);
$categoryTemplate->set('CATEGORIES', $category_list ?? []);
$categoryTemplate->set('COUNT', [
	'POST' => $categoryRepository->getNumberOfPosts($category),
	'CHILDREN' => $categoryRepository->getNumberOfChildren($category)
]);
$categoryTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getEntityURL($category))
]);
$categoryTemplate->set('LIST', [
	'POSTS' => $post_templates ?? [],
	'CATEGORIES' => $child_templates ?? []
]);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('TYPE', 'CATEGORY');
$mainTemplate->set('CATEGORY', $category_data);
$mainTemplate->set('CATEGORIES', $category_list ?? []);
$mainTemplate->set('HTML', $categoryTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => Application::getLanguage()->text('title_category',
		[$category_data['ATTR']['NAME'], $currentSite]),
	'DESC' => description($category_data['BODY']['HTML'](),
		Application::get('CATEGORY.DESCRIPTION_SIZE')),
	'PERM' => $category_data['URL'],
	'OG_IMAGES' => $category_data['FILE']['LIST']
]);

echo $mainTemplate;
