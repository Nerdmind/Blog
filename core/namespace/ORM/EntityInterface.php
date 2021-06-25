<?php
namespace ORM;

interface EntityInterface {
	public function get(string $attribute);
	public function set(string $attribute, $value);

	public function getID(): int;
	public function getAll(array $exclude = []): array;
}
