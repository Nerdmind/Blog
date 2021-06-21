<?php
abstract class Item implements ItemInterface {
	protected $Database   = NULL;
	protected $Attribute  = NULL;
	protected $Reflection = NULL;

	#===============================================================================
	# Abstract item constructor
	#===============================================================================
	public final function __construct($itemID, \Database $Database) {
		$this->Database = $Database;

		$this->Reflection = new ReflectionObject($this);

		$attribute = "{$this->Reflection->getNamespaceName()}\\Attribute";
		$exception = "{$this->Reflection->getNamespaceName()}\\Exception";

		#===============================================================================
		# Checking if item in database exists
		#===============================================================================
		$Statement = $Database->prepare(sprintf('SELECT * FROM %s WHERE id = ?', $attribute::TABLE));
		$Statement->execute([$itemID]);

		#===============================================================================
		# Checking if retrieving data failed
		#===============================================================================
		if(!$this->Attribute = $Statement->fetchObject($attribute)) {
			throw new $exception(sprintf('%s\\Item with ID %s does not exist', $this->Reflection->getNamespaceName(), (int) $itemID));
		}
	}

	#===============================================================================
	# Return attribute by name (short hand wrapper)
	#===============================================================================
	public function attr($attribute) {
		return $this->Attribute->get($attribute);
	}

	#===============================================================================
	# Return Attribute object
	#===============================================================================
	public final function getAttribute(): Attribute {
		return $this->Attribute;
	}

	#===============================================================================
	# Return unique ID
	#===============================================================================
	public final function getID(): int {
		return $this->Attribute->get('id');
	}

	#===============================================================================
	# Return previous item ID
	#===============================================================================
	public function getPrevID(): int {
		$execute = 'SELECT id FROM %s WHERE time_insert < ? ORDER BY time_insert DESC LIMIT 1';

		$attribute = "{$this->Reflection->getNamespaceName()}\\Attribute";
		$Statement = $this->Database->prepare(sprintf($execute, $attribute::TABLE));

		if($Statement->execute([$this->Attribute->get('time_insert')])) {
			return $Statement->fetchColumn();
		}

		return 0;
	}

	#===============================================================================
	# Return next item ID
	#===============================================================================
	public function getNextID(): int {
		$execute = 'SELECT id FROM %s WHERE time_insert > ? ORDER BY time_insert ASC LIMIT 1';

		$attribute = "{$this->Reflection->getNamespaceName()}\\Attribute";
		$Statement = $this->Database->prepare(sprintf($execute, $attribute::TABLE));

		if($Statement->execute([$this->Attribute->get('time_insert')])) {
			return $Statement->fetchColumn();
		}

		return 0;
	}

	#===============================================================================
	# Return unique ID based on specific field comparison with value
	#===============================================================================
	public static function getIDByField($field, $value, \Database $Database): int {
		$attribute = (new ReflectionClass(get_called_class()))->getNamespaceName().'\\Attribute';
		$Statement = $Database->prepare('SELECT id FROM '.$attribute::TABLE." WHERE {$field} = ?");

		if($Statement->execute([$value])) {
			return $Statement->fetchColumn();
		}

		return 0;
	}
}
