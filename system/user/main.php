<?php
#===============================================================================
# INCLUDE: Main configuration
#===============================================================================
require_once '../../core/application.php';

#===============================================================================
# TRY: User\Exception
#===============================================================================
try {
	if(Application::get('USER.SLUG_URLS')) {
		$User = User\Factory::buildBySlug(HTTP::GET('param'));
	}

	else {
		$User = User\Factory::build(HTTP::GET('param'));
	}

	$user_data = generateUserItemData($User);

	#===============================================================================
	# Add user data for previous and next user
	#===============================================================================
	try {
		$PrevUser = User\Factory::build($User->getPrevID());
		$user_data['PREV'] = generateUserItemData($PrevUser);
	} catch(User\Exception $Exception){}

	try {
		$NextUser = User\Factory::build($User->getNextID());
		$user_data['NEXT'] = generateUserItemData($NextUser);
	} catch(User\Exception $Exception){}

	#===============================================================================
	# TRY: Template\Exception
	#===============================================================================
	try {
		#===============================================================================
		# TRY: PDOException
		#===============================================================================
		try {
			$PostCountStatement = $Database->query(sprintf('SELECT COUNT(*) FROM %s WHERE user = %d', Post\Attribute::TABLE, $User->getID()));
			$PageCountStatement = $Database->query(sprintf('SELECT COUNT(*) FROM %s WHERE user = %d', Page\Attribute::TABLE, $User->getID()));
		}

		#===============================================================================
		# CATCH: PDOException
		#===============================================================================
		catch(PDOException $Exception) {
			exit($Exception->getMessage());
		}

		$UserTemplate = Template\Factory::build('user/main');
		$UserTemplate->set('USER', $user_data);
		$UserTemplate->set('COUNT', [
			'POST' => $PostCountStatement->fetchColumn(),
			'PAGE' => $PageCountStatement->fetchColumn()
		]);

		$MainTemplate = Template\Factory::build('main');
		$MainTemplate->set('HTML', $UserTemplate);
		$MainTemplate->set('HEAD', [
			'NAME' => $user_data['ATTR']['FULLNAME'],
			'DESC' => cut(removeLineBreaksAndTabs(removeHTML($user_data['BODY']['HTML']), ' '), Application::get('USER.DESCRIPTION_SIZE')),
			'PERM' => $User->getURL(),
			'OG_IMAGES' => $User->getFiles()
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
# CATCH: User\Exception
#===============================================================================
catch(User\Exception $Exception) {
	try {
		if(Application::get('USER.SLUG_URLS') === FALSE) {
			$User = User\Factory::buildBySlug(HTTP::GET('param'));
		} else {
			$User = User\Factory::build(HTTP::GET('param'));
		}

		HTTP::redirect($User->getURL());
	}

	catch(User\Exception $Exception) {
		Application::exit(404);
	}
}