<?php
namespace ORM\Entities;
use ORM\Entity;

class Page extends Entity {
	protected $user;
	protected $slug;
	protected $name;
	protected $body;
	protected $argv;
}
