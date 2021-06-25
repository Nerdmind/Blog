<?php
namespace ORM\Entities;
use ORM\Entity;

class Post extends Entity {
	protected $id   = FALSE;
	protected $user = FALSE;
	protected $slug = FALSE;
	protected $name = FALSE;
	protected $body = FALSE;
	protected $argv = FALSE;
	protected $time_insert = FALSE;
	protected $time_update = FALSE;
}
