<?php
namespace ORM\Repository;
use ORM\Entity\User;

class UserRepository extends Repository {
	public static function getTableName(): string { return 'user'; }
	public static function getClassName(): string { return 'ORM\Entity\User'; }

	#===============================================================================
	# Get number of *pages* assigned to $user
	#===============================================================================
	public function getNumberOfPages(User $user): int {
		$query = 'SELECT COUNT(id) FROM %s WHERE user = ?';
		$query = sprintf($query, PageRepository::getTableName());

		$statement = $this->database->prepare($query);
		$statement->execute([$user->getID()]);

		return $statement->fetchColumn();
	}

	#===============================================================================
	# Get number of *posts* assigned to $user
	#===============================================================================
	public function getNumberOfPosts(User $user): int {
		$query = 'SELECT COUNT(id) FROM %s WHERE user = ?';
		$query = sprintf($query, PostRepository::getTableName());

		$statement = $this->database->prepare($query);
		$statement->execute([$user->getID()]);

		return $statement->fetchColumn();
	}
}
