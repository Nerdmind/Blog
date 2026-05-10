<?php

$language = Application::getLanguage();

$repository = Application::getRepository($entityClassName);

$siteSize = Application::get(sprintf('ADMIN.%s.LIST_SIZE', strtoupper($entityClassName)));
$siteSort = Application::get(sprintf('ADMIN.%s.LIST_SORT', strtoupper($entityClassName)));

$count = $repository->getCount();
$lastSite = ceil($count / $siteSize);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

if(!$count) {
	HTTP::redirect(Application::getAdminURL(sprintf('%s/insert.php', $entityType)));
}

if($currentSite < 1 or ($currentSite > $lastSite and $lastSite > 0)) {
	Application::error404();
}

// For categories we want a paginated tree
if($entityClassName === 'Category') {
	$entities = $repository->getPaginatedTree(
		$siteSize, ($currentSite-1) * $siteSize);
} else { // ... all other entity types
	$entities = $repository->getPaginated(
		$siteSort, $siteSize, ($currentSite-1) * $siteSize);
}

foreach($entities as $entity) {
	if($entityClassName === 'Category') {
		$templates[] = createEntityItemTemplate($entity, ['is_root' => true]);
	} else {
		$templates[] = createEntityItemTemplate($entity);
	}
}

$listTemplate = Template\Factory::build(sprintf('%s/index', $entityType));
$listTemplate->set('LIST', [
	$listKeyPlural => $templates ?? []
]);

$listTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getAdminURL(sprintf('%s/', $entityType))
	)
]);

$template = Template\Factory::build('main');
$template->set('NAME', $language->text(sprintf('title_%s_overview', $entityType), $currentSite));
$template->set('HTML', $listTemplate);

echo $template;