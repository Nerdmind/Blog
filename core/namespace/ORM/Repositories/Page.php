<?php
namespace ORM\Repositories;
use ORM\Repository;
use ORM\Entities\User;

class Page extends Repository {
	public static function getTableName(): string { return 'page'; }
	public static function getClassName(): string { return 'ORM\Entities\Page'; }

	public function getCountByUser(User $User): int {
		$query = 'SELECT COUNT(id) FROM %s WHERE user = ?';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$User->getID()]);

		return $Statement->fetchColumn();
	}
}
