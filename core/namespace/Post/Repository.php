<?php
namespace Post;

class Repository extends \Repository {
	public static function getTableName(): string { return 'post'; }
	public static function getClassName(): string { return 'Post\Entity'; }

	public function getCountByUser(\User\Entity $User): int {
		$query = 'SELECT COUNT(id) FROM %s WHERE user = ?';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$User->getID()]);

		return $Statement->fetchColumn();
	}
}
