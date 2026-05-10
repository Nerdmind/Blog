<?php

$language = Application::getLanguage();

$repository = Application::getRepository($entityClassName);

$pageSize = Application::get(sprintf('ADMIN.%s.LIST_SIZE', strtoupper($entityClassName)));
$sortOrder = Application::get(sprintf('ADMIN.%s.LIST_SORT', strtoupper($entityClassName)));

$count = $repository->getCount();
$pageCount = ceil($count / $pageSize);

$pageNumber = HTTP::GET('site') ?? 1;
$pageNumber = intval($pageNumber);

if(!$count) {
	HTTP::redirect(Application::getAdminURL(sprintf('%s/insert.php', $entityType)));
}

if($pageNumber < 1 or ($pageNumber > $pageCount and $pageCount > 0)) {
	Application::error404();
}

// For categories we want a paginated tree
if($entityClassName === 'Category') {
	$entities = $repository->getPaginatedTree(
		$pageSize, ($pageNumber-1) * $pageSize);
} else { // ... all other entity types
	$entities = $repository->getPaginated(
		$sortOrder, $pageSize, ($pageNumber-1) * $pageSize);
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
	'THIS' => $pageNumber,
	'LAST' => $pageCount,
	'HTML' => createPaginationTemplate(
		$pageNumber, $pageCount, Application::getAdminURL(sprintf('%s/', $entityType))
	)
]);

$template = Template\Factory::build('main');
$template->set('NAME', $language->text(sprintf('title_%s_overview', $entityType), $pageNumber));
$template->set('HTML', $listTemplate);

echo $template;