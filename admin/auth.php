<?php
#===============================================================================
# DEFINE: Administration
#===============================================================================
const ADMINISTRATION = TRUE;

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
	if(HTTP::issetGET(['token' => Application::getSecurityToken()], ['action' => 'logout'])) {
		session_destroy();
		HTTP::redirect(Application::getAdminURL('auth.php'));
	}

	HTTP::redirect(Application::getAdminURL());
}

#===============================================================================
# IF: Login action
#===============================================================================
if(HTTP::issetPOST('username', 'password')) {
	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		$UserRepository = Application::getRepository('User');

		if($User = $UserRepository->findBy('username', HTTP::POST('username'))) {
			if(password_verify(HTTP::POST('password'), $User->get('password'))) {
				$_SESSION['USER_ID'] = $User->getID();
				HTTP::redirect(Application::getAdminURL());
			} else {
				$messages[] = $Language->text('authentication_failure');
			}
		} else {
			$fake_hash = '$2y$10$xpnwDU2HumOgGQhVpMOP9uataEF82YXizniFhSUhYjUiXF8aoDk0C';
			$fake_pass = HTTP::POST('password');

			password_verify($fake_pass, $fake_hash);

			$messages[] = $Language->text('authentication_failure');
		}
	} else {
		$messages[] = $Language->text('error_security_csrf');
	}
}

#===============================================================================
# Build document
#===============================================================================
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
