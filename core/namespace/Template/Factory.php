<?php
namespace Template;

class Factory extends \Factory implements \FactoryInterface {
	public static function build($template): Template {
		$Template = new Template(ROOT.'theme/'.\Application::get('TEMPLATE.NAME')."/html/{$template}.php");
		$Template->set('Language', \Application::getLanguage());
		$Template->set('BLOGMETA', [
			'NAME' => \Application::get('BLOGMETA.NAME'),
			'DESC' => \Application::get('BLOGMETA.DESC'),
			'MAIL' => \Application::get('BLOGMETA.MAIL'),
			'LANG' => \Application::get('BLOGMETA.LANG'),
		]);

		return $Template;
	}
}
?>