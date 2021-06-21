<?php
#===============================================================================
# Get repositories
#===============================================================================
$UserRepository = Application::getRepository('User');

#===============================================================================
# HEADER: Content-Type for XML document
#===============================================================================
HTTP::responseHeader(HTTP::HEADER_CONTENT_TYPE, HTTP::CONTENT_TYPE_XML);

#===============================================================================
# Post feed
#===============================================================================
if(!isset($param) OR $param !== 'page') {
	$PostRepository = Application::getRepository('Post');

	$posts = $PostRepository->getPaginated(
		Application::get('POST.FEED_SORT'),
		Application::get('POST.FEED_SIZE')
	);

	foreach($posts as $Post) {
		$User = $UserRepository->find($Post->get('user'));

		$ItemTemplate = Template\Factory::build('feed/item_post');
		$ItemTemplate->set('POST', generateItemTemplateData($Post));
		$ItemTemplate->set('USER', generateItemTemplateData($User));

		$post_templates[] = $ItemTemplate;
	}
}

#===============================================================================
# Page feed
#===============================================================================
if(!isset($param) OR $param !== 'post') {
	$PageRepository = Application::getRepository('Page');

	$pages = $PageRepository->getPaginated(
		Application::get('PAGE.FEED_SORT'),
		Application::get('PAGE.FEED_SIZE')
	);

	foreach($pages as $Page) {
		$User = $UserRepository->find($Page->get('user'));

		$ItemTemplate = Template\Factory::build('feed/item_page');
		$ItemTemplate->set('PAGE', generateItemTemplateData($Page));
		$ItemTemplate->set('USER', generateItemTemplateData($User));

		$page_templates[] = $ItemTemplate;
	}
}

#===============================================================================
# Build document
#===============================================================================
$FeedTemplate = Template\Factory::build('feed/main');
$FeedTemplate->set('FEED', [
	'TYPE' => $param ?? NULL,
	'LIST' => [
		'POSTS' => $post_templates ?? [],
		'PAGES' => $page_templates ?? [],
	]
]);

echo $FeedTemplate;
