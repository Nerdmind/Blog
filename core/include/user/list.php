<?php
#===============================================================================
# Get instances
#===============================================================================
$Database = Application::getDatabase();
$Language = Application::getLanguage();

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('USER.LIST_SIZE');
$site_sort = Application::get('USER.LIST_SORT');

$count = $Database->query(sprintf('SELECT COUNT(id) FROM %s', User\Attribute::TABLE))->fetchColumn();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Single redirect
#===============================================================================
if(Application::get('USER.SINGLE_REDIRECT') === TRUE AND $count === '1') {
	$Statement = $Database->query(sprintf('SELECT id FROM %s LIMIT 1', User\Attribute::TABLE));
	$User = User\Factory::build($Statement->fetchColumn());
	HTTP::redirect($User->getURL());
}

$execSQL = "SELECT * FROM %s ORDER BY {$site_sort} LIMIT ".(($currentSite-1) * $site_size).", {$site_size}";
$Statement = $Database->query(sprintf($execSQL, User\Attribute::TABLE));

while($Attribute = $Statement->fetchObject('User\Attribute')) {
	try {
		$User = User\Factory::buildByAttribute($Attribute);
		$ItemTemplate = generateUserItemTemplate($User);

		$users[] = $ItemTemplate;
	} catch(User\Exception $Exception){}
}

foreach($userIDs as $userID) {
	try {
		$User = User\Factory::build($userID);
		$ItemTemplate = generateUserItemTemplate($User);

		$users[] = $ItemTemplate;
	} catch(User\Exception $Exception){}
}

#===============================================================================
# Build document
#===============================================================================
$ListTemplate = Template\Factory::build('user/list');
$ListTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => generateUserNaviTemplate($currentSite)
]);
$ListTemplate->set('LIST', [
	'USERS' => $users ?? []
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('HTML', $ListTemplate);
$MainTemplate->set('HEAD', [
	'NAME' => $Language->text('title_user_overview', $currentSite)
]);

echo $MainTemplate;
