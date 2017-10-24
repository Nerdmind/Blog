<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
define('ADMINISTRATION', TRUE);
define('AUTHENTICATION', TRUE);

#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require '../../core/application.php';

#===============================================================================
# IF: Handle search request
#===============================================================================
if($search = HTTP::GET('q')) {
	if($pageIDs = Page\Item::getSearchResultIDs($search, $Database)) {
		foreach($pageIDs as $pageID) {
			try {
				$Page = Page\Factory::build($pageID);
				$User = User\Factory::build($Page->attr('user'));

				$pages[] = generatePageItemTemplate($Page, $User);
			}
			catch(Page\Exception $Exception){}
			catch(User\Exception $Exception){}
		}
	}
}

#===============================================================================
# TRY: Template\Exception
#===============================================================================
try {
	$SearchTemplate = Template\Factory::build('page/search');
	$SearchTemplate->set('QUERY', $search);
	$SearchTemplate->set('PAGES', $pages ?? []);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', $Language->text('title_page_search'));
	$MainTemplate->set('HTML', $SearchTemplate);

	echo $MainTemplate;
}

#===============================================================================
# CATCH: Template\Exception
#===============================================================================
catch(Template\Exception $Exception) {
	Application::exit($Exception->getMessage());
}
?>