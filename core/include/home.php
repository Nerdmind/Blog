<?php
#===============================================================================
# Get repositories
#===============================================================================
$PostRepository = Application::getRepository('Post');
$UserRepository = Application::getRepository('User');

#===============================================================================
# Get paginated post list
#===============================================================================
$posts = $PostRepository->getPaginated(
	Application::get('POST.LIST_SORT'),
	Application::get('POST.LIST_SIZE')
);

foreach($posts as $Post) {
	$User = $UserRepository->find($Post->get('user'));
	$templates[] = generatePostItemTemplate($Post, $User);
}

#===============================================================================
# Build document
#===============================================================================
$HomeTemplate = Template\Factory::build('home');
$HomeTemplate->set('PAGINATION', [
	'HTML' => generatePostNaviTemplate(1)
]);
$HomeTemplate->set('LIST', [
	'POSTS' => $templates ?? []
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('HTML', $HomeTemplate);
$MainTemplate->set('HEAD', [
	'NAME' => Application::get('BLOGMETA.HOME'),
	'DESC' => Application::get('BLOGMETA.NAME').' – '
		.Application::get('BLOGMETA.DESC'),
	'PERM' => Application::getURL()
]);

echo $MainTemplate;
