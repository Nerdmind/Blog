<?php
#===============================================================================
# HEADER: Content-Type for XML document
#===============================================================================
HTTP::responseHeader(HTTP::HEADER_CONTENT_TYPE, HTTP::CONTENT_TYPE_XML);

#===============================================================================
# Get repositories
#===============================================================================
$CategoryRepository = Application::getRepository('Category');
$PostRepository = Application::getRepository('Post');
$UserRepository = Application::getRepository('User');

#===============================================================================
# Get list of posts to show in the feed
#===============================================================================
$posts = $PostRepository->getPaginated(
	Application::get('POST.FEED_SORT'),
	Application::get('POST.FEED_SIZE')
);

#===============================================================================
# Build item templates
#===============================================================================
foreach($posts as $Post) {
	$User = $UserRepository->find($Post->get('user'));

	try {
		$ItemTemplate = Template\Factory::build('feed/item');
	} catch(Template\Exception $Exception) {
		# Backward compatibility if feed/item.php does not exist.
		$ItemTemplate = Template\Factory::build('feed/item_post');
	}

	$post_data = generateItemTemplateData($Post);
	$post_data['GUID'] = sha1($Post->getID().$Post->get('time_insert'));

	#===============================================================================
	# Generate category template data (including parents)
	#===============================================================================
	foreach($CategoryRepository->findWithParents($Post->get('category')) as $Category) {
		$category_list[] = generateItemTemplateData($Category);
	}

	#===============================================================================
	# Define data variable for current category
	#===============================================================================
	if(isset($category_list)) {
		$category_data = $category_list[array_key_last($category_list)];
	}

	$ItemTemplate->set('POST', $post_data);
	$ItemTemplate->set('USER', generateItemTemplateData($User));
	$ItemTemplate->set('CATEGORY', $category_data ?? []);
	$ItemTemplate->set('CATEGORIES', $category_list ?? []);

	$templates[] = $ItemTemplate;
}

#===============================================================================
# Build document
#===============================================================================
$FeedTemplate = Template\Factory::build('feed/main');
$FeedTemplate->set('FEED', [
	'TYPE' => 'post',
	'LIST' => [
		'POSTS' => $templates ?? [],
		'PAGES' => [],
	]
]);

echo $FeedTemplate;
