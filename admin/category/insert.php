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
# Instantiate new Category entity
#===============================================================================
$Category = new ORM\Entities\Category;

#===============================================================================
# Check for insert request
#===============================================================================
if(HTTP::issetPOST('insert')) {
	$Category->set('parent', HTTP::POST('parent') ?: NULL);
	$Category->set('slug', HTTP::POST('slug') ?: generateSlug(HTTP::POST('name')));
	$Category->set('name', HTTP::POST('name') ?: NULL);
	$Category->set('body', HTTP::POST('body') ?: NULL);
	$Category->set('argv', HTTP::POST('argv') ?: NULL);
	$Category->set('time_insert', HTTP::POST('time_insert') ?: date('Y-m-d H:i:s'));
	$Category->set('time_update', HTTP::POST('time_update') ?: date('Y-m-d H:i:s'));

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$CategoryRepository->insert($Category);
			HTTP::redirect(Application::getAdminURL('category/'));
		} catch(PDOException $Exception) {
			$messages[] = $Exception->getMessage();
		}
	}

	else {
		$messages[] = $Language->text('error_security_csrf');
	}
}

#===============================================================================
# Generate category list
#===============================================================================
foreach($CategoryRepository->getAll([], 'name ASC') as $_Category) {
	$categoryList[] = [
		'ID' => $_Category->getID(),
		'NAME' => $_Category->get('name'),
		'PARENT' => $_Category->get('parent'),
	];
}

#===============================================================================
# Build document
#===============================================================================
$FormTemplate = Template\Factory::build('category/form');
$FormTemplate->set('FORM', [
	'TYPE' => 'INSERT',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($Category->getAll(), CASE_UPPER),
	'CATEGORY_LIST' => $categoryList ??  [],
	'CATEGORY_TREE' => generateCategoryDataTree($categoryList ?? []),
	'TOKEN' => Application::getSecurityToken()
]);

$InsertTemplate = Template\Factory::build('category/insert');
$InsertTemplate->set('HTML', $FormTemplate);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_category_insert'));
$MainTemplate->set('HTML', $InsertTemplate);
echo $MainTemplate;
