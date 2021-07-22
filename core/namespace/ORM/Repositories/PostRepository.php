<?php
namespace ORM\Repositories;
use ORM\Repository;
use ORM\RepositorySearch;

class PostRepository extends Repository {
	use RepositorySearch;

	public static function getTableName(): string { return 'post'; }
	public static function getClassName(): string { return 'ORM\Entities\Post'; }
}
