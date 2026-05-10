<?php
#===============================================================================
# Get repositories
#===============================================================================
$userRepository = Application::getRepository('User');

#===============================================================================
# Try to find user by slug URL or unique ID
#===============================================================================
if(Application::get('USER.SLUG_URLS')) {
	if(!$user = $userRepository->findBy('slug', $param)) {
		if($user = $userRepository->find($param)) {
			HTTP::redirect(Application::getEntityURL($user));
		}
	}
}

else {
	if(!$user = $userRepository->find($param)) {
		if($user = $userRepository->findBy('slug', $param)) {
			HTTP::redirect(Application::getEntityURL($user));
		}
	}
}

#===============================================================================
# Throw 404 error if user could not be found
#===============================================================================
if(!isset($user)) {
	Application::error404();
}

#===============================================================================
# Generate template data
#===============================================================================
$userData = generateItemTemplateData($user);

#===============================================================================
# Add template data for previous and next user
#===============================================================================
if($prevUser = $userRepository->findPrev($user)) {
	$userData['PREV'] = generateItemTemplateData($prevUser);
}

if($nextUser = $userRepository->findNext($user)) {
	$userData['NEXT'] = generateItemTemplateData($nextUser);
}

#===============================================================================
# Build document
#===============================================================================
$userTemplate = Template\Factory::build('user/main');
$userTemplate->set('USER', $userData);
$userTemplate->set('COUNT', [
	'PAGE' => $userRepository->getNumberOfPages($user),
	'POST' => $userRepository->getNumberOfPosts($user)
]);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('TYPE', 'USER');
$mainTemplate->set('USER', $userData);
$mainTemplate->set('HTML', $userTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => $userData['ATTR']['FULLNAME'],
	'DESC' => description($userData['BODY']['HTML'](),
		Application::get('USER.DESCRIPTION_SIZE')),
	'PERM' => $userData['URL'],
	'OG_IMAGES' => $userData['FILE']['LIST']
]);

echo $mainTemplate;
