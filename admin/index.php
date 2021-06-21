<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
define('ADMINISTRATION', TRUE);
define('AUTHENTICATION', TRUE);

#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require '../core/application.php';

#===============================================================================
# Fetch last items and item count
#===============================================================================
$execute = 'SELECT id FROM %s ORDER BY time_insert DESC LIMIT 1';
$LastPageStatement = $Database->query(sprintf($execute, Page\Attribute::TABLE));
$LastPostStatement = $Database->query(sprintf($execute, Post\Attribute::TABLE));
$LastUserStatement = $Database->query(sprintf($execute, User\Attribute::TABLE));

$execute = 'SELECT COUNT(*) FROM %s';
$PageCountStatement = $Database->query(sprintf($execute, Page\Attribute::TABLE));
$PostCountStatement = $Database->query(sprintf($execute, Post\Attribute::TABLE));
$UserCountStatement = $Database->query(sprintf($execute, User\Attribute::TABLE));

try {
	$LastPage = Page\Factory::build($LastPageStatement->fetchColumn());
	$LastPageUser = User\Factory::build($LastPage->get('user'));

	$PageItemTemplate = generatePageItemTemplate($LastPage, $LastPageUser);
}

catch(Page\Exception $Exception){}
catch(User\Exception $Exception){}

try {
	$LastPost = Post\Factory::build($LastPostStatement->fetchColumn());
	$LastPostUser = User\Factory::build($LastPost->get('user'));

	$PostItemTemplate = generatePostItemTemplate($LastPost, $LastPostUser);
}

catch(Post\Exception $Exception){}
catch(User\Exception $Exception){}

try {
	$LastUser = User\Factory::build($LastUserStatement->fetchColumn());
	$UserItemTemplate = generateUserItemTemplate($LastUser);
} catch(User\Exception $Exception){}

#===============================================================================
# Build document
#===============================================================================
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
