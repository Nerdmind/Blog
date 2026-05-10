<?php
namespace Template;

class Factory extends \Factory implements \FactoryInterface {
	public static function build($template): Template {
		$template = new Template(ROOT.'theme/'.\Application::get('TEMPLATE.NAME')."/html/{$template}.php");
		$template->set('Language', \Application::getLanguage());
		$template->set('BLOGMETA', [
			'NAME' => \Application::get('BLOGMETA.NAME'),
			'DESC' => \Application::get('BLOGMETA.DESC'),
			'MAIL' => \Application::get('BLOGMETA.MAIL'),
			'LANG' => \Application::get('BLOGMETA.LANG'),
		]);

		return $template;
	}
}
