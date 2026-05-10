<?php
#===============================================================================
# Get repositories
#===============================================================================
$pageRepository = Application::getRepository('Page');
$userRepository = Application::getRepository('User');

#===============================================================================
# Try to find page by slug URL or unique ID
#===============================================================================
if(Application::get('PAGE.SLUG_URLS')) {
	if(!$page = $pageRepository->findBy('slug', $param)) {
		if($page = $pageRepository->find($param)) {
			HTTP::redirect(Application::getEntityURL($page));
		}
	}
}

else {
	if(!$page = $pageRepository->find($param)) {
		if($page = $pageRepository->findBy('slug', $param)) {
			HTTP::redirect(Application::getEntityURL($page));
		}
	}
}

#===============================================================================
# Throw 404 error if page could not be found
#===============================================================================
if(!isset($page)) {
	Application::error404();
}

#===============================================================================
# Generate template data
#===============================================================================
$user = $userRepository->find($page->get('user'));
$page_data = generateItemTemplateData($page);
$user_data = generateItemTemplateData($user);

#===============================================================================
# Add template data for previous and next page
#===============================================================================
if($prevPage = $pageRepository->findPrev($page)) {
	$page_data['PREV'] = generateItemTemplateData($prevPage);
}

if($nextPage = $pageRepository->findNext($page)) {
	$page_data['NEXT'] = generateItemTemplateData($nextPage);
}

#===============================================================================
# Build document
#===============================================================================
$pageTemplate = Template\Factory::build('page/main');
$pageTemplate->set('PAGE', $page_data);
$pageTemplate->set('USER', $user_data);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('TYPE', 'PAGE');
$mainTemplate->set('PAGE', $page_data);
$mainTemplate->set('USER', $user_data);
$mainTemplate->set('HTML', $pageTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => $page_data['ATTR']['NAME'],
	'DESC' => description($page_data['BODY']['HTML'](),
		Application::get('PAGE.DESCRIPTION_SIZE')),
	'PERM' => $page_data['URL'],
	'OG_IMAGES' => $page_data['FILE']['LIST']
]);

echo $mainTemplate;
