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
$postData = generateItemTemplateData($post);
$userData = generateItemTemplateData($user);

#===============================================================================
# Add template data for previous and next post
#===============================================================================
if($prevPost = $postRepository->findPrev($post)) {
	$postData['PREV'] = generateItemTemplateData($prevPost);
}

if($nextPost = $postRepository->findNext($post)) {
	$postData['NEXT'] = generateItemTemplateData($nextPost);
}

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

#===============================================================================
# Build document
#===============================================================================
$postTemplate = Template\Factory::build('post/main');
$postTemplate->set('POST', $postData);
$postTemplate->set('USER', $userData);
$postTemplate->set('CATEGORY', $categoryData ?? []);
$postTemplate->set('CATEGORIES', $categoryList ?? []);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('TYPE', 'POST');
$mainTemplate->set('POST', $postData);
$mainTemplate->set('USER', $userData);
$mainTemplate->set('HTML', $postTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => $postData['ATTR']['NAME'],
	'DESC' => description($postData['BODY']['HTML'](),
		Application::get('POST.DESCRIPTION_SIZE')),
	'PERM' => $postData['URL'],
	'OG_IMAGES' => $postData['FILE']['LIST']
]);

echo $mainTemplate;
