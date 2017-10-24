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
# Pagination
#===============================================================================
$site_size = Application::get('POST.LIST_SIZE');
$site_sort = Application::get('POST.LIST_SORT');

$lastSite = ceil($Database->query(sprintf('SELECT COUNT(id) FROM %s', User\Attribute::TABLE))->fetchColumn() / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Fetch user IDs from database
#===============================================================================
$execSQL = "SELECT id FROM %s ORDER BY {$site_sort} LIMIT ".(($currentSite-1) * $site_size).", {$site_size}";
$userIDs = $Database->query(sprintf($execSQL, User\Attribute::TABLE))->fetchAll($Database::FETCH_COLUMN);

#===============================================================================
# TRY: Template\Exception
#===============================================================================
try {
	foreach($userIDs as $userID) {
		try {
			$User = User\Factory::build($userID);
			$ItemTemplate = generateUserItemTemplate($User);

			$users[] = $ItemTemplate;
		} catch(User\Exception $Exception){}
	}

	$PaginationTemplate = Template\Factory::build('pagination');
	$PaginationTemplate->set('THIS', $currentSite);
	$PaginationTemplate->set('LAST', $lastSite);
	$PaginationTemplate->set('HREF', Application::getAdminURL('user/?site=%d'));

	$ListTemplate = Template\Factory::build('user/index');
	$ListTemplate->set('LIST', [
		'USERS' => $users ?? []
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
}

#===============================================================================
# CATCH: Template\Exception
#===============================================================================
catch(Template\Exception $Exception) {
	Application::exit($Exception->getMessage());
}
?>