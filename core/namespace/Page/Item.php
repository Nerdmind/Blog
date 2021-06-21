<?php
namespace Page;

class Item extends \Item {
	const CONFIGURATION = 'PAGE';

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
