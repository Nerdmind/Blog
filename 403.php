<?php
#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require_once 'core/application.php';

#===============================================================================
# Send HTTP status code
#===============================================================================
http_response_code(403);

#===============================================================================
# Build document
#===============================================================================
$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', '403 Forbidden');
$MainTemplate->set('HEAD', ['NAME' => $MainTemplate->get('NAME')]);
$MainTemplate->set('HTML', Template\Factory::build('403'));

echo $MainTemplate;
