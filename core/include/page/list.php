<?php
#===============================================================================
# Get instances
#===============================================================================
$Database = Application::getDatabase();
$Language = Application::getLanguage();

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('PAGE.LIST_SIZE');
$site_sort = Application::get('PAGE.LIST_SORT');

$count = $Database->query(sprintf('SELECT COUNT(id) FROM %s', Page\Attribute::TABLE))->fetchColumn();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Single redirect
#===============================================================================
if(Application::get('PAGE.SINGLE_REDIRECT') === TRUE AND $count === '1') {
	$Statement = $Database->query(sprintf('SELECT id FROM %s LIMIT 1', Page\Attribute::TABLE));
	$Page = Page\Factory::build($Statement->fetchColumn());
	HTTP::redirect($Page->getURL());
}

#===============================================================================
# TRY: Template\Exception
#===============================================================================
try {
	$execSQL = "SELECT * FROM %s ORDER BY {$site_sort} LIMIT ".(($currentSite-1) * $site_size).", {$site_size}";
	$Statement = $Database->query(sprintf($execSQL, Page\Attribute::TABLE));

	while($Attribute = $Statement->fetchObject('Page\Attribute')) {
		try {
			$Page = Page\Factory::buildByAttribute($Attribute);
			$User = User\Factory::build($Page->attr('user'));

			$ItemTemplate = generatePageItemTemplate($Page, $User);

			$pages[] = $ItemTemplate;
		}
		catch(Page\Exception $Exception){}
		catch(User\Exception $Exception){}
	}

	$ListTemplate = Template\Factory::build('page/list');
	$ListTemplate->set('PAGINATION', [
		'THIS' => $currentSite,
		'LAST' => $lastSite,
		'HTML' => generatePageNaviTemplate($currentSite)
	]);
	$ListTemplate->set('LIST', [
		'PAGES' => $pages ?? []
	]);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('HTML', $ListTemplate);
	$MainTemplate->set('HEAD', [
		'NAME' => $Language->text('title_page_overview', $currentSite)
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