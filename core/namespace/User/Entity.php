<?php
namespace User;

class Entity extends \Entity {
	protected $id       = FALSE;
	protected $slug     = FALSE;
	protected $username = FALSE;
	protected $password = FALSE;
	protected $fullname = FALSE;
	protected $mailaddr = FALSE;
	protected $body     = FALSE;
	protected $argv     = FALSE;
	protected $time_insert = FALSE;
	protected $time_update = FALSE;
}
