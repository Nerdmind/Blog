<?php
#===============================================================================
# Get repositories
#===============================================================================
$PageRepository = Application::getRepository('Page');
$UserRepository = Application::getRepository('User');

#===============================================================================
# Try to find page by slug URL or unique ID
#===============================================================================
if(Application::get('PAGE.SLUG_URLS')) {
	if(!$Page = $PageRepository->findBy('slug', $param)) {
		if($Page = $PageRepository->find($param)) {
			HTTP::redirect(Application::getEntityURL($Page));
		}
	}
}

else {
	if(!$Page = $PageRepository->find($param)) {
		if($Page = $PageRepository->findBy('slug', $param)) {
			HTTP::redirect(Application::getEntityURL($Page));
		}
	}
}

#===============================================================================
# Throw 404 error if page could not be found
#===============================================================================
if(!isset($Page)) {
	Application::error404();
}

#===============================================================================
# Generate template data
#===============================================================================
$User = $UserRepository->find($Page->get('user'));
$page_data = generateItemTemplateData($Page);
$user_data = generateItemTemplateData($User);

#===============================================================================
# Add template data for previous and next page
#===============================================================================
if($PrevPage = $PageRepository->findPrev($Page)) {
	$page_data['PREV'] = generateItemTemplateData($PrevPage);
}

if($NextPage = $PageRepository->findNext($Page)) {
	$page_data['NEXT'] = generateItemTemplateData($NextPage);
}

#===============================================================================
# Build document
#===============================================================================
$PageTemplate = Template\Factory::build('page/main');
$PageTemplate->set('PAGE', $page_data);
$PageTemplate->set('USER', $user_data);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('TYPE', 'PAGE');
$MainTemplate->set('PAGE', $page_data);
$MainTemplate->set('USER', $user_data);
$MainTemplate->set('HTML', $PageTemplate);
$MainTemplate->set('HEAD', [
	'NAME' => $page_data['ATTR']['NAME'],
	'DESC' => description($page_data['BODY']['HTML'](),
		Application::get('PAGE.DESCRIPTION_SIZE')),
	'PERM' => $page_data['URL'],
	'OG_IMAGES' => $page_data['FILE']['LIST']
]);

echo $MainTemplate;
