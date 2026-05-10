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
$user_data = generateItemTemplateData($user);

#===============================================================================
# Add template data for previous and next user
#===============================================================================
if($prevUser = $userRepository->findPrev($user)) {
	$user_data['PREV'] = generateItemTemplateData($prevUser);
}

if($nextUser = $userRepository->findNext($user)) {
	$user_data['NEXT'] = generateItemTemplateData($nextUser);
}

#===============================================================================
# Build document
#===============================================================================
$userTemplate = Template\Factory::build('user/main');
$userTemplate->set('USER', $user_data);
$userTemplate->set('COUNT', [
	'PAGE' => $userRepository->getNumberOfPages($user),
	'POST' => $userRepository->getNumberOfPosts($user)
]);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('TYPE', 'USER');
$mainTemplate->set('USER', $user_data);
$mainTemplate->set('HTML', $userTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => $user_data['ATTR']['FULLNAME'],
	'DESC' => description($user_data['BODY']['HTML'](),
		Application::get('USER.DESCRIPTION_SIZE')),
	'PERM' => $user_data['URL'],
	'OG_IMAGES' => $user_data['FILE']['LIST']
]);

echo $mainTemplate;
