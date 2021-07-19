<?php
#===============================================================================
# Get repositories
#===============================================================================
$UserRepository = Application::getRepository('User');

#===============================================================================
# Try to find user by slug URL or unique ID
#===============================================================================
if(Application::get('USER.SLUG_URLS')) {
	if(!$User = $UserRepository->findBy('slug', $param)) {
		if($User = $UserRepository->find($param)) {
			HTTP::redirect(Application::getEntityURL($User));
		}
	}
}

else {
	if(!$User = $UserRepository->find($param)) {
		if($User = $UserRepository->findBy('slug', $param)) {
			HTTP::redirect(Application::getEntityURL($User));
		}
	}
}

#===============================================================================
# Throw 404 error if user could not be found
#===============================================================================
if(!isset($User)) {
	Application::error404();
}

#===============================================================================
# Generate template data
#===============================================================================
$user_data = generateItemTemplateData($User);

#===============================================================================
# Add template data for previous and next user
#===============================================================================
if($PrevUser = $UserRepository->findPrev($User)) {
	$user_data['PREV'] = generateItemTemplateData($PrevUser);
}

if($NextUser = $UserRepository->findNext($User)) {
	$user_data['NEXT'] = generateItemTemplateData($NextUser);
}

#===============================================================================
# Build document
#===============================================================================
$UserTemplate = Template\Factory::build('user/main');
$UserTemplate->set('USER', $user_data);
$UserTemplate->set('COUNT', [
	'PAGE' => $UserRepository->getNumberOfPages($User),
	'POST' => $UserRepository->getNumberOfPosts($User)
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('TYPE', 'USER');
$MainTemplate->set('USER', $user_data);
$MainTemplate->set('HTML', $UserTemplate);
$MainTemplate->set('HEAD', [
	'NAME' => $user_data['ATTR']['FULLNAME'],
	'DESC' => description($user_data['BODY']['HTML'](),
		Application::get('USER.DESCRIPTION_SIZE')),
	'PERM' => $user_data['URL'],
	'OG_IMAGES' => $user_data['FILE']['LIST']
]);

echo $MainTemplate;
