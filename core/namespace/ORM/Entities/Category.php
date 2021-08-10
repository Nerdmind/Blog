<?php
namespace ORM\Entities;
use ORM\Entity;

class Category extends Entity {
	protected $parent;
	protected $slug;
	protected $name;
	protected $body;
	protected $argv;
}
