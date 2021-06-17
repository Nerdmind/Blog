<?php
#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require_once 'core/application.php';

#===============================================================================
# Send HTTP status code
#===============================================================================
http_response_code(404);

$MainTemplate = Template\Factory::build('main');
$MainTemplate->set('NAME', '404 Not Found');
$MainTemplate->set('HEAD', ['NAME' => $MainTemplate->get('NAME')]);
$MainTemplate->set('HTML', Template\Factory::build('404'));

echo $MainTemplate;
