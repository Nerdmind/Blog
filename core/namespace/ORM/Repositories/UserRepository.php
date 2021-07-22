<?php
namespace ORM\Repositories;
use ORM\Repository;
use ORM\Entities\User;

class UserRepository extends Repository {
	public static function getTableName(): string { return 'user'; }
	public static function getClassName(): string { return 'ORM\Entities\User'; }

	#===============================================================================
	# Get number of *pages* assigned to $User
	#===============================================================================
	public function getNumberOfPages(User $User): int {
		$query = 'SELECT COUNT(id) FROM %s WHERE user = ?';
		$query = sprintf($query, PageRepository::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$User->getID()]);

		return $Statement->fetchColumn();
	}

	#===============================================================================
	# Get number of *posts* assigned to $User
	#===============================================================================
	public function getNumberOfPosts(User $User): int {
		$query = 'SELECT COUNT(id) FROM %s WHERE user = ?';
		$query = sprintf($query, PostRepository::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$User->getID()]);

		return $Statement->fetchColumn();
	}
}
