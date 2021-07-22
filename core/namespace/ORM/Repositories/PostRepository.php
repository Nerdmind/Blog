<?php
namespace ORM\Repositories;
use ORM\Repository;

class PostRepository extends Repository {
	public static function getTableName(): string { return 'post'; }
	public static function getClassName(): string { return 'ORM\Entities\Post'; }
}
