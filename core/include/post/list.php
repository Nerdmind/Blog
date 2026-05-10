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
$siteSize = Application::get('POST.LIST_SIZE');
$siteSort = Application::get('POST.LIST_SORT');

$count = $postRepository->getCount();
$lastSite = ceil($count / $siteSize);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 or ($currentSite > $lastSite and $lastSite > 0)) {
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
	$siteSort,
	$siteSize,
	($currentSite-1) * $siteSize
);

foreach($posts as $post) {
	$templates[] = createEntityItemTemplate($post);
}

#===============================================================================
# Build document
#===============================================================================
$listTemplate = Template\Factory::build('post/list');
$listTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getPostURL())
]);
$listTemplate->set('LIST', [
	'POSTS' => $templates ?? []
]);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('HTML', $listTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => $language->text('title_post_overview', $currentSite)
]);

echo $mainTemplate;
