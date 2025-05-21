<?php

$PageRepository = Application::getRepository('Page');
$PostRepository = Application::getRepository('Post');
$UserRepository = Application::getRepository('User');

if($Page = $PageRepository->getLast()) {
	$PageItemTemplate = createEntityItemTemplate($Page);
}

if($Post = $PostRepository->getLast()) {
	$PostItemTemplate = createEntityItemTemplate($Post);
}

if($User = $UserRepository->getLast()) {
	$UserItemTemplate = createEntityItemTemplate($User);
}

$AdminIndexTemplate = Template\Factory::build('home');
$AdminIndexTemplate->set('LAST', [
	'PAGE' => $PageItemTemplate ?? false,
	'POST' => $PostItemTemplate ?? false,
	'USER' => $UserItemTemplate ?? false
]);

$AdminIndexTemplate->set('COUNT', [
	'PAGE' => $PageRepository->getCount(),
	'POST' => $PostRepository->getCount(),
	'USER' => $UserRepository->getCount()
]);

$Template = Template\Factory::build('main');
$Template->set('NAME', 'Dashboard');
$Template->set('HTML', $AdminIndexTemplate);

echo $Template;
