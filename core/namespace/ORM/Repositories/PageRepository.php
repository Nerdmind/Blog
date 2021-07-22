<?php
namespace ORM\Repositories;
use ORM\Repository;

class PageRepository extends Repository {
	public static function getTableName(): string { return 'page'; }
	public static function getClassName(): string { return 'ORM\Entities\Page'; }
}
