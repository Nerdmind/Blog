<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
define('ADMINISTRATION', TRUE);
define('AUTHENTICATION', TRUE);

#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require '../../core/application.php';

#===============================================================================
# Check for search request
#===============================================================================
if($search = HTTP::GET('q')) {
	$PageRepository = Application::getRepository('Page');
	$UserRepository = Application::getRepository('User');

	foreach($PageRepository->search($search) as $Page) {
		$User = $UserRepository->find($Page->get('user'));
		$templates[] = generatePageItemTemplate($Page, $User);
	}
}

#===============================================================================
# Build document
#===============================================================================
$SearchTemplate = Template\Factory::build('page/search');
$SearchTemplate->set('QUERY', $search);
$SearchTemplate->set('PAGES', $templates ?? []);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_page_search'));
$MainTemplate->set('HTML', $SearchTemplate);

echo $MainTemplate;
