<?php
namespace Post;

class Item extends \Item {
	const CONFIGURATION = 'POST';

	#===============================================================================
	# Return absolute post URL
	#===============================================================================
	public function getURL(): string {
		if(\Application::get('POST.SLUG_URLS')) {
			return \Application::getPostURL("{$this->Attribute->get('slug')}/");
		}

		return \Application::getPostURL("{$this->Attribute->get('id')}/");
	}

	#===============================================================================
	# Return unique pseudo GUID
	#===============================================================================
	public function getGUID(): string {
		foreach(\Application::get('POST.FEED_GUID') as $attribute) {
			$attributes[] = $this->Attribute->get($attribute);
		}

		return sha1(implode(NULL, $attributes));
	}

	#===============================================================================
	# Return unique post IDs for search results
	#===============================================================================
	public static function getSearchResultIDs($search, array $date, \Database $Database): array {
		$D = ($D = intval($date[0])) !== 0 ? $D : 'NULL';
		$M = ($M = intval($date[1])) !== 0 ? $M : 'NULL';
		$Y = ($Y = intval($date[2])) !== 0 ? $Y : 'NULL';

		$Statement = $Database->prepare(sprintf("SELECT id FROM %s WHERE 
			({$Y} IS NULL OR YEAR(time_insert) = {$Y})  AND
			({$M} IS NULL OR MONTH(time_insert) = {$M}) AND
			({$D} IS NULL OR DAY(time_insert) = {$D})   AND
			MATCH(name, body) AGAINST(? IN BOOLEAN MODE) LIMIT 20", Attribute::TABLE));

		if($Statement->execute([$search])) {
			return $Statement->fetchAll($Database::FETCH_COLUMN);
		}

		return [];
	}
}
