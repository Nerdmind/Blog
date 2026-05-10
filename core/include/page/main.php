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
$pageData = generateItemTemplateData($page);
$userData = generateItemTemplateData($user);

#===============================================================================
# Add template data for previous and next page
#===============================================================================
if($prevPage = $pageRepository->findPrev($page)) {
	$pageData['PREV'] = generateItemTemplateData($prevPage);
}

if($nextPage = $pageRepository->findNext($page)) {
	$pageData['NEXT'] = generateItemTemplateData($nextPage);
}

#===============================================================================
# Build document
#===============================================================================
$pageTemplate = Template\Factory::build('page/main');
$pageTemplate->set('PAGE', $pageData);
$pageTemplate->set('USER', $userData);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('TYPE', 'PAGE');
$mainTemplate->set('PAGE', $pageData);
$mainTemplate->set('USER', $userData);
$mainTemplate->set('HTML', $pageTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => $pageData['ATTR']['NAME'],
	'DESC' => description($pageData['BODY']['HTML'](),
		Application::get('PAGE.DESCRIPTION_SIZE')),
	'PERM' => $pageData['URL'],
	'OG_IMAGES' => $pageData['FILE']['LIST']
]);

echo $mainTemplate;
