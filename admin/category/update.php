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
# Throw 404 error if category could not be found
#===============================================================================
if(!$Category = $CategoryRepository->find(HTTP::GET('id'))) {
	Application::error404();
}

#===============================================================================
# Check for update request
#===============================================================================
if(HTTP::issetPOST('update')) {
	$Category->set('slug', HTTP::POST('slug') ?: generateSlug(HTTP::POST('name')));
	$Category->set('name', HTTP::POST('name') ?: NULL);
	$Category->set('body', HTTP::POST('body') ?: NULL);
	$Category->set('argv', HTTP::POST('argv') ?: NULL);
	$Category->set('time_insert', HTTP::POST('time_insert') ?: date('Y-m-d H:i:s'));
	$Category->set('time_update', HTTP::POST('time_update') ?: date('Y-m-d H:i:s'));

	# Modify parent field only if it is not a self-reference
	if(HTTP::POST('parent') != $Category->getID()) {
		$Category->set('parent', HTTP::POST('parent') ?: NULL);
	}

	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		try {
			$CategoryRepository->update($Category);
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
	'TYPE' => 'UPDATE',
	'INFO' => $messages ?? [],
	'DATA' => array_change_key_case($Category->getAll(), CASE_UPPER),
	'CATEGORY_LIST' => $categoryList ??  [],
	'CATEGORY_TREE' => generateCategoryDataTree($categoryList ?? []),
	'TOKEN' => Application::getSecurityToken()
]);

$UpdateTemplate = Template\Factory::build('category/update');
$UpdateTemplate->set('CATEGORY', generateItemTemplateData($Category));
$UpdateTemplate->set('HTML', $FormTemplate);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_category_update'));
$MainTemplate->set('HTML', $UpdateTemplate);
echo $MainTemplate;
