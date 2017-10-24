<?php
#===============================================================================
# Get instances
#===============================================================================
$Database = Application::getDatabase();
$Language = Application::getLanguage();

#===============================================================================
# TRY: User\Exception
#===============================================================================
try {
	if(Application::get('USER.SLUG_URLS')) {
		$User = User\Factory::buildBySlug($param);
	}

	else {
		$User = User\Factory::build($param);
	}

	$user_data = generateItemTemplateData($User);

	#===============================================================================
	# Add user data for previous and next user
	#===============================================================================
	try {
		$PrevUser = User\Factory::build($User->getPrevID());
		$user_data['PREV'] = generateItemTemplateData($PrevUser);
	} catch(User\Exception $Exception){}

	try {
		$NextUser = User\Factory::build($User->getNextID());
		$user_data['NEXT'] = generateItemTemplateData($NextUser);
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
			'DESC' => description($user_data['BODY']['HTML'](), Application::get('USER.DESCRIPTION_SIZE')),
			'PERM' => $User->getURL(),
			'OG_IMAGES' => $User->getFiles()
		]);

		# Get access to the current item data from main template
		$MainTemplate->set('TYPE', 'USER');
		$MainTemplate->set('USER', $user_data);

		echo $MainTemplate;
	}

	#===============================================================================
	# CATCH: Template\Exception
	#===============================================================================
	catch(Template\Exception $Exception) {
		Application::exit($Exception->getMessage());
	}
}

#===============================================================================
# CATCH: User\Exception
#===============================================================================
catch(User\Exception $Exception) {
	try {
		if(Application::get('USER.SLUG_URLS') === FALSE) {
			$User = User\Factory::buildBySlug($param);
		} else {
			$User = User\Factory::build($param);
		}

		HTTP::redirect($User->getURL());
	}

	catch(User\Exception $Exception) {
		Application::error404();
	}
}
?>