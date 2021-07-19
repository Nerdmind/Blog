<?php
namespace ORM\Repositories;
use ORM\Repository;
use ORM\Entities\User;

class Page extends Repository {
	public static function getTableName(): string { return 'page'; }
	public static function getClassName(): string { return 'ORM\Entities\Page'; }
}
