<?php
#===============================================================================
# Get instances
#===============================================================================
$Database = Application::getDatabase();
$Language = Application::getLanguage();

#===============================================================================
# HEADER: Content-Type for XML document
#===============================================================================
HTTP::responseHeader(HTTP::HEADER_CONTENT_TYPE, HTTP::CONTENT_TYPE_XML);

#===============================================================================
# Post feed
#===============================================================================
if(!isset($param) OR $param !== 'page') {
	$POST['FEED_SORT'] = Application::get('POST.FEED_SORT');
	$POST['FEED_SIZE'] = Application::get('POST.FEED_SIZE');

	$execSQL = "SELECT * FROM %s ORDER BY {$POST['FEED_SORT']} LIMIT {$POST['FEED_SIZE']}";
	$Statement = $Database->query(sprintf($execSQL, Post\Attribute::TABLE));

	while($Attribute = $Statement->fetchObject('Post\Attribute')) {
		try {
			$Post = Post\Factory::buildByAttribute($Attribute);
			$User = User\Factory::build($Post->attr('user'));

			$ItemTemplate = Template\Factory::build('feed/item_post');
			$ItemTemplate->set('POST', generateItemTemplateData($Post));
			$ItemTemplate->set('USER', generateItemTemplateData($User));

			$posts[] = $ItemTemplate;
		}

		catch(Post\Exception $Exception){}
		catch(User\Exception $Exception){}
	}
}

#===============================================================================
# Page feed
#===============================================================================
if(!isset($param) OR $param !== 'post') {
	$PAGE['FEED_SORT'] = Application::get('PAGE.FEED_SORT');
	$PAGE['FEED_SIZE'] = Application::get('PAGE.FEED_SIZE');

	$execSQL = "SELECT * FROM %s ORDER BY {$PAGE['FEED_SORT']} LIMIT {$PAGE['FEED_SIZE']}";
	$Statement = $Database->query(sprintf($execSQL, Page\Attribute::TABLE));

	while($Attribute = $Statement->fetchObject('Page\Attribute')) {
		try {
			$Page = Page\Factory::buildByAttribute($Attribute);
			$User = User\Factory::build($Page->attr('user'));

			$ItemTemplate = Template\Factory::build('feed/item_page');
			$ItemTemplate->set('PAGE', generateItemTemplateData($Page));
			$ItemTemplate->set('USER', generateItemTemplateData($User));

			$pages[] = $ItemTemplate;
		}

		catch(Page\Exception $Exception){}
		catch(User\Exception $Exception){}
	}
}

#===============================================================================
# Build document
#===============================================================================
$FeedTemplate = Template\Factory::build('feed/main');
$FeedTemplate->set('FEED', [
	'TYPE' => $param ?? NULL,
	'LIST' => [
		'POSTS' => $posts ?? [],
		'PAGES' => $pages ?? [],
	]
]);

echo $FeedTemplate;
