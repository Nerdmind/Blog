<?php
namespace ORM\Repositories;
use ORM\Repository;
use ORM\Entities\User;
use ORM\Entities\Category;

class Post extends Repository {
	public static function getTableName(): string { return 'post'; }
	public static function getClassName(): string { return 'ORM\Entities\Post'; }

	public function getCountByUser(User $User): int {
		$query = 'SELECT COUNT(id) FROM %s WHERE user = ?';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$User->getID()]);

		return $Statement->fetchColumn();
	}

	# TODO: This only gets the count of the direct category, not its children
	public function getCountByCategory(Category $Category): int {
		$query = 'SELECT COUNT(id) FROM %s WHERE category = ?';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$Category->getID()]);

		return $Statement->fetchColumn();
	}
}
