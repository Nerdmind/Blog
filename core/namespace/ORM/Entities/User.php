<?php
namespace ORM\Entities;
use ORM\Entity;

class User extends Entity {
	protected $slug     = FALSE;
	protected $username = FALSE;
	protected $password = FALSE;
	protected $fullname = FALSE;
	protected $mailaddr = FALSE;
	protected $body     = FALSE;
	protected $argv     = FALSE;
}
