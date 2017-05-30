<?php
abstract class Attribute implements AttributeInterface {

	#===============================================================================
	# Set attribute
	#===============================================================================
	public function set($attribute, $value) {
		return $this->{$attribute} = $value;
	}

	#===============================================================================
	# Get attribute
	#===============================================================================
	public function get($attribute) {
		return $this->{$attribute} ?? NULL;
	}

	#===============================================================================
	# Get all attributes
	#===============================================================================
	public function getAll($exclude = []): array {
		$attributes = get_object_vars($this);

		return array_filter($attributes, function($attribute) use($exclude) {
			return !in_array($attribute, $exclude);
		}, ARRAY_FILTER_USE_KEY);
	}

	#===============================================================================
	# Get array with not FALSE attributes
	#===============================================================================
	protected function getFilteredAttributes(): array {
		return array_filter(get_object_vars($this), function($value) {
			return $value !== FALSE;
		});
	}

	#===============================================================================
	# Insert database item
	#===============================================================================
	public function databaseINSERT(\Database $Database): bool {
		$part[0] = '';
		$part[1] = '';

		$attributes = $this->getFilteredAttributes();

		foreach($attributes as $column => $value) {
			$part[0] .= "{$column},";
			$part[1] .= '?,';
		}

		$part[0] = rtrim($part[0], ',');
		$part[1] = rtrim($part[1], ',');

		$Statement = $Database->prepare('INSERT INTO '.static::TABLE." ({$part[0]}) VALUES ({$part[1]})");
		return $Statement->execute(array_values($attributes));
	}

	#===============================================================================
	# Update database item
	#===============================================================================
	public function databaseUPDATE(\Database $Database): bool {
		$part = '';

		$attributes = $this->getFilteredAttributes();

		foreach($attributes as $column => $value) {
			$part .= "{$column} = ?,";
		}

		$part = rtrim($part, ',');

		$Statement = $Database->prepare('UPDATE '.static::TABLE.' SET '.$part.' WHERE id = '.(int) $this->get('id'));
		return $Statement->execute(array_values($attributes));
	}

	#===============================================================================
	# Delete database item
	#===============================================================================
	public function databaseDELETE(\Database $Database): bool {
		$Statement = $Database->prepare('DELETE FROM '.static::TABLE.' WHERE id = ?');
		return $Statement->execute([$this->get('id')]);
	}
}
?>