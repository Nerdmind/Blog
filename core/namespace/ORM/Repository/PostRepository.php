<?php
namespace ORM\Repository;

class PostRepository extends Repository {
	use RepositorySearch;

	public static function getTableName(): string { return 'post'; }
	public static function getClassName(): string { return 'ORM\Entity\Post'; }
}
