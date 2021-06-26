<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
const ADMINISTRATION = TRUE;
const AUTHENTICATION = TRUE;

#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require '../core/application.php';

#===============================================================================
# Get repositories
#===============================================================================
$PageRepository = Application::getRepository('Page');
$PostRepository = Application::getRepository('Post');
$UserRepository = Application::getRepository('User');

#===============================================================================
# Last items
#===============================================================================
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

#===============================================================================
# Build document
#===============================================================================
$HomeTemplate = Template\Factory::build('home');
$HomeTemplate->set('LAST', [
	'PAGE' => $PageItemTemplate ?? FALSE,
	'POST' => $PostItemTemplate ?? FALSE,
	'USER' => $UserItemTemplate ?? FALSE
]);

$HomeTemplate->set('COUNT', [
	'PAGE' => $PageRepository->getCount(),
	'POST' => $PostRepository->getCount(),
	'USER' => $UserRepository->getCount()
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', 'Dashboard');
$MainTemplate->set('HTML', $HomeTemplate);
echo $MainTemplate;
