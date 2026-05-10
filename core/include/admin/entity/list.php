<?php

$language = Application::getLanguage();

$repository = Application::getRepository($entity_ns);

$site_size = Application::get(sprintf('ADMIN.%s.LIST_SIZE', strtoupper($entity_ns)));
$site_sort = Application::get(sprintf('ADMIN.%s.LIST_SORT', strtoupper($entity_ns)));

$count = $repository->getCount();
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
	$entities = $repository->getPaginatedTree(
		$site_size, ($currentSite-1) * $site_size);
} else { // ... all other entity types
	$entities = $repository->getPaginated(
		$site_sort,	$site_size,	($currentSite-1) * $site_size);
}

foreach($entities as $entity) {
	if($entity_ns === 'Category') {
		$templates[] = createEntityItemTemplate($entity, ['is_root' => true]);
	} else {
		$templates[] = createEntityItemTemplate($entity);
	}
}

$listTemplate = Template\Factory::build(sprintf('%s/index', $entity_str));
$listTemplate->set('LIST', [
	$list_key_plural => $templates ?? []
]);

$listTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getAdminURL(sprintf('%s/', $entity_str))
	)
]);

$template = Template\Factory::build('main');
$template->set('NAME', $language->text(sprintf('title_%s_overview', $entity_str), $currentSite));
$template->set('HTML', $listTemplate);

echo $template;