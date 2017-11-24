<?php
namespace Category;

class Attribute extends \Attribute {

	#===============================================================================
	# Pre-Define database table columns
	#===============================================================================
	protected $id   = FALSE;
    protected $name = FALSE;
	protected $slug = FALSE;
	protected $time_insert = FALSE;
	protected $time_update = FALSE;

	#===============================================================================
	# Define database table name
	#===============================================================================
	const TABLE = 'category';
}
?>