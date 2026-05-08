<?php
namespace ORM\Repository;
use ORM\Repository;
use ORM\RepositorySearch;

class PageRepository extends Repository {
	use RepositorySearch;

	public static function getTableName(): string { return 'page'; }
	public static function getClassName(): string { return 'ORM\Entity\Page'; }
}
