<?php
namespace Page;

class Repository extends \Repository {
	public static function getTableName(): string { return 'page'; }
	public static function getClassName(): string { return 'Page\Entity'; }

	public function getCountByUser(\User\Entity $User): int {
		$query = 'SELECT COUNT(id) FROM %s WHERE user = ?';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$User->getID()]);

		return $Statement->fetchColumn();
	}
}
