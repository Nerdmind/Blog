<?php
namespace ORM\Repositories;
use ORM\Repository;
use ORM\Entities\User as UserEntity;

class User extends Repository {
	public static function getTableName(): string { return 'user'; }
	public static function getClassName(): string { return 'ORM\Entities\User'; }

	#===============================================================================
	# Get number of *pages* assigned to $User
	#===============================================================================
	public function getNumberOfPages(UserEntity $User): int {
		$query = 'SELECT COUNT(id) FROM %s WHERE user = ?';
		$query = sprintf($query, Page::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$User->getID()]);

		return $Statement->fetchColumn();
	}

	#===============================================================================
	# Get number of *posts* assigned to $User
	#===============================================================================
	public function getNumberOfPosts(UserEntity $User): int {
		$query = 'SELECT COUNT(id) FROM %s WHERE user = ?';
		$query = sprintf($query, Post::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$User->getID()]);

		return $Statement->fetchColumn();
	}
}
