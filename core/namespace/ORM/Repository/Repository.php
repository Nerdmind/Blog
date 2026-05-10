<?php
namespace ORM\Repository;
use Database;
use PDOStatement;
use ORM\Entity\EntityInterface;

abstract class Repository implements RepositoryInterface {
	protected $database;
	protected $entities = [];

	abstract public static function getTableName(): string;
	abstract public static function getClassName(): string;

	public function __construct(Database $database) {
		$this->database = $database;
	}

	#===============================================================================
	# Fetch entity from a PDOStatement result
	#===============================================================================
	protected function fetchEntity(PDOStatement $statement): ?EntityInterface {
		if($entity = $statement->fetchObject(static::getClassName())) {
			$this->storeInstance($entity->getID(), $entity);
			return $entity;
		}

		return null;
	}

	#===============================================================================
	# Fetch multiple entities from a PDOStatement result
	#===============================================================================
	protected function fetchEntities(PDOStatement $statement): array {
		if($entities = $statement->fetchAll($this->database::FETCH_CLASS, static::getClassName())) {
			$this->storeMultipleInstances($entities);
			return $entities;
		}

		return [];
	}

	#===============================================================================
	# Adds an entity to the runtime cache
	#===============================================================================
	protected function storeInstance(int $identifier, EntityInterface $entity) {
		return $this->entities[$identifier] = $entity;
	}

	#===============================================================================
	# Adds an array of entities to the runtime cache
	#===============================================================================
	protected function storeMultipleInstances(array $entities) {
		foreach($entities as $entity) {
			$this->storeInstance($entity->getID(), $entity);
		}

		return $entities;
	}

	#===============================================================================
	# Gets an entity from the runtime cache
	#===============================================================================
	protected function fetchInstance($identifier) {
		return $this->entities[$identifier] ?? false;
	}

	#===============================================================================
	# Removes an entity from the runtime cache
	#===============================================================================
	protected function removeInstance($identifier) {
		if(isset($this->entities[$identifier])) {
			unset($this->entities[$identifier]);
		}
	}

	#===========================================================================
	# Insert entity
	#===========================================================================
	public function insert(EntityInterface $entity): bool {
		foreach($entity->getModifiedKeys() as $attribute) {
			$params[] = $entity->get($attribute);
			$fields[] = $attribute;
			$values[] = '?';
		}

		if(isset($params, $fields, $values)) {
			$fields = implode(', ', $fields);
			$values = implode(', ', $values);

			$query = sprintf('INSERT INTO %s (%s) VALUES(%s)',
				static::getTableName(), $fields, $values);

			$statement = $this->database->prepare($query);
			return $statement->execute($params);
		}

		return false;
	}

	#===========================================================================
	# Update entity
	#===========================================================================
	public function update(EntityInterface $entity): bool {
		foreach($entity->getModifiedKeys() as $attribute) {
			$params[] = $entity->get($attribute);
			$fields[] = "$attribute = ?";
		}

		if(isset($params, $fields)) {
			$fields = implode(', ', $fields);

			$query = sprintf('UPDATE %s SET %s WHERE id = %d',
				static::getTableName(), $fields, $entity->getID());

			$statement = $this->database->prepare($query);
			return $statement->execute($params);
		}

		return false;
	}

	#===========================================================================
	# Delete entity
	#===========================================================================
	public function delete(EntityInterface $entity): bool {
		$query = 'DELETE FROM %s WHERE id = ?';
		$query = sprintf($query, static::getTableName());

		$statement = $this->database->prepare($query);
		return $statement->execute([$entity->getID()]);
	}

	#===========================================================================
	# Find entity based on primary key
	#===========================================================================
	public function find($id): ?EntityInterface {
		if($entity = $this->fetchInstance($id)) {
			return $entity;
		}

		return $this->findBy('id', $id);
	}

	#===============================================================================
	# Find entity based on specific field comparison
	#===============================================================================
	public function findBy(string $field, $value): ?EntityInterface {
		$query = 'SELECT * FROM %s WHERE %s = ?';
		$query = sprintf($query, static::getTableName(), $field);

		$statement = $this->database->prepare($query);
		$statement->execute([$value]);

		return $this->fetchEntity($statement);
	}

	#===============================================================================
	# Find previous entity
	#===============================================================================
	public function findPrev(EntityInterface $entity): ?EntityInterface {
		$query = 'SELECT * FROM %s WHERE time_insert < ? ORDER BY time_insert DESC LIMIT 1';
		$query = sprintf($query, static::getTableName());

		$statement = $this->database->prepare($query);
		$statement->execute([$entity->get('time_insert')]);

		return $this->fetchEntity($statement);
	}

	#===============================================================================
	# Find next entity
	#===============================================================================
	public function findNext(EntityInterface $entity): ?EntityInterface {
		$query = 'SELECT * FROM %s WHERE time_insert > ? ORDER BY time_insert ASC LIMIT 1';
		$query = sprintf($query, static::getTableName());

		$statement = $this->database->prepare($query);
		$statement->execute([$entity->get('time_insert')]);

		return $this->fetchEntity($statement);
	}

	#===========================================================================
	# Find last (which means the newest) entity
	#===========================================================================
	public function getLast(): ?EntityInterface {
		$query = 'SELECT * FROM %s ORDER BY time_insert DESC LIMIT 1';
		$query = sprintf($query, static::getTableName());

		$statement = $this->database->query($query);
		return $this->fetchEntity($statement);
	}

	#===========================================================================
	# Get entity count
	#===========================================================================
	public function getCount(array $filter = []): int {
		$wheres = [];
		$params = [];

		if(!empty($filter)) {
			foreach($filter as $column => $value) {
				if($value === null) {
					$wheres[] = "$column IS NULL";
				} else {
					$wheres[] = "$column = ?";
					$params[] = $value;
				}
			}

			$where = 'WHERE '.implode(' AND ', $wheres);
		}

		$query = 'SELECT COUNT(id) FROM %s %s';
		$query = sprintf($query, static::getTableName(), $where ?? '');

		$statement = $this->database->prepare($query);
		$statement->execute($params);

		return $statement->fetchColumn();
	}

	#===========================================================================
	# Get paginated entity list
	#===========================================================================
	public function getPaginated(string $order, int $limit, int $offset = 0): array {
		return $this->getAll([], $order, $limit, $offset);
	}

	#===========================================================================
	# Get all entities
	#===========================================================================
	public function getAll(array $filter = [], string $order = null, int $limit = null, int $offset = 0): array {
		$select = 'SELECT * FROM '.static::getTableName();
		$wheres = [];
		$params = [];

		if(!empty($filter)) {
			foreach($filter as $column => $value) {
				if($value === NULL) {
					$wheres[] = "$column IS NULL";
				} else {
					$wheres[] = "$column = ?";
					$params[] = $value;
				}
			}

			$where = 'WHERE '.implode(' AND ', $wheres);
		}

		if($order) {
			$order = "ORDER BY $order";
		}

		if($limit) {
			$limit = "LIMIT $offset,$limit";
		}

		$query = "$select %s %s %s";
		$query = sprintf($query, $where ?? '', $order ?? '', $limit ?? '');

		$statement = $this->database->prepare($query);
		$statement->execute($params);

		return $this->fetchEntities($statement);
	}
}
