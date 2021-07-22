<?php
namespace ORM\Entities;
use ORM\Entity;

class Category extends Entity {
	protected $parent = FALSE;
	protected $slug   = FALSE;
	protected $name   = FALSE;
	protected $body   = FALSE;
	protected $argv   = FALSE;
}
