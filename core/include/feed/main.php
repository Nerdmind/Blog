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

	$postData = generateItemTemplateData($post);
	$postData['GUID'] = sha1($post->getID().$post->get('time_insert'));

	#===============================================================================
	# Generate category template data (including parents)
	#===============================================================================
	foreach($categoryRepository->findWithParents($post->get('category')) as $category) {
		$categoryList[] = generateItemTemplateData($category);
	}

	#===============================================================================
	# Define data variable for current category
	#===============================================================================
	if(isset($categoryList)) {
		$categoryData = $categoryList[array_key_last($categoryList)];
	}

	$itemTemplate->set('POST', $postData);
	$itemTemplate->set('USER', generateItemTemplateData($user));
	$itemTemplate->set('CATEGORY', $categoryData ?? []);
	$itemTemplate->set('CATEGORIES', $categoryList ?? []);

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
