<?php
namespace Category;

class Item extends \Item {
	const CONFIGURATION = 'CATEGORY';

	#===============================================================================
	# Return absolute category URL
	#===============================================================================
	public function getURL(): string {
		if(\Application::get('CATEGORY.SLUG_URLS')) {
			return \Application::getCategoryURL("{$this->Attribute->get('slug')}/");
		}

		return \Application::getCategoryURL("{$this->Attribute->get('id')}/");
	}

	#===============================================================================
	# Return unique pseudo GUID
	#===============================================================================
	public function getGUID(): string {
		foreach(\Application::get('CATEGORY.FEED_GUID') as $attribute) {
			$attributes[] = $this->Attribute->get($attribute);
		}

		return sha1(implode(NULL, $attributes));
	}
}
?>