<?php
namespace ORM\Repositories;
use ORM\Repository;

class User extends Repository {
	public static function getTableName(): string { return 'user'; }
	public static function getClassName(): string { return 'ORM\Entities\User'; }
}
