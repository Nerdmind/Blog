<?php
#===============================================================================
# Get repositories
#===============================================================================
$postRepository = Application::getRepository('Post');

#===============================================================================
# Get paginated post list
#===============================================================================
$posts = $postRepository->getPaginated(
	Application::get('POST.LIST_SORT'),
	Application::get('POST.LIST_SIZE')
);

foreach($posts as $post) {
	$templates[] = createEntityItemTemplate($post);
}

#===============================================================================
# Pagination
#===============================================================================
$count = $postRepository->getCount();
$lastSite = ceil($count / Application::get('POST.LIST_SIZE'));

#===============================================================================
# Build document
#===============================================================================
$homeTemplate = Template\Factory::build('home');
$homeTemplate->set('PAGINATION', [
	'HTML' => createPaginationTemplate(
		1, $lastSite, Application::getPostURL())
]);
$homeTemplate->set('LIST', [
	'POSTS' => $templates ?? []
]);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('HTML', $homeTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => Application::get('BLOGMETA.HOME'),
	'DESC' => Application::get('BLOGMETA.NAME').' – '
		.Application::get('BLOGMETA.DESC'),
	'PERM' => Application::getURL()
]);

echo $mainTemplate;
