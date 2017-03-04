<?php
namespace User;

class Factory extends \ItemFactory {
	public static function buildByUsername($username): \Item {
		return self::build(Item::getIDByField('username', $username, \Application::getDatabase()));
	}
}
?>