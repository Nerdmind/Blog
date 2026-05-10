<?php

$language = Application::getLanguage();

if(Application::isAuthenticated()) {
	if(HTTP::issetGET(['token' => Application::getSecurityToken()], ['action' => 'logout'])) {
		session_destroy();
		HTTP::redirect(Application::getAdminURL('auth.php'));
	}

	HTTP::redirect(Application::getAdminURL());
}

if(HTTP::issetPOST('username', 'password')) {
	if(HTTP::issetPOST(['token' => Application::getSecurityToken()])) {
		$userRepository = Application::getRepository('User');

		if($user = $userRepository->findBy('username', HTTP::POST('username'))) {
			if(password_verify(HTTP::POST('password'), $user->get('password'))) {
				$_SESSION['USER_ID'] = $user->getID();
				HTTP::redirect(Application::getAdminURL());
			} else {
				$messages[] = $language->text('authentication_failure');
			}
		} else {
			$fakeHash = '$2y$10$xpnwDU2HumOgGQhVpMOP9uataEF82YXizniFhSUhYjUiXF8aoDk0C';
			$fakePass = HTTP::POST('password');

			password_verify($fakePass, $fakeHash);

			$messages[] = $language->text('authentication_failure');
		}
	} else {
		$messages[] = $language->text('error_security_csrf');
	}
}

$loginTemplate = Template\Factory::build('auth');
$loginTemplate->set('FORM', [
	'INFO' => $messages ?? [],
	'DATA' => [
		'USERNAME' => HTTP::POST('username'),
		'PASSWORD' => HTTP::POST('password'),
	],
	'TOKEN' => Application::getSecurityToken()
]);

$template = Template\Factory::build('main');
$template->set('NAME', 'Login');
$template->set('HTML', $loginTemplate);

echo $template;
