<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
const ADMINISTRATION = TRUE;
const AUTHENTICATION = TRUE;

#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require '../../core/application.php';

#===============================================================================
# Get repositories
#===============================================================================
$CategoryRepository = Application::getRepository('Category');

#===============================================================================
# Pagination
#===============================================================================
$site_size = Application::get('ADMIN.CATEGORY.LIST_SIZE');

$count = $CategoryRepository->getCount();
$lastSite = ceil($count / $site_size);

$currentSite = HTTP::GET('site') ?? 1;
$currentSite = intval($currentSite);

#===============================================================================
# Redirect to category create form if no category exists
#===============================================================================
if($count === 0) {
	HTTP::redirect(Application::getAdminURL('category/insert.php'));
}

if($currentSite < 1 OR ($currentSite > $lastSite AND $lastSite > 0)) {
	Application::error404();
}

#===============================================================================
# Get paginated category list
#===============================================================================
$categories = $CategoryRepository->getPaginatedTree(
	$site_size, ($currentSite-1) * $site_size);

foreach($categories as $Category) {
	$templates[] = generateCategoryItemTemplate($Category, TRUE);
}

#===============================================================================
# Build document
#===============================================================================
$ListTemplate = Template\Factory::build('category/index');
$ListTemplate->set('LIST', [
	'CATEGORIES' => $templates ?? []
]);

$ListTemplate->set('PAGINATION', [
	'THIS' => $currentSite,
	'LAST' => $lastSite,
	'HTML' => createPaginationTemplate(
		$currentSite, $lastSite, Application::getAdminURL('category/')
	)
]);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_category_overview', $currentSite));
$MainTemplate->set('HTML', $ListTemplate);
echo $MainTemplate;
