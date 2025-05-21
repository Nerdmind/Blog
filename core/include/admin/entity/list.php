<?php

$Language = Application::getLanguage();

$Repository = Application::getRepository($entity_ns);

$site_size = Application::get(sprintf('ADMIN.%s.LIST_SIZE', strtoupper($entity_ns)));
$site_sort = Application::get(sprintf('ADMIN.%s.LIST_SORT', strtoupper($entity_ns)));

$count = $Repository->getCount();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if(!$count) {
	HTTP::redirect(Application::getAdminURL(sprintf('%s/insert.php', $entity_str)));
}

if($currentSite < 1 or ($currentSite > $lastSite and $lastSite > 0)) {
	Application::error404();
}

// For categories we want a paginated tree
if($entity_ns === 'Category') {
	$entities = $Repository->getPaginatedTree(
		$site_size, ($currentSite-1) * $site_size);
} else { // ... all other entity types
	$entities = $Repository->getPaginated(
		$site_sort,	$site_size,	($currentSite-1) * $site_size);
}

foreach($entities as $Entity) {
	if($entity_ns === 'Category') {
		$templates[] = createEntityItemTemplate($Entity, ['is_root' => true]);
	} else {
		$templates[] = createEntityItemTemplate($Entity);
	}
}

$ListTemplate = Template\Factory::build(sprintf('%s/index', $entity_str));
$ListTemplate->set('LIST', [
	$list_key_plural => $templates ?? []
]);

$ListTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getAdminURL(sprintf('%s/', $entity_str))
	)
]);

$Template = Template\Factory::build('main');
$Template->set('NAME', $Language->text(sprintf('title_%s_overview', $entity_str), $currentSite));
$Template->set('HTML', $ListTemplate);

echo $Template;