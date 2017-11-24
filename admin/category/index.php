<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
define('ADMINISTRATION', TRUE);
define('AUTHENTICATION', TRUE);

#===============================================================================
# INCLUDE: Main configuration
#===============================================================================
require '../../core/application.php';

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('CATEGORY.LIST_SIZE');
$site_sort = Application::get('CATEGORY.LIST_SORT');

$lastSite = ceil($Database->query(sprintf('SELECT COUNT(id) FROM %s', Category\Attribute::TABLE))->fetchColumn() / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Fetch category IDs from database
#===============================================================================
$execSQL = "SELECT id FROM %s ORDER BY {$site_sort} LIMIT ".(($currentSite-1) * $site_size).", {$site_size}";
$categoryIDs = $Database->query(sprintf($execSQL, Category\Attribute::TABLE))->fetchAll($Database::FETCH_COLUMN);

#===============================================================================
# TRY: Template\Exception
#===============================================================================
try {
	foreach($categoryIDs as $categoryID) {
		try {
			$Category = Category\Factory::build($categoryID);
            $ItemTemplate = generateCategoryItemTemplate($Category);
			$categories[] = $ItemTemplate;
		}
		catch(Category\Exception $Exception){}
	}

	$PaginationTemplate = Template\Factory::build('pagination');
	$PaginationTemplate->set('THIS', $currentSite);
	$PaginationTemplate->set('LAST', $lastSite);
	$PaginationTemplate->set('HREF', Application::getAdminURL('category/?site=%d'));

	$ListTemplate = Template\Factory::build('category/index');
	$ListTemplate->set('LIST', [
		'CATEGORIES' => $categories ?? []
	]);

	$ListTemplate->set('PAGINATION', [
		'THIS' => $currentSite,
		'LAST' => $lastSite,
		'HTML' => $PaginationTemplate
	]);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', $Language->text('title_category_overview', $currentSite));
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