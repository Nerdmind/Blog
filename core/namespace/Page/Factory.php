<?php
namespace Page;

class Factory extends \ItemFactory {
	public static function buildBySlug($slug): \Item {
		return self::build(Item::getIDByField('slug', $slug, \Application::getDatabase()));
	}
}
?>