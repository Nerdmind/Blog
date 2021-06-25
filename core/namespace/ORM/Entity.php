<?php
namespace ORM;

abstract class Entity implements EntityInterface {
	protected $id;
	protected $time_insert;
	protected $time_update;

	#===============================================================================
	# Get attribute
	#===============================================================================
	public function get(string $attribute) {
		return $this->{$attribute} ?? NULL;
	}

	#===============================================================================
	# Set attribute
	#===============================================================================
	public function set(string $attribute, $value) {
		return $this->{$attribute} = $value;
	}

	#===============================================================================
	# Return ID
	#===============================================================================
	final public function getID(): int {
		return $this->id;
	}

	#===============================================================================
	# Get all attributes
	#===============================================================================
	public function getAll(array $exclude = []): array {
		$attributes = get_object_vars($this);

		return array_filter($attributes, function($attribute) use($exclude) {
			return !in_array($attribute, $exclude);
		}, ARRAY_FILTER_USE_KEY);
	}

	#===============================================================================
	# Get array with all non-false attributes
	#===============================================================================
	public function getFilteredAttributes(): array {
		return array_filter(get_object_vars($this), function($value) {
			return $value !== FALSE;
		});
	}
}
