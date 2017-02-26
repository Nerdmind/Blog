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
	$MainTemplate->set('NAME', '403 Forbidden');
	$MainTemplate->set('HEAD', [
		'NAME' => '403 Forbidden',
		'DESC' => "You don't have permission to access {$_SERVER['REQUEST_URI']} on this server."
	]);

	$MainTemplate->set('HTML', Template\Factory::build('403'));
	echo $MainTemplate;
}

#===============================================================================
# CATCH: Template\Exception
#===============================================================================
catch(Template\Exception $Exception) {
	$Exception->defaultHandler();
}
?>