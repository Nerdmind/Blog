<?php
namespace Page;

class Item extends \Item {
	const CONFIGURATION = 'PAGE';

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
	# Return unique page IDs for search results
	#===============================================================================
	public static function getSearchResultIDs($search, \Database $Database): array {
		$Statement = $Database->prepare(sprintf("SELECT id FROM %s WHERE
			MATCH(name, body) AGAINST(? IN BOOLEAN MODE) LIMIT 20", Attribute::TABLE));

		if($Statement->execute([$search])) {
			return $Statement->fetchAll($Database::FETCH_COLUMN);
		}

		return [];
	}
}
