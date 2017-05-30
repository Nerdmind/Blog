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
# TRY: Template\Exception
#===============================================================================
try {
	if(!isset($param) OR $param !== 'page') {
		$execSQL = 'SELECT id FROM %s ORDER BY '.Application::get('POST.FEED_SORT').' LIMIT '.Application::get('POST.FEED_SIZE');
		$postIDs = $Database->query(sprintf($execSQL, Post\Attribute::TABLE))->fetchAll($Database::FETCH_COLUMN);

		foreach($postIDs as $postID) {
			try {
				$Post = Post\Factory::build($postID);
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

	if(!isset($param) OR $param !== 'post') {
		$execSQL = 'SELECT id FROM %s ORDER BY '.Application::get('PAGE.FEED_SORT').' LIMIT '.Application::get('PAGE.FEED_SIZE');
		$pageIDs = $Database->query(sprintf($execSQL, Page\Attribute::TABLE))->fetchAll($Database::FETCH_COLUMN);

		foreach($pageIDs as $pageID) {
			try {
				$Page = Page\Factory::build($pageID);
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

	$FeedTemplate = Template\Factory::build('feed/main');
	$FeedTemplate->set('FEED', [
		'TYPE' => $param ?? NULL,
		'LIST' => [
			'POSTS' => $posts ?? [],
			'PAGES' => $pages ?? [],
		]
	]);

	echo $FeedTemplate;
}

#===============================================================================
# CATCH: Template\Exception
#===============================================================================
catch(Template\Exception $Exception) {
	Application::exit($Exception->getMessage());
}
?>