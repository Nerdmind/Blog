<?php
namespace ORM;

interface EntityInterface {
	public function get(string $attribute);
	public function set(string $attribute, $value): void;

	public function getID(): int;
	public function getAll(array $exclude = []): array;
	public function getModifiedKeys(): array;
}
