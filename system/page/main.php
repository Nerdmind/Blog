<?php
#===============================================================================
# INCLUDE: Main configuration
#===============================================================================
require_once '../../core/application.php';

#===============================================================================
# TRY: Page\Exception, User\Exception
#===============================================================================
try {
	if(Application::get('PAGE.SLUG_URLS')) {
		$Page = Page\Factory::buildBySlug(HTTP::GET('param'));
	}

	else {
		$Page = Page\Factory::build(HTTP::GET('param'));
	}

	$User = User\Factory::build($Page->attr('user'));

	$page_data = generatePageItemData($Page);
	$user_data = generateUserItemData($User);

	#===============================================================================
	# Add page data for previous and next page
	#===============================================================================
	try {
		$PrevPage = Page\Factory::build($Page->getPrevID());
		$page_data['PREV'] = generatePageItemData($PrevPage);
	} catch(Page\Exception $Exception){}

	try {
		$NextPage = Page\Factory::build($Page->getNextID());
		$page_data['NEXT'] = generatePageItemData($NextPage);
	} catch(Page\Exception $Exception){}

	#===============================================================================
	# TRY: Template\Exception
	#===============================================================================
	try {
		$PageTemplate = Template\Factory::build('page/main');
		$PageTemplate->set('PAGE', $page_data);
		$PageTemplate->set('USER', $user_data);

		$MainTemplate = Template\Factory::build('main');
		$MainTemplate->set('HTML', $PageTemplate);
		$MainTemplate->set('HEAD', [
			'NAME' => $page_data['ATTR']['NAME'],
			'DESC' => cut(removeLineBreaksAndTabs(removeHTML($page_data['BODY']['HTML']), ' '), Application::get('PAGE.DESCRIPTION_SIZE')),
			'PERM' => $page_data['URL'],
			'OG_IMAGES' => $page_data['FILE']['LIST']
		]);

		echo $MainTemplate;
	}

	#===============================================================================
	# CATCH: Template\Exception
	#===============================================================================
	catch(Template\Exception $Exception) {
		$Exception->defaultHandler();
	}
}

#===============================================================================
# CATCH: Page\Exception
#===============================================================================
catch(Page\Exception $Exception) {
	try {
		if(Application::get('PAGE.SLUG_URLS') === FALSE) {
			$Page = Page\Factory::buildBySlug(HTTP::GET('param'));
		} else {
			$Page = Page\Factory::build(HTTP::GET('param'));
		}

		HTTP::redirect($Page->getURL());
	}

	catch(Page\Exception $Exception) {
		Application::exit(404);
	}
}

#===============================================================================
# CATCH: User\Exception
#===============================================================================
catch(User\Exception $Exception) {
	exit($Exception->getMessage());
}