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

$lastSite = ceil($Database->query(sprintf('SELECT COUNT(id) FROM %s', Post\Attribute::TABLE))->fetchColumn() / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Fetch post IDs from database
#===============================================================================
$execSQL = "SELECT id FROM %s ORDER BY {$site_sort} LIMIT ".(($currentSite-1) * $site_size).", {$site_size}";
$postIDs = $Database->query(sprintf($execSQL, Post\Attribute::TABLE))->fetchAll($Database::FETCH_COLUMN);

#===============================================================================
# TRY: Template\Exception
#===============================================================================
try {
	foreach($postIDs as $postID) {
		try {
			$Post = Post\Factory::build($postID);
			$User = User\Factory::build($Post->attr('user'));

			$ItemTemplate = generatePostItemTemplate($Post, $User);

			$posts[] = $ItemTemplate;
		}
		catch(Post\Exception $Exception){}
		catch(User\Exception $Exception){}
	}

	$PaginationTemplate = Template\Factory::build('pagination');
	$PaginationTemplate->set('THIS', $currentSite);
	$PaginationTemplate->set('LAST', $lastSite);
	$PaginationTemplate->set('HREF', Application::getAdminURL('post/?site=%d'));

	$ListTemplate = Template\Factory::build('post/index');
	$ListTemplate->set('LIST', [
		'POSTS' => $posts ?? []
	]);

	$ListTemplate->set('PAGINATION', [
		'THIS' => $currentSite,
		'LAST' => $lastSite,
		'HTML' => $PaginationTemplate
	]);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', $Language->text('title_post_overview', $currentSite));
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