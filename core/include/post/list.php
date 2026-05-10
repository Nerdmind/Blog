<?php
#===============================================================================
# Get instances
#===============================================================================
$language = Application::getLanguage();

#===============================================================================
# Get repositories
#===============================================================================
$postRepository = Application::getRepository('Post');

#===============================================================================
# Pagination
#===============================================================================
$pageSize = Application::get('POST.LIST_SIZE');
$sortOrder = Application::get('POST.LIST_SORT');

$count = $postRepository->getCount();
$pageCount = ceil($count / $pageSize);

$pageNumber = HTTP::GET('site') ?? 1;
$pageNumber = intval($pageNumber);

if($pageNumber < 1 or ($pageNumber > $pageCount and $pageCount > 0)) {
	Application::error404();
}

#===============================================================================
# Single redirect
#===============================================================================
if(Application::get('POST.REDIRECT_SINGLE') === true and $count === 1) {
	$post = $postRepository->getLast();
	HTTP::redirect(Application::getEntityURL($post));
}

#===============================================================================
# Get paginated post list
#===============================================================================
$posts = $postRepository->getPaginated(
	$sortOrder,
	$pageSize,
	($pageNumber-1) * $pageSize
);

foreach($posts as $post) {
	$templates[] = createEntityItemTemplate($post);
}

#===============================================================================
# Build document
#===============================================================================
$listTemplate = Template\Factory::build('post/list');
$listTemplate->set('PAGINATION', [
	'THIS' => $pageNumber,
	'LAST' => $pageCount,
	'HTML' => createPaginationTemplate(
		$pageNumber, $pageCount, Application::getPostURL())
]);
$listTemplate->set('LIST', [
	'POSTS' => $templates ?? []
]);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('HTML', $listTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => $language->text('title_post_overview', $pageNumber)
]);

echo $mainTemplate;
