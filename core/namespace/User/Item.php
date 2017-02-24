<?php
namespace User;

class Item extends \Item {
	const CONFIGURATION = 'USER';

	#===============================================================================
	# Return absolute user URL
	#===============================================================================
	public function getURL(): string {
		if(\Application::get('USER.SLUG_URLS')) {
			return \Application::getUserURL("{$this->Attribute->get('slug')}/");
		}

		return \Application::getUserURL("{$this->Attribute->get('id')}/");
	}

	#===============================================================================
	# Return unique pseudo GUID
	#===============================================================================
	public function getGUID(): string {
		foreach(['id', 'time_insert'] as $attribute) {
			$attributes[] = $this->Attribute->get($attribute);
		}

		return sha1(implode(NULL, $attributes));
	}

	#===============================================================================
	# Compare plaintext password with hashed password from database
	#===============================================================================
	public function comparePassword($password): bool {
		return password_verify($password, $this->Attribute->get('password'));
	}
}
?>