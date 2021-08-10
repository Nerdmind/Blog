<?php
namespace ORM\Entities;
use ORM\Entity;

class Post extends Entity {
	protected $user;
	protected $category;
	protected $slug;
	protected $name;
	protected $body;
	protected $argv;
}
