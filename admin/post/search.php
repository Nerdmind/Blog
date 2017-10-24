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
	if($postIDs = Post\Item::getSearchResultIDs($search, [NULL, NULL, NULL], $Database)) {
		foreach($postIDs as $postID) {
			try {
				$Post = Post\Factory::build($postID);
				$User = User\Factory::build($Post->attr('user'));

				$posts[] = generatePostItemTemplate($Post, $User);
			}
			catch(Post\Exception $Exception){}
			catch(User\Exception $Exception){}
		}
	}
}

#===============================================================================
# TRY: Template\Exception
#===============================================================================
try {
	$SearchTemplate = Template\Factory::build('post/search');
	$SearchTemplate->set('QUERY', $search);
	$SearchTemplate->set('POSTS', $posts ?? []);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', $Language->text('title_post_search'));
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