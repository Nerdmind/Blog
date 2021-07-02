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

#===============================================================================
# Redirect to user create form if no user exists
#===============================================================================
if(!$count) {
	HTTP::redirect(Application::getAdminURL('user/insert.php'));
}

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
$ListTemplate = Template\Factory::build('user/index');
$ListTemplate->set('LIST', [
	'USERS' => $templates ?? []
]);

$ListTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getAdminURL('user/')
	)
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_user_overview', $currentSite));
$MainTemplate->set('HTML', $ListTemplate);
echo $MainTemplate;
