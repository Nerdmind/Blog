<?php
namespace Page;

class Item extends \Item {
	const CONFIGURATION = 'PAGE';

	#===============================================================================
	# Return absolute page URL
	#===============================================================================
	public function getURL(): string {
		if(\Application::get('PAGE.SLUG_URLS')) {
			return \Application::getPageURL("{$this->Attribute->get('slug')}/");
		}

		return \Application::getPageURL("{$this->Attribute->get('id')}/");
	}

	#===============================================================================
	# Return unique pseudo GUID
	#===============================================================================
	public function getGUID(): string {
		foreach(\Application::get('PAGE.FEED_GUID') as $attribute) {
			$attributes[] = $this->Attribute->get($attribute);
		}

		return sha1(implode(NULL, $attributes));
	}
}
?>