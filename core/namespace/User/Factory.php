<?php
namespace User;

class Factory extends \ItemFactory {
	public static function buildBySlug($slug): \Item {
		return self::build(Item::getIDByField('slug', $slug, \Application::getDatabase()));
	}

	public static function buildByUsername($username): \Item {
		return self::build(Item::getIDByField('username', $username, \Application::getDatabase()));
	}
}
?>