<?php

$PageRepository = Application::getRepository('Page');
$PostRepository = Application::getRepository('Post');
$UserRepository = Application::getRepository('User');

if($Page = $PageRepository->getLast()) {
	$User = $UserRepository->find($Page->get('user'));
	$PageItemTemplate = generatePageItemTemplate($Page, $User);
}

if($Post = $PostRepository->getLast()) {
	$User = $UserRepository->find($Post->get('user'));
	$PostItemTemplate = generatePostItemTemplate($Post, $User);
}

if($User = $UserRepository->getLast()) {
	$UserItemTemplate = generateUserItemTemplate($User);
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
