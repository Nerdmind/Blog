<?php
namespace ORM\Entities;
use ORM\Entity;

class Post extends Entity {
	protected $user     = FALSE;
	protected $category = FALSE;
	protected $slug     = FALSE;
	protected $name     = FALSE;
	protected $body     = FALSE;
	protected $argv     = FALSE;
}
