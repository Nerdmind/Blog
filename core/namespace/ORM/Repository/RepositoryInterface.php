<?php
namespace ORM\Repository;
use ORM\Entity\EntityInterface;

interface RepositoryInterface {
	public function insert(EntityInterface $entity): bool;
	public function update(EntityInterface $entity): bool;
	public function delete(EntityInterface $entity): bool;
	public function find($id): ?EntityInterface;
	public function findBy(string $field, $value): ?EntityInterface;
	public function findPrev(EntityInterface $entity): ?EntityInterface;
	public function findNext(EntityInterface $entity): ?EntityInterface;
	public function getLast(): ?EntityInterface;
	public function getCount(array $filter = []): int;
	public function getPaginated(string $order, int $limit, int $offset = 0): array;
	public function getAll(array $filter = [], string $order = null, int $limit = null, int $offset = 0): array;
}
