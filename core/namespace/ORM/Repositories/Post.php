<?php
namespace ORM\Repositories;
use ORM\Repository;
use ORM\Entities\User;
use ORM\Entities\Category;

class Post extends Repository {
	public static function getTableName(): string { return 'post'; }
	public static function getClassName(): string { return 'ORM\Entities\Post'; }
}
