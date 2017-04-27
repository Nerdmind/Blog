<?php
namespace User;

class Attribute extends \Attribute {

	#===============================================================================
	# Pre-Define database table columns
	#===============================================================================
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

	#===============================================================================
	# Define database table name
	#===============================================================================
	const TABLE = 'user';
}
?>