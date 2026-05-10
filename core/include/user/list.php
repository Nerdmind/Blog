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
$pageSize = Application::get('USER.LIST_SIZE');
$sortOrder = Application::get('USER.LIST_SORT');

$count = $userRepository->getCount();
$pageCount = ceil($count / $pageSize);

$pageNumber = HTTP::GET('site') ?? 1;
$pageNumber = intval($pageNumber);

if($pageNumber < 1 or ($pageNumber > $pageCount and $pageCount > 0)) {
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
	$sortOrder,
	$pageSize,
	($pageNumber-1) * $pageSize
);

foreach($users as $user) {
	$templates[] = createEntityItemTemplate($user);
}

#===============================================================================
# Build document
#===============================================================================
$listTemplate = Template\Factory::build('user/list');
$listTemplate->set('PAGINATION', [
	'THIS' => $pageNumber,
	'LAST' => $pageCount,
	'HTML' => createPaginationTemplate(
		$pageNumber, $pageCount, Application::getUserURL()
	)
]);
$listTemplate->set('LIST', [
	'USERS' => $templates ?? []
]);

$mainTemplate = Template\Factory::build('main');
$mainTemplate->set('HTML', $listTemplate);
$mainTemplate->set('HEAD', [
	'NAME' => $language->text('title_user_overview', $pageNumber)
]);

echo $mainTemplate;
