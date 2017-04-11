<?php
#===============================================================================
# Get instances
#===============================================================================
$Database = Application::getDatabase();
$Language = Application::getLanguage();

$SEARCH_SUCCESS = FALSE;
$D_LIST = $Database->query(sprintf('SELECT DISTINCT DAY(time_insert) AS temp FROM %s ORDER BY temp', Post\Attribute::TABLE));
$M_LIST = $Database->query(sprintf('SELECT DISTINCT MONTH(time_insert) AS temp FROM %s ORDER BY temp', Post\Attribute::TABLE));
$Y_LIST = $Database->query(sprintf('SELECT DISTINCT YEAR(time_insert) AS temp FROM %s ORDER BY temp', Post\Attribute::TABLE));

if($search = HTTP::GET('q')) {
	if(!$postIDs = Post\Item::getSearchResultIDs($search, [HTTP::GET('d'), HTTP::GET('m'), HTTP::GET('y')], $Database)) {
		$message = $Language->text('search_no_results', escapeHTML($search));
	}
}

$form_data = [
	'SELECT' => [
		'D' => HTTP::GET('d'),
		'M' => HTTP::GET('m'),
		'Y' => HTTP::GET('y'),
	],
	'OPTIONS' => [
		'D' => $D_LIST->fetchAll($Database::FETCH_COLUMN),
		'M' => $M_LIST->fetchAll($Database::FETCH_COLUMN),
		'Y' => $Y_LIST->fetchAll($Database::FETCH_COLUMN),
	]
];

$search_data = [
	'TEXT' => $search,
	'INFO' => isset($message) ? $message : FALSE,
];

#===============================================================================
# TRY: Template\Exception
#===============================================================================
try {
	if(isset($postIDs) AND !empty($postIDs)) {
		foreach($postIDs as $postID) {
			try {
				$Post = Post\Factory::build($postID);
				$User = User\Factory::build($Post->attr('user'));

				$posts[] = generatePostItemTemplate($Post, $User);
			}
			catch(Post\Exception $Exception){}
			catch(User\Exception $Exception){}
		}

		$ResultTemplate = Template\Factory::build('search/result');
		$ResultTemplate->set('FORM', $form_data);
		$ResultTemplate->set('SEARCH', $search_data);
		$ResultTemplate->set('RESULT', [
			'LIST' => $posts ?? []
		]);

		$MainTemplate = Template\Factory::build('main');
		$MainTemplate->set('HTML', $ResultTemplate);
		$MainTemplate->set('HEAD', [
			'NAME' => $Language->text('title_search_results', escapeHTML($search)),
			'PERM' => Application::getURL('search/')
		]);
	}

	else {
		$SearchTemplate = Template\Factory::build('search/main');
		$SearchTemplate->set('FORM', $form_data);
		$SearchTemplate->set('SEARCH', $search_data);

		$MainTemplate = Template\Factory::build('main');
		$MainTemplate->set('HTML', $SearchTemplate);
		$MainTemplate->set('HEAD', [
			'NAME' => $Language->text('title_search_request'),
			'PERM' => Application::getURL('search/')
		]);
	}

	echo $MainTemplate;
}

#===============================================================================
# CATCH: Template\Exception
#===============================================================================
catch(Template\Exception $Exception) {
	Application::exit($Exception->getMessage());
}
?>