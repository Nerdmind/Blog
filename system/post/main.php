<?php
#===============================================================================
# Get instances
#===============================================================================
$Database = Application::getDatabase();
$Language = Application::getLanguage();

#===============================================================================
# TRY: Post\Exception, User\Exception
#===============================================================================
try {
	if(Application::get('POST.SLUG_URLS')) {
		$Post = Post\Factory::buildBySlug($param);
	}

	else {
		$Post = Post\Factory::build($param);
	}

	$User = User\Factory::build($Post->attr('user'));

	$post_data = generatePostItemData($Post);
	$user_data = generateUserItemData($User);

	#===============================================================================
	# Add post data for previous and next post
	#===============================================================================
	try {
		$PrevPost = Post\Factory::build($Post->getPrevID());
		$post_data['PREV'] = generatePostItemData($PrevPost);
	} catch(Post\Exception $Exception){}

	try {
		$NextPost = Post\Factory::build($Post->getNextID());
		$post_data['NEXT'] = generatePostItemData($NextPost);
	} catch(Post\Exception $Exception){}

	#===============================================================================
	# TRY: Template\Exception
	#===============================================================================
	try {
		$PostTemplate = Template\Factory::build('post/main');
		$PostTemplate->set('POST', $post_data);
		$PostTemplate->set('USER', $user_data);

		$MainTemplate = Template\Factory::build('main');
		$MainTemplate->set('HTML', $PostTemplate);
		$MainTemplate->set('HEAD', [
			'NAME' => $post_data['ATTR']['NAME'],
			'DESC' => cut(removeLineBreaksAndTabs(removeHTML($post_data['BODY']['HTML']), ' '), Application::get('POST.DESCRIPTION_SIZE')),
			'PERM' => $post_data['URL'],
			'OG_IMAGES' => $post_data['FILE']['LIST']
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
# CATCH: Post\Exception
#===============================================================================
catch(Post\Exception $Exception) {
	try {
		if(Application::get('POST.SLUG_URLS') === FALSE) {
			$Post = Post\Factory::buildBySlug($param);
		} else {
			$Post = Post\Factory::build($param);
		}

		HTTP::redirect($Post->getURL());
	}

	catch(Post\Exception $Exception) {
		Application::exit(404);
	}
}

#===============================================================================
# CATCH: User\Exception
#===============================================================================
catch(User\Exception $Exception) {
	$Exception->defaultHandler();
}