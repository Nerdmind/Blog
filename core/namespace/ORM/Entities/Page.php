<?php
namespace ORM\Entities;
use ORM\Entity;

class Page extends Entity {
	protected $id   = FALSE;
	protected $user = FALSE;
	protected $slug = FALSE;
	protected $name = FALSE;
	protected $body = FALSE;
	protected $argv = FALSE;
	protected $time_insert = FALSE;
	protected $time_update = FALSE;
}
