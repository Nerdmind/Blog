<?php

$language = Application::getLanguage();

$repository = Application::getRepository($entityNs);

$siteSize = Application::get(sprintf('ADMIN.%s.LIST_SIZE', strtoupper($entityNs)));
$siteSort = Application::get(sprintf('ADMIN.%s.LIST_SORT', strtoupper($entityNs)));

$count = $repository->getCount();
$lastSite = ceil($count / $siteSize);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if(!$count) {
	HTTP::redirect(Application::getAdminURL(sprintf('%s/insert.php', $entityStr)));
}

if($currentSite < 1 or ($currentSite > $lastSite and $lastSite > 0)) {
	Application::error404();
}

// For categories we want a paginated tree
if($entityNs === 'Category') {
	$entities = $repository->getPaginatedTree(
		$siteSize, ($currentSite-1) * $siteSize);
} else { // ... all other entity types
	$entities = $repository->getPaginated(
		$siteSort, $siteSize, ($currentSite-1) * $siteSize);
}

foreach($entities as $entity) {
	if($entityNs === 'Category') {
		$templates[] = createEntityItemTemplate($entity, ['is_root' => true]);
	} else {
		$templates[] = createEntityItemTemplate($entity);
	}
}

$listTemplate = Template\Factory::build(sprintf('%s/index', $entityStr));
$listTemplate->set('LIST', [
	$listKeyPlural => $templates ?? []
]);

$listTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getAdminURL(sprintf('%s/', $entityStr))
	)
]);

$template = Template\Factory::build('main');
$template->set('NAME', $language->text(sprintf('title_%s_overview', $entityStr), $currentSite));
$template->set('HTML', $listTemplate);

echo $template;