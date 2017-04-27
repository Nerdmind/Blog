<?php
namespace Page;

class Attribute extends \Attribute {

	#===============================================================================
	# Pre-Define database table columns
	#===============================================================================
	protected $id   = FALSE;
	protected $user = FALSE;
	protected $slug = FALSE;
	protected $name = FALSE;
	protected $body = FALSE;
	protected $argv = FALSE;
	protected $time_insert = FALSE;
	protected $time_update = FALSE;

	#===============================================================================
	# Define database table name
	#===============================================================================
	const TABLE = 'page';
}
?>