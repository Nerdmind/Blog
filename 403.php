<?php
#===============================================================================
# INCLUDE: Main configuration
#===============================================================================
if(!defined('ROOT')) {
	require_once 'core/application.php';
}

#===============================================================================
# Send HTTP status code
#===============================================================================
http_response_code(403);

#===============================================================================
# TRY: Template\Exception
#===============================================================================
try {
	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', '403 Forbidden');
	$MainTemplate->set('HEAD', ['NAME' => '403 Forbidden']);
	$MainTemplate->set('HTML', Template\Factory::build('403'));

	echo $MainTemplate;
}

#===============================================================================
# CATCH: Template\Exception
#===============================================================================
catch(Template\Exception $Exception) {
	Application::exit($Exception->getMessage());
}
?>