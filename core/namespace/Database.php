<?php
class Database extends \PDO {
	public function __construct($hostname, $basename, $username, $password) {
		parent::__construct("mysql:host={$hostname};dbname={$basename};charset=utf8mb4;", $username, $password);
	}
}
