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
$site_size = Application::get('PAGE.LIST_SIZE');
$site_sort = Application::get('PAGE.LIST_SORT');

$lastSite = ceil($Database->query(sprintf('SELECT COUNT(id) FROM %s', Page\Attribute::TABLE))->fetchColumn() / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Fetch page IDs from database
#===============================================================================
$execSQL = "SELECT id FROM %s ORDER BY {$site_sort} LIMIT ".(($currentSite-1) * $site_size).", {$site_size}";
$pageIDs = $Database->query(sprintf($execSQL, Page\Attribute::TABLE))->fetchAll($Database::FETCH_COLUMN);

#===============================================================================
# TRY: Template\Exception
#===============================================================================
try {
	foreach($pageIDs as $pageID) {
		try {
			$Page = Page\Factory::build($pageID);
			$User = User\Factory::build($Page->attr('user'));

			$ItemTemplate = generatePageItemTemplate($Page, $User);

			$pages[] = $ItemTemplate;
		}
		catch(Page\Exception $Exception){}
		catch(User\Exception $Exception){}
	}

	$PaginationTemplate = Template\Factory::build('pagination');
	$PaginationTemplate->set('THIS', $currentSite);
	$PaginationTemplate->set('LAST', $lastSite);
	$PaginationTemplate->set('HREF', Application::getAdminURL('page/?site=%d'));

	$ListTemplate = Template\Factory::build('page/index');
	$ListTemplate->set('LIST', [
		'PAGES' => $pages ?? []
	]);

	$ListTemplate->set('PAGINATION', [
		'THIS' => $currentSite,
		'LAST' => $lastSite,
		'HTML' => $PaginationTemplate
	]);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', $Language->text('title_page_overview', $currentSite));
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