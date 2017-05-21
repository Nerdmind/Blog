<?php
abstract class Item implements ItemInterface {
	protected $Database   = NULL;
	protected $Attribute  = NULL;
	protected $Reflection = NULL;

	abstract public function getURL();
	abstract public function getGUID();

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
	# Return pre-parsed content
	#===============================================================================
	public function getBody(): string {
		$content = preg_replace_callback('#\{(POST|PAGE|USER)\[([0-9]+)\]\}#', function($matches) {
			$namespace = ucfirst(strtolower($matches[1])).'\\Factory';

			try {
				$Item = $namespace::build($matches[2]);
				return $Item->getURL();
			} catch(Exception $Exception) {
				return '{undefined}';
			}
		}, $this->Attribute->get('body'));

		$content = preg_replace('#\{BASE\[\"([^"]+)\"\]\}#', \Application::getURL('$1'), $content);
		$content = preg_replace('#\{FILE\[\"([^"]+)\"\]\}#', \Application::getFileURL('$1'), $content);

		return $content;
	}

	#===============================================================================
	# Return parsed content
	#===============================================================================
	public function getHTML(): string {
		$item = "{$this->Reflection->getNamespaceName()}\\Item";

		$Parsedown = new Parsedown();
		$Parsedown->setUrlsLinked(FALSE);
		$content = $this->getBody();

		if(\Application::get($item::CONFIGURATION.'.EMOTICONS') === TRUE) {
			$content = parseEmoticons($content);
		}

		return $Parsedown->text($content);
	}

	#===============================================================================
	# Return attached files
	#===============================================================================
	public function getFiles(): array {
		if(preg_match_all('#\!\[(.*)\][ ]?(?:\n[ ]*)?\((.*)(\s[\'"](.*)[\'"])?\)#U', $this->getBody(), $matches)) {
			return array_map('htmlentities', $matches[2]);
		}

		return [];
	}

	#===============================================================================
	# Return parsed arguments
	#===============================================================================
	public function getArguments(): array {
		if($argv = $this->Attribute->get('argv')) {
			foreach(explode('|', $argv) as $delimeter) {
				$part = explode('=', $delimeter);

				$argumentK = $part[0] ?? NULL;
				$argumentV = $part[1] ?? TRUE;

				if(preg_match('#^[[:word:]]+$#', $argumentK)) {
					$arguments[strtoupper($argumentK)] = $argumentV;
				}
			}
		}

		return $arguments ?? [];
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
?>