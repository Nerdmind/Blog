<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
define('ADMINISTRATION', TRUE);
define('AUTHENTICATION', TRUE);

#===============================================================================
# INCLUDE: Main configuration
#===============================================================================
require '../core/application.php';

#===============================================================================
# TRY: PDOException
#===============================================================================
try {
	$LastPageStatement = $Database->query(sprintf('SELECT id FROM %s ORDER BY time_insert DESC LIMIT 1', Page\Attribute::TABLE));
	$LastPostStatement = $Database->query(sprintf('SELECT id FROM %s ORDER BY time_insert DESC LIMIT 1', Post\Attribute::TABLE));
	$LastUserStatement = $Database->query(sprintf('SELECT id FROM %s ORDER BY time_insert DESC LIMIT 1', User\Attribute::TABLE));

	$PageCountStatement = $Database->query(sprintf('SELECT COUNT(*) FROM %s', Page\Attribute::TABLE));
	$PostCountStatement = $Database->query(sprintf('SELECT COUNT(*) FROM %s', Post\Attribute::TABLE));
	$UserCountStatement = $Database->query(sprintf('SELECT COUNT(*) FROM %s', User\Attribute::TABLE));
}

#===============================================================================
# CATCH: PDOException
#===============================================================================
catch(PDOException $Exception) {
	exit($Exception->getMessage());
}

#===============================================================================
# TRY: Template\Exception
#===============================================================================
try {
	try {
		$LastPage = Page\Factory::build($LastPageStatement->fetchColumn());
		$LastPageUser = User\Factory::build($LastPage->attr('user'));

		$PageItemTemplate = generatePageItemTemplate($LastPage, $LastPageUser);
	}

	catch(Page\Exception $Exception){}
	catch(User\Exception $Exception){}

	try {
		$LastPost = Post\Factory::build($LastPostStatement->fetchColumn());
		$LastPostUser = User\Factory::build($LastPost->attr('user'));

		$PostItemTemplate = generatePostItemTemplate($LastPost, $LastPostUser);
	}

	catch(Post\Exception $Exception){}
	catch(User\Exception $Exception){}

	try {
		$LastUser = User\Factory::build($LastUserStatement->fetchColumn());
		$UserItemTemplate = generateUserItemTemplate($LastUser);
	} catch(User\Exception $Exception){}

	$HomeTemplate = Template\Factory::build('home');
	$HomeTemplate->set('LAST', [
		'PAGE' => $PageItemTemplate ?? FALSE,
		'POST' => $PostItemTemplate ?? FALSE,
		'USER' => $UserItemTemplate ?? FALSE,

	]);

	$HomeTemplate->set('COUNT', [
		'PAGE' => $PageCountStatement->fetchColumn(),
		'POST' => $PostCountStatement->fetchColumn(),
		'USER' => $UserCountStatement->fetchColumn(),
	]);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', 'Dashboard');
	$MainTemplate->set('HTML', $HomeTemplate);
	echo $MainTemplate;
}

#===============================================================================
# CATCH: Template\Exception
#===============================================================================
catch(Template\Exception $Exception) {
	Application::exit($Exception->getMessage());
}
?>