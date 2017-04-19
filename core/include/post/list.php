<?php
#===============================================================================
# Get instances
#===============================================================================
$Database = Application::getDatabase();
$Language = Application::getLanguage();

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
# TRY: Template\Exception
#===============================================================================
try {
	$execSQL = "SELECT id FROM %s ORDER BY {$site_sort} LIMIT ".(($currentSite-1) * $site_size).", {$site_size}";
	$postIDs = $Database->query(sprintf($execSQL, Post\Attribute::TABLE))->fetchAll($Database::FETCH_COLUMN);

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

	$ListTemplate = Template\Factory::build('post/list');
	$ListTemplate->set('PAGINATION', [
		'THIS' => $currentSite,
		'LAST' => $lastSite,
		'HTML' => generatePostNaviTemplate($currentSite)
	]);
	$ListTemplate->set('LIST', [
		'POSTS' => $posts ?? []
	]);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('HTML', $ListTemplate);
	$MainTemplate->set('HEAD', [
		'NAME' => $Language->text('title_post_overview', $currentSite)
	]);

	echo $MainTemplate;
}

#===============================================================================
# CATCH: Template\Exception
#===============================================================================
catch(Template\Exception $Exception) {
	Application::exit($Exception->getMessage());
}
?>