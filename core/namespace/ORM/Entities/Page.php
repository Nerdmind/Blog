<?php
namespace ORM\Entities;
use ORM\Entity;

class Page extends Entity {
	protected $user = FALSE;
	protected $slug = FALSE;
	protected $name = FALSE;
	protected $body = FALSE;
	protected $argv = FALSE;
}
