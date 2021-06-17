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
	foreach(Post\Item::getSearchResults($search, [NULL, NULL, NULL], $Database) as $Attribute) {
		try {
			$Post = Post\Factory::buildByAttribute($Attribute);
			$User = User\Factory::build($Post->attr('user'));

			$posts[] = generatePostItemTemplate($Post, $User);
		}
		catch(Post\Exception $Exception){}
		catch(User\Exception $Exception){}
	}
}

#===============================================================================
# Build document
#===============================================================================
$SearchTemplate = Template\Factory::build('post/search');
$SearchTemplate->set('QUERY', $search);
$SearchTemplate->set('POSTS', $posts ?? []);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', $Language->text('title_post_search'));
$MainTemplate->set('HTML', $SearchTemplate);

echo $MainTemplate;
