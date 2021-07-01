<?php
namespace ORM\Entities;
use ORM\Entity;

class Category extends Entity {
	protected $id     = FALSE;
	protected $parent = FALSE;
	protected $slug   = FALSE;
	protected $name   = FALSE;
	protected $body   = FALSE;
	protected $argv   = FALSE;
	protected $time_insert = FALSE;
	protected $time_update = FALSE;
}
