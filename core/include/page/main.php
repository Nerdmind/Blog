<?php
#===============================================================================
# Get instances
#===============================================================================
$Database = Application::getDatabase();
$Language = Application::getLanguage();

#===============================================================================
# TRY: Page\Exception
#===============================================================================
try {
	if(Application::get('PAGE.SLUG_URLS')) {
		$Page = Page\Factory::buildBySlug($param);
	}

	else {
		$Page = Page\Factory::build($param);
	}

	$User = User\Factory::build($Page->get('user'));

	$page_data = generateItemTemplateData($Page);
	$user_data = generateItemTemplateData($User);

	#===============================================================================
	# Add page data for previous and next page
	#===============================================================================
	try {
		$PrevPage = Page\Factory::build($Page->getPrevID());
		$page_data['PREV'] = generateItemTemplateData($PrevPage);
	} catch(Page\Exception $Exception){}

	try {
		$NextPage = Page\Factory::build($Page->getNextID());
		$page_data['NEXT'] = generateItemTemplateData($NextPage);
	} catch(Page\Exception $Exception){}

	#===============================================================================
	# Build document
	#===============================================================================
	$PageTemplate = Template\Factory::build('page/main');
	$PageTemplate->set('PAGE', $page_data);
	$PageTemplate->set('USER', $user_data);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('HTML', $PageTemplate);
	$MainTemplate->set('HEAD', [
		'NAME' => $page_data['ATTR']['NAME'],
		'DESC' => description($page_data['BODY']['HTML'](), Application::get('PAGE.DESCRIPTION_SIZE')),
		'PERM' => $page_data['URL'],
		'OG_IMAGES' => $page_data['FILE']['LIST']
	]);

	# Get access to the current item data from main template
	$MainTemplate->set('TYPE', 'PAGE');
	$MainTemplate->set('PAGE', $page_data);
	$MainTemplate->set('USER', $user_data);

	echo $MainTemplate;
}

#===============================================================================
# CATCH: Page\Exception
#===============================================================================
catch(Page\Exception $Exception) {
	try {
		if(Application::get('PAGE.SLUG_URLS') === FALSE) {
			$Page = Page\Factory::buildBySlug($param);
		} else {
			$Page = Page\Factory::build($param);
		}

		HTTP::redirect(Application::getEntityURL($Page));
	}

	catch(Page\Exception $Exception) {
		Application::error404();
	}
}
