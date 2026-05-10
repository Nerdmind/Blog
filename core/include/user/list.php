<?php
#===============================================================================
# Get instances
#===============================================================================
$language = Application::getLanguage();

#===============================================================================
# Get repositories
#===============================================================================
$userRepository = Application::getRepository('User');

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('USER.LIST_SIZE');
$site_sort = Application::get('USER.LIST_SORT');

$count = $userRepository->getCount();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 or ($currentSite > $lastSite and $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Single redirect
#===============================================================================
if(Application::get('USER.REDIRECT_SINGLE') === true and $count === 1) {
	$user = $userRepository->getLast();
	HTTP::redirect(Application::getEntityURL($user));
}

#===============================================================================
# Get paginated user list
#===============================================================================
$users = $userRepository->getPaginated(
	$site_sort,
	$site_size,
	($currentSite-1) * $site_size
);

foreach($users as $user) {
	$templates[] = createEntityItemTemplate($user);
}

#===============================================================================
# Build document
#===============================================================================
$listTemplate = Template\Factory::build('user/list');
$listTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getUserURL()
	)
]);
$listTemplate->set('LIST', [
	'USERS' => $templates ?? []
]);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('HTML', $listTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => $language->text('title_user_overview', $currentSite)
]);

echo $mainTemplate;
