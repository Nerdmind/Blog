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
# TRY: Post\Exception
#===============================================================================
try {
	$Post = Post\Factory::build(HTTP::GET('id'));
	$Attribute = $Post->getAttribute();

	if(HTTP::issetPOST('user', 'slug', 'name', 'body', 'argv', 'time_insert', 'time_update', 'update', 'archive', 'category')) {
		$Attribute->set('user', HTTP::POST('user'));
		$Attribute->set('slug', HTTP::POST('slug') ? HTTP::POST('slug') : generateSlug(HTTP::POST('name')));
		$Attribute->set('name', HTTP::POST('name') ? HTTP::POST('name') : NULL);
		$Attribute->set('body', HTTP::POST('body') ? HTTP::POST('body') : NULL);
		$Attribute->set('argv', HTTP::POST('argv') ? HTTP::POST('argv') : NULL);
        $Attribute->set('archive', HTTP::POST('archive') ? HTTP::POST('archive') : 0);
        $Attribute->set('category_id', HTTP::POST('category') ? HTTP::POST('category') : 0);
		$Attribute->set('time_insert', HTTP::POST('time_insert') ?: date('Y-m-d H:i:s'));
		$Attribute->set('time_update', HTTP::POST('time_update') ?: date('Y-m-d H:i:s'));

		if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
			try {
				$Attribute->databaseUPDATE($Database);
			} catch(PDOException $Exception) {
				$messages[] = $Exception->getMessage();
			}
		}

		else {
			$messages[] = $Language->text('error_security_csrf');
		}
	}

	#===============================================================================
	# TRY: Template\Exception
	#===============================================================================
	try {
		$userIDs = $Database->query(sprintf('SELECT id FROM %s ORDER BY fullname ASC', User\Attribute::TABLE));

		foreach($userIDs->fetchAll($Database::FETCH_COLUMN) as $userID) {
			$User = User\Factory::build($userID);
			$userAttributes[] = [
				'ID' => $User->attr('id'),
				'FULLNAME' => $User->attr('fullname'),
				'USERNAME' => $User->attr('username'),
			];
		}

        $categoryIDs = $Database->query(sprintf('SELECT id FROM %s ORDER BY name ASC', Category\Attribute::TABLE));

        foreach ($categoryIDs->fetchAll($Database::FETCH_COLUMN) as $categoryID) {
            $Category = Category\Factory::build($categoryID);
            $categoryAttributes[] = [
                'ID' => $Category->attr('id'),
                'NAME' => $Category->attr('name'),
                'SLUG' => $Category->attr('slug'),
            ];
        }

		$FormTemplate = Template\Factory::build('post/form');
		$FormTemplate->set('FORM', [
			'TYPE' => 'UPDATE',
			'INFO' => $messages ?? [],
			'DATA' => array_change_key_case($Attribute->getAll(), CASE_UPPER),
			'USER_LIST' => $userAttributes ??  [],
            'CATEGORY_LIST' => $categoryAttributes ?? [],
			'TOKEN' => Application::getSecurityToken()
		]);

		$PostUpdateTemplate = Template\Factory::build('post/update');
		$PostUpdateTemplate->set('HTML', $FormTemplate);

		$MainTemplate = Template\Factory::build('main');
		$MainTemplate->set('NAME', $Language->text('title_post_update'));
		$MainTemplate->set('HTML', $PostUpdateTemplate);
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
# CATCH: Post\Exception
#===============================================================================
catch(Post\Exception $Exception) {
	Application::error404();
}
?>