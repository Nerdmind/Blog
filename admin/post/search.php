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
$CategoryRepository = Application::getRepository('Category');
$PostRepository = Application::getRepository('Post');
$UserRepository = Application::getRepository('User');

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('ADMIN.POST.LIST_SIZE');
$site_sort = Application::get('ADMIN.POST.LIST_SORT');

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);
$offset = ($currentSite-1) * $site_size;

#===============================================================================
# Check for search request
#===============================================================================
if($search = HTTP::GET('q')) {
	try {
		$filter = [
			'user' => HTTP::GET('user'),
			'category' => HTTP::GET('category')
		];

		if(!$posts = $PostRepository->search($search, $filter, $site_size, $offset)) {
			$messages[] = Application::getLanguage()->text(
				'search_no_results', htmlspecialchars($search));
		}

		foreach($posts as $Post) {
			$User = $UserRepository->find($Post->get('user'));
			$templates[] = generatePostItemTemplate($Post, $User);
		}
	} catch(PDOException $Exception) {
		$messages[] = $Exception->getMessage();
	}
}

#===============================================================================
# Create pagination only if there are results
#===============================================================================
if($count = $PostRepository->getLastSearchOverallCount()) {
	$last = ceil($count / $site_size);

	$pagination_data = [
		'THIS' => $currentSite,
		'LAST' => $last,
		'HTML' => createPaginationTemplate(
			$currentSite, $last, Application::getAdminURL('post/search.php')
		)
	];
}

#===============================================================================
# Generate user list
#===============================================================================
foreach($UserRepository->getAll([], 'fullname ASC') as $User) {
	$userList[] = [
		'ID' => $User->getID(),
		'FULLNAME' => $User->get('fullname'),
		'USERNAME' => $User->get('username'),
	];
}

#===============================================================================
# Generate category list
#===============================================================================
foreach($CategoryRepository->getAll([], 'name ASC') as $Category) {
	$categoryList[] = [
		'ID' => $Category->getID(),
		'NAME' => $Category->get('name'),
		'PARENT' => $Category->get('parent'),
	];
}

#===============================================================================
# Build document
#===============================================================================
$SearchTemplate = Template\Factory::build('post/search');
$SearchTemplate->set('QUERY', $search);
$SearchTemplate->set('POSTS', $templates ?? []);
$SearchTemplate->set('FORM', [
	'INFO' => $messages ?? [],
	'DATA' => [
		'USER' => HTTP::GET('user'),
		'CATEGORY' => HTTP::GET('category')
	],
	'USER_LIST' => $userList ??  [],
	'CATEGORY_LIST' => $categoryList ?? [],
	'CATEGORY_TREE' => generateCategoryDataTree($categoryList ?? [])
]);
$SearchTemplate->set('PAGINATION', $pagination_data ?? []);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_post_search'));
$MainTemplate->set('HTML', $SearchTemplate);

echo $MainTemplate;
