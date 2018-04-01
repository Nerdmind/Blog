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

	#===============================================================================
	# Return search results as Page\Attribute
	#===============================================================================
	public static function getSearchResults($search, \Database $Database): array {
		$Statement = $Database->prepare(sprintf("SELECT * FROM %s WHERE
			MATCH(name, body) AGAINST(? IN BOOLEAN MODE) LIMIT 20", Attribute::TABLE));

		if($Statement->execute([$search])) {
			return $Statement->fetchAll($Database::FETCH_CLASS, 'Page\Attribute');
		}

		return [];
	}

	#===============================================================================
	# Return associated User\Attribute
	#===============================================================================
	public function getUserAttribute() {
		$Statement = $this->Database->query(sprintf('SELECT * FROM user WHERE id = %d', $this->Attribute->get('user')));
		return $Statement->fetchObject('User\Attribute');
	}
}
?>