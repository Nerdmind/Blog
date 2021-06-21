<?php
namespace User;

class Repository extends \Repository {
	public static function getTableName(): string { return 'user'; }
	public static function getClassName(): string { return 'User\Entity'; }
}
