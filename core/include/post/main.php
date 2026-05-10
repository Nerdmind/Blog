<?php
#===============================================================================
# Get repositories
#===============================================================================
$categoryRepository = Application::getRepository('Category');
$postRepository = Application::getRepository('Post');
$userRepository = Application::getRepository('User');

#===============================================================================
# Try to find post by slug URL or unique ID
#===============================================================================
if(Application::get('POST.SLUG_URLS')) {
	if(!$post = $postRepository->findBy('slug', $param)) {
		if($post = $postRepository->find($param)) {
			HTTP::redirect(Application::getEntityURL($post));
		}
	}
}

else {
	if(!$post = $postRepository->find($param)) {
		if($post = $postRepository->findBy('slug', $param)) {
			HTTP::redirect(Application::getEntityURL($post));
		}
	}
}

#===============================================================================
# Throw 404 error if post could not be found
#===============================================================================
if(!isset($post)) {
	Application::error404();
}

#===============================================================================
# Generate template data
#===============================================================================
$user = $userRepository->find($post->get('user'));
$post_data = generateItemTemplateData($post);
$user_data = generateItemTemplateData($user);

#===============================================================================
# Add template data for previous and next post
#===============================================================================
if($prevPost = $postRepository->findPrev($post)) {
	$post_data['PREV'] = generateItemTemplateData($prevPost);
}

if($nextPost = $postRepository->findNext($post)) {
	$post_data['NEXT'] = generateItemTemplateData($nextPost);
}

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

#===============================================================================
# Build document
#===============================================================================
$postTemplate = Template\Factory::build('post/main');
$postTemplate->set('POST', $post_data);
$postTemplate->set('USER', $user_data);
$postTemplate->set('CATEGORY', $category_data ?? []);
$postTemplate->set('CATEGORIES', $category_list ?? []);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('TYPE', 'POST');
$mainTemplate->set('POST', $post_data);
$mainTemplate->set('USER', $user_data);
$mainTemplate->set('HTML', $postTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => $post_data['ATTR']['NAME'],
	'DESC' => description($post_data['BODY']['HTML'](),
		Application::get('POST.DESCRIPTION_SIZE')),
	'PERM' => $post_data['URL'],
	'OG_IMAGES' => $post_data['FILE']['LIST']
]);

echo $mainTemplate;
