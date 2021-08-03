<?php
#===============================================================================
# Get repositories
#===============================================================================
$CategoryRepository = Application::getRepository('Category');
$PostRepository = Application::getRepository('Post');
$UserRepository = Application::getRepository('User');

#===============================================================================
# Try to find category (with parents) by slug URL or unique ID
#===============================================================================
if(Application::get('CATEGORY.SLUG_URLS')) {
	if(!$categories = $CategoryRepository->findWithParentsBy('slug', $param)) {
		if($categories = $CategoryRepository->findWithParents($param)) {
			$redirect_scheduled = TRUE;
		}
	}
}

else {
	if(!$categories = $CategoryRepository->findWithParents($param)) {
		if($categories = $CategoryRepository->findWithParentsBy('slug', $param)) {
			$redirect_scheduled = TRUE;
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
$Category = $categories[array_key_last($categories)];

#===============================================================================
# If category with parents was found by alternative, redirect to correct URL
#===============================================================================
if(isset($redirect_scheduled)) {
	HTTP::redirect(Application::getEntityURL($Category));
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
$child_categories = $CategoryRepository->getAll(
	['parent' => $Category->getID()],
	Application::get('CATEGORY.LIST_SORT')
);

foreach($child_categories as $ChildCategory) {
	$child_templates[] = generateCategoryItemTemplate($ChildCategory);
}

#===============================================================================
# Pagination (for posts in this category)
#===============================================================================
$site_size = Application::get('POST.LIST_SIZE');
$site_sort = Application::get('POST.LIST_SORT');

$count = $CategoryRepository->getNumberOfPosts($Category);
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Get paginated post list for this category
#===============================================================================
$posts = $PostRepository->getAll(
	['category' => $Category->getID()],
	$site_sort,
	$site_size,
	($currentSite-1) * $site_size
);

foreach($posts as $Post) {
	$User = $UserRepository->find($Post->get('user'));
	$post_templates[] = generatePostItemTemplate($Post, $User);
}

#===============================================================================
# Build document
#===============================================================================
$CategoryTemplate = Template\Factory::build('category/main');
$CategoryTemplate->set('CATEGORY', $category_data);
$CategoryTemplate->set('CATEGORIES', $category_list ?? []);
$CategoryTemplate->set('COUNT', [
	'POST' => $CategoryRepository->getNumberOfPosts($Category),
	'CHILDREN' => $CategoryRepository->getNumberOfChildren($Category)
]);
$CategoryTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getEntityURL($Category))
]);
$CategoryTemplate->set('LIST', [
	'POSTS' => $post_templates ?? [],
	'CATEGORIES' => $child_templates ?? []
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('TYPE', 'CATEGORY');
$MainTemplate->set('CATEGORY', $category_data);
$MainTemplate->set('CATEGORIES', $category_list ?? []);
$MainTemplate->set('HTML', $CategoryTemplate);
$MainTemplate->set('HEAD', [
	'NAME' => Application::getLanguage()->text('title_category',
		[$category_data['ATTR']['NAME'], $currentSite]),
	'DESC' => description($category_data['BODY']['HTML'](),
		Application::get('CATEGORY.DESCRIPTION_SIZE')),
	'PERM' => $category_data['URL'],
	'OG_IMAGES' => $category_data['FILE']['LIST']
]);

echo $MainTemplate;
