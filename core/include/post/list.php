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

$count = $Database->query(sprintf('SELECT COUNT(id) FROM %s', Post\Attribute::TABLE))->fetchColumn();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Single redirect
#===============================================================================
if(Application::get('POST.SINGLE_REDIRECT') === TRUE AND $count === '1') {
	$Statement = $Database->query(sprintf('SELECT id FROM %s LIMIT 1', Post\Attribute::TABLE));
	$Post = Post\Factory::build($Statement->fetchColumn());
	HTTP::redirect($Post->getURL());
}

$execSQL = "SELECT * FROM %s ORDER BY {$site_sort} LIMIT ".(($currentSite-1) * $site_size).", {$site_size}";
$Statement = $Database->query(sprintf($execSQL, Post\Attribute::TABLE));

while($Attribute = $Statement->fetchObject('Post\Attribute')) {
	try {
		$Post = Post\Factory::buildByAttribute($Attribute);
		$User = User\Factory::build($Post->attr('user'));

		$ItemTemplate = generatePostItemTemplate($Post, $User);

		$posts[] = $ItemTemplate;
	}
	catch(Post\Exception $Exception){}
	catch(User\Exception $Exception){}
}

#===============================================================================
# Build document
#===============================================================================
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
