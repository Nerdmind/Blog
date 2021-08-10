<?php
namespace ORM\Entities;
use ORM\Entity;

class User extends Entity {
	protected $slug;
	protected $username;
	protected $password;
	protected $fullname;
	protected $mailaddr;
	protected $body;
	protected $argv;
}
