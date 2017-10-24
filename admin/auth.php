<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
define('ADMINISTRATION', TRUE);

#===============================================================================
# INCLUDE: Initialization
#===============================================================================
require '../core/application.php';

#===============================================================================
# IF: Already authenticated
#===============================================================================
if(Application::isAuthenticated()) {
	#===============================================================================
	# IF: Logout action
	#===============================================================================
	if(HTTP::issetGET(['token' => Application::getSecurityToken(), ['action' => 'logout']])) {
		session_destroy();
		HTTP::redirect(Application::getAdminURL('auth.php'));
	}

	HTTP::redirect(Application::getAdminURL());
}

#===============================================================================
# IF: Login action
#===============================================================================
if(HTTP::issetPOST(['token' => Application::getSecurityToken()], 'username', 'password')) {
	try {
		$User = User\Factory::buildByUsername(HTTP::POST('username'));

		if($User->comparePassword(HTTP::POST('password'))) {
			$_SESSION['auth'] = $User->getID();
			HTTP::redirect(Application::getAdminURL());
		}

		else {
			$messages[] = $Language->text('authentication_failure');
		}
	} catch(User\Exception $Exception){
		$fake_hash = '$2y$10$xpnwDU2HumOgGQhVpMOP9uataEF82YXizniFhSUhYjUiXF8aoDk0C';
		$fake_pass = HTTP::POST('password');

		password_verify($fake_pass, $fake_hash);

		$messages[] = $Language->text('authentication_failure');
	}
}

#===============================================================================
# TRY: Template\Exception
#===============================================================================
try {
	$AuthTemplate = Template\Factory::build('auth');
	$AuthTemplate->set('FORM', [
		'INFO' => $messages ?? [],
		'DATA' => [
			'USERNAME' => HTTP::POST('username'),
			'PASSWORD' => HTTP::POST('password'),
		],
		'TOKEN' => Application::getSecurityToken()
	]);

	$MainTemplate = Template\Factory::build('main');
	$MainTemplate->set('NAME', 'Authentication');
	$MainTemplate->set('HTML', $AuthTemplate);
	echo $MainTemplate;
}

#===============================================================================
# CATCH: Template\Exception
#===============================================================================
catch(Template\Exception $Exception) {
	Application::exit($Exception->getMessage());
}
?>