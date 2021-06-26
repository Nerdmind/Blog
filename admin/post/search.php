<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
const ADMINISTRATION = TRUE;
const AUTHENTICATION = TRUE;

#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require '../../core/application.php';

#===============================================================================
# Check for search request
#===============================================================================
if($search = HTTP::GET('q')) {
	$PostRepository = Application::getRepository('Post');
	$UserRepository = Application::getRepository('User');

	foreach($PostRepository->search($search) as $Post) {
		$User = $UserRepository->find($Post->get('user'));
		$templates[] = generatePostItemTemplate($Post, $User);
	}
}

#===============================================================================
# Build document
#===============================================================================
$SearchTemplate = Template\Factory::build('post/search');
$SearchTemplate->set('QUERY', $search);
$SearchTemplate->set('POSTS', $templates ?? []);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_post_search'));
$MainTemplate->set('HTML', $SearchTemplate);

echo $MainTemplate;
