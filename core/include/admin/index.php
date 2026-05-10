<?php

$pageRepository = Application::getRepository('Page');
$postRepository = Application::getRepository('Post');
$userRepository = Application::getRepository('User');

if($page = $pageRepository->getLast()) {
	$pageItemTemplate = createEntityItemTemplate($page);
}

if($post = $postRepository->getLast()) {
	$postItemTemplate = createEntityItemTemplate($post);
}

if($user = $userRepository->getLast()) {
	$userItemTemplate = createEntityItemTemplate($user);
}

$adminIndexTemplate = Template\Factory::build('home');
$adminIndexTemplate->set('LAST', [
	'PAGE' => $pageItemTemplate ?? false,
	'POST' => $postItemTemplate ?? false,
	'USER' => $userItemTemplate ?? false
]);

$adminIndexTemplate->set('COUNT', [
	'PAGE' => $pageRepository->getCount(),
	'POST' => $postRepository->getCount(),
	'USER' => $userRepository->getCount()
]);

$template = Template\Factory::build('main');
$template->set('NAME', 'Dashboard');
$template->set('HTML', $adminIndexTemplate);

echo $template;
