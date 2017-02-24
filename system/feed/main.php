<?php
#===============================================================================
# INCLUDE: Main configuration
#===============================================================================
require_once '../../core/application.php';

#===============================================================================
# HEADER: Content-Type for XML document
#===============================================================================
HTTP::responseHeader(HTTP::HEADER_CONTENT_TYPE, HTTP::CONTENT_TYPE_XML);

#===============================================================================
# TRY: Template\Exception
#===============================================================================
try {
	if(HTTP::GET('item') !== 'page') {
		$execSQL = 'SELECT id FROM %s ORDER BY '.Application::get('POST.FEED_SORT').' LIMIT '.Application::get('POST.FEED_SIZE');
		$postIDs = $Database->query(sprintf($execSQL, Post\Attribute::TABLE))->fetchAll($Database::FETCH_COLUMN);

		foreach($postIDs as $postID) {
			try {
				$Post = Post\Factory::build($postID);
				$User = User\Factory::build($Post->attr('user'));

				$ItemTemplate = Template\Factory::build('feed/item_post');
				$ItemTemplate->set('POST', generatePostItemData($Post));
				$ItemTemplate->set('USER', generateUserItemData($User));

				$posts[] = $ItemTemplate;
			}

			catch(Post\Exception $Exception){}
			catch(User\Exception $Exception){}
		}
	}

	if(HTTP::GET('item') !== 'post') {
		$execSQL = 'SELECT id FROM %s ORDER BY '.Application::get('PAGE.FEED_SORT').' LIMIT '.Application::get('PAGE.FEED_SIZE');
		$pageIDs = $Database->query(sprintf($execSQL, Page\Attribute::TABLE))->fetchAll($Database::FETCH_COLUMN);

		foreach($pageIDs as $pageID) {
			try {
				$Page = Page\Factory::build($pageID);
				$User = User\Factory::build($Page->attr('user'));

				$ItemTemplate = Template\Factory::build('feed/item_page');
				$ItemTemplate->set('PAGE', generatePageItemData($Page));
				$ItemTemplate->set('USER', generateUserItemData($User));

				$pages[] = $ItemTemplate;
			}

			catch(Page\Exception $Exception){}
			catch(User\Exception $Exception){}
		}
	}

	$FeedTemplate = Template\Factory::build('feed/main');
	$FeedTemplate->set('FEED', [
		'TYPE' => HTTP::GET('item'),
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
	$Exception->defaultHandler();
}
?>