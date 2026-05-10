<?php
#===============================================================================
# HEADER: Content-Type for XML document
#===============================================================================
HTTP::responseHeader(HTTP::HEADER_CONTENT_TYPE, HTTP::CONTENT_TYPE_XML);

#===============================================================================
# Get repositories
#===============================================================================
$categoryRepository = Application::getRepository('Category');
$postRepository = Application::getRepository('Post');
$userRepository = Application::getRepository('User');

#===============================================================================
# Get list of posts to show in the feed
#===============================================================================
$posts = $postRepository->getPaginated(
	Application::get('POST.FEED_SORT'),
	Application::get('POST.FEED_SIZE')
);

#===============================================================================
# Build item templates
#===============================================================================
foreach($posts as $post) {
	$user = $userRepository->find($post->get('user'));

	try {
		$itemTemplate = Template\Factory::build('feed/item');
	} catch(Template\Exception $exception) {
		# Backward compatibility if feed/item.php does not exist.
		$itemTemplate = Template\Factory::build('feed/item_post');
	}

	$post_data = generateItemTemplateData($post);
	$post_data['GUID'] = sha1($post->getID().$post->get('time_insert'));

	#===============================================================================
	# Generate category template data (including parents)
	#===============================================================================
	foreach($categoryRepository->findWithParents($post->get('category')) as $category) {
		$category_list[] = generateItemTemplateData($category);
	}

	#===============================================================================
	# Define data variable for current category
	#===============================================================================
	if(isset($category_list)) {
		$category_data = $category_list[array_key_last($category_list)];
	}

	$itemTemplate->set('POST', $post_data);
	$itemTemplate->set('USER', generateItemTemplateData($user));
	$itemTemplate->set('CATEGORY', $category_data ?? []);
	$itemTemplate->set('CATEGORIES', $category_list ?? []);

	$templates[] = $itemTemplate;
}

#===============================================================================
# Build document
#===============================================================================
$feedTemplate = Template\Factory::build('feed/main');
$feedTemplate->set('FEED', [
	'TYPE' => 'post',
	'LIST' => [
		'POSTS' => $templates ?? [],
		'PAGES' => [],
	]
]);

echo $feedTemplate;
