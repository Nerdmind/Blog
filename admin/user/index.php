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
# Get repositories
#===============================================================================
$UserRepository = Application::getRepository('User');

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('ADMIN.USER.LIST_SIZE');
$site_sort = Application::get('ADMIN.USER.LIST_SORT');

$count = $UserRepository->getCount();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Get paginated user list
#===============================================================================
$users = $UserRepository->getPaginated(
	$site_sort,
	$site_size,
	($currentSite-1) * $site_size
);

foreach($users as $User) {
	$templates[] = generateUserItemTemplate($User);
}

#===============================================================================
# Build document
#===============================================================================
$PaginationTemplate = Template\Factory::build('pagination');
$PaginationTemplate->set('THIS', $currentSite);
$PaginationTemplate->set('LAST', $lastSite);
$PaginationTemplate->set('HREF', Application::getAdminURL('user/?site=%d'));

$ListTemplate = Template\Factory::build('user/index');
$ListTemplate->set('LIST', [
	'USERS' => $templates ?? []
]);

$ListTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => $PaginationTemplate
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_user_overview', $currentSite));
$MainTemplate->set('HTML', $ListTemplate);
echo $MainTemplate;
