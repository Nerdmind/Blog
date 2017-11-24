<?php
#===============================================================================
# Get instances
#===============================================================================
$Database = Application::getDatabase();
$Language = Application::getLanguage();

#===============================================================================
# TRY: Category\Exception, User\Exception
#===============================================================================
try {
    if(Application::get('CATEGORY.SLUG_URLS')) {
        $Category = Category\Factory::buildBySlug($param);
    } else {
        $Category = Category\Factory::build($param);
    }

    $execSQL = 'SELECT id FROM %s WHERE category_id = %s ORDER BY '.Application::get('POST.LIST_SORT').' LIMIT '.Application::get('POST.LIST_SIZE');
    $Statement = $Database->query(sprintf($execSQL, Post\Attribute::TABLE, $Category->attr('id')));


    $postIDs = $Statement->fetchAll($Database::FETCH_COLUMN);

    foreach($postIDs as $postID) {
        try {
            $Post = Post\Factory::build($postID);
            $User = User\Factory::build($Post->attr('user'));

	        $category_data = generateItemTemplateData($Category);
            $ItemTemplate = generatePostItemTemplate($Post, $User);

            $posts[] = $ItemTemplate;
        }
        catch(Post\Exception $Exception){}
        catch(User\Exception $Exception){}
    }

	#===============================================================================
	# TRY: Template\Exception
	#===============================================================================
	try {
        $CategoryTemplate = Template\Factory::build('category/main');
        $CategoryTemplate->set('PAGINATION', [
            'HTML' => generateCategoryNaviTemplate(1)
        ]);
        $CategoryTemplate->set('CATEGORY', $category_data);
        $CategoryTemplate->set('LIST', [
            'POSTS' => $posts ?? []
        ]);

        $MainTemplate = Template\Factory::build('main');
        $MainTemplate->set('HTML', $CategoryTemplate);
        $MainTemplate->set('HEAD', [
            'NAME' => $category_data['ATTR']['NAME'],
            'DESC' => description($category_data['BODY']['HTML'](), Application::get('CATEGORY.DESCRIPTION_SIZE')),
            'PERM' => $category_data['URL']
        ]);

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
# CATCH: Category\Exception
#===============================================================================
catch(Category\Exception $Exception) {
	try {
		if(Application::get('CATEGORY.SLUG_URLS') === FALSE) {
			$Category = Category\Factory::buildBySlug($param);
		} else {
			$Category = Category\Factory::build($param);
		}

		HTTP::redirect($Category->getURL());
	}

	catch(Category\Exception $Exception) {
		Application::error404();
	}
}

#===============================================================================
# CATCH: User\Exception
#===============================================================================
catch(User\Exception $Exception) {
	Application::exit($Exception->getMessage());
}