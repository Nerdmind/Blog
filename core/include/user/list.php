<?php
#===============================================================================
# Get instances
#===============================================================================
$Language = Application::getLanguage();

#===============================================================================
# Get repositories
#===============================================================================
$UserRepository = Application::getRepository('User');

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('USER.LIST_SIZE');
$site_sort = Application::get('USER.LIST_SORT');

$count = $UserRepository->getCount();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Single redirect
#===============================================================================
if(Application::get('USER.REDIRECT_SINGLE') === TRUE AND $count === 1) {
	$User = $UserRepository->getLast();
	HTTP::redirect(Application::getEntityURL($User));
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
$ListTemplate = Template\Factory::build('user/list');
$ListTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getUserURL()
	)
]);
$ListTemplate->set('LIST', [
	'USERS' => $templates ?? []
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('HTML', $ListTemplate);
$MainTemplate->set('HEAD', [
	'NAME' => $Language->text('title_user_overview', $currentSite)
]);

echo $MainTemplate;
