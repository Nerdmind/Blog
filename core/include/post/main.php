<?php
#===============================================================================
# Get repositories
#===============================================================================
$CategoryRepository = Application::getRepository('Category');
$PostRepository = Application::getRepository('Post');
$UserRepository = Application::getRepository('User');

#===============================================================================
# Try to find post by slug URL or unique ID
#===============================================================================
if(Application::get('POST.SLUG_URLS')) {
	if(!$Post = $PostRepository->findBy('slug', $param)) {
		if($Post = $PostRepository->find($param)) {
			HTTP::redirect(Application::getEntityURL($Post));
		}
	}
}

else {
	if(!$Post = $PostRepository->find($param)) {
		if($Post = $PostRepository->findBy('slug', $param)) {
			HTTP::redirect(Application::getEntityURL($Post));
		}
	}
}

#===============================================================================
# Throw 404 error if post could not be found
#===============================================================================
if(!isset($Post)) {
	Application::error404();
}

#===============================================================================
# Generate template data
#===============================================================================
$User = $UserRepository->find($Post->get('user'));
$post_data = generateItemTemplateData($Post);
$user_data = generateItemTemplateData($User);

#===============================================================================
# Add template data for previous and next post
#===============================================================================
if($PrevPost = $PostRepository->findPrev($Post)) {
	$post_data['PREV'] = generateItemTemplateData($PrevPost);
}

if($NextPost = $PostRepository->findNext($Post)) {
	$post_data['NEXT'] = generateItemTemplateData($NextPost);
}

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

#===============================================================================
# Build document
#===============================================================================
$PostTemplate = Template\Factory::build('post/main');
$PostTemplate->set('POST', $post_data);
$PostTemplate->set('USER', $user_data);
$PostTemplate->set('CATEGORY', $category_data ?? []);
$PostTemplate->set('CATEGORIES', $category_list ?? []);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('TYPE', 'POST');
$MainTemplate->set('POST', $post_data);
$MainTemplate->set('USER', $user_data);
$MainTemplate->set('HTML', $PostTemplate);
$MainTemplate->set('HEAD', [
	'NAME' => $post_data['ATTR']['NAME'],
	'DESC' => description($post_data['BODY']['HTML'](),
		Application::get('POST.DESCRIPTION_SIZE')),
	'PERM' => $post_data['URL'],
	'OG_IMAGES' => $post_data['FILE']['LIST']
]);

echo $MainTemplate;
