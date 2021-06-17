<?php
abstract class Factory implements FactoryInterface {
	public static $storage = [];

	#===============================================================================
	# Adds an instance of a class to the runtime instance cache
	#===============================================================================
	protected static function storeInstance($identifier, $instance) {
		return self::$storage[get_called_class()][$identifier] = $instance;
	}

	#===============================================================================
	# Gets an instance of a class from the runtime instance cache
	#===============================================================================
	protected static function fetchInstance($identifier) {
		return self::$storage[get_called_class()][$identifier] ?? FALSE;
	}
}
