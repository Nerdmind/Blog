<?php
#===============================================================================
# INCLUDE: Main configuration
#===============================================================================
if(!defined('ROOT')) {
	require_once '../core/application.php';
}

#===============================================================================
# TRY: Template\Exception
#===============================================================================
try {
	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', '404 Not Found');
	$MainTemplate->set('HEAD', ['NAME' => '404 Not Found']);
	$MainTemplate->set('HTML', Template\Factory::build('404'));

	echo $MainTemplate;
}

#===============================================================================
# CATCH: Template\Exception
#===============================================================================
catch(Template\Exception $Exception) {
	$Exception->defaultHandler();
}
?>