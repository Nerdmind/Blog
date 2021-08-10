<?php
namespace ORM;
use Database;
use PDOStatement;

abstract class Repository {
	protected $Database;
	protected $entities = [];

	abstract public static function getTableName(): string;
	abstract public static function getClassName(): string;

	public function __construct(Database $Database) {
		$this->Database = $Database;
	}

	#===============================================================================
	# Fetch entity from a PDOStatement result
	#===============================================================================
	protected function fetchEntity(PDOStatement $Statement): ?EntityInterface {
		if($Entity = $Statement->fetchObject(static::getClassName())) {
			$this->storeInstance($Entity->getID(), $Entity);
			return $Entity;
		}

		return NULL;
	}

	#===============================================================================
	# Fetch multiple entities from a PDOStatement result
	#===============================================================================
	protected function fetchEntities(PDOStatement $Statement): array {
		if($entities = $Statement->fetchAll($this->Database::FETCH_CLASS, static::getClassName())) {
			$this->storeMultipleInstances($entities);
			return $entities;
		}

		return [];
	}

	#===============================================================================
	# Adds an entity to the runtime cache
	#===============================================================================
	protected function storeInstance(int $identifier, EntityInterface $Entity) {
		return $this->entities[$identifier] = $Entity;
	}

	#===============================================================================
	# Adds an array of entities to the runtime cache
	#===============================================================================
	protected function storeMultipleInstances(array $entities) {
		foreach($entities as $Entity) {
			$this->storeInstance($Entity->getID(), $Entity);
		}

		return $entities;
	}

	#===============================================================================
	# Gets an entity from the runtime cache
	#===============================================================================
	protected function fetchInstance($identifier) {
		return $this->entities[$identifier] ?? FALSE;
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
	public function insert(EntityInterface $Entity): bool {
		foreach($Entity->getModifiedKeys() as $attribute) {
			$params[] = $Entity->get($attribute);
			$fields[] = $attribute;
			$values[] = '?';
		}

		if(isset($params, $fields, $values)) {
			$fields = implode(', ', $fields);
			$values = implode(', ', $values);

			$query = sprintf('INSERT INTO %s (%s) VALUES(%s)',
				static::getTableName(), $fields, $values);

			$Statement = $this->Database->prepare($query);
			return $Statement->execute($params);
		}

		return FALSE;
	}

	#===========================================================================
	# Update entity
	#===========================================================================
	public function update(EntityInterface $Entity): bool {
		foreach($Entity->getModifiedKeys() as $attribute) {
			$params[] = $Entity->get($attribute);
			$fields[] = "$attribute = ?";
		}

		if(isset($params, $fields)) {
			$fields = implode(', ', $fields);

			$query = sprintf('UPDATE %s SET %s WHERE id = %d',
				static::getTableName(), $fields, $Entity->getID());

			$Statement = $this->Database->prepare($query);
			return $Statement->execute($params);
		}

		return FALSE;
	}

	#===========================================================================
	# Delete entity
	#===========================================================================
	public function delete(EntityInterface $Entity): bool {
		$query = 'DELETE FROM %s WHERE id = ?';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->prepare($query);
		return $Statement->execute([$Entity->getID()]);
	}

	#===========================================================================
	# Find entity based on primary key
	#===========================================================================
	public function find($id): ?EntityInterface {
		if($Entity = $this->fetchInstance($id)) {
			return $Entity;
		}

		return $this->findBy('id', $id);
	}

	#===============================================================================
	# Find entity based on specific field comparison
	#===============================================================================
	public function findBy(string $field, $value): ?EntityInterface {
		$query = 'SELECT * FROM %s WHERE %s = ?';
		$query = sprintf($query, static::getTableName(), $field);

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$value]);

		return $this->fetchEntity($Statement);
	}

	#===============================================================================
	# Find previous entity
	#===============================================================================
	public function findPrev(EntityInterface $Entity): ?EntityInterface {
		$query = 'SELECT * FROM %s WHERE time_insert < ? ORDER BY time_insert DESC LIMIT 1';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$Entity->get('time_insert')]);

		return $this->fetchEntity($Statement);
	}

	#===============================================================================
	# Find next entity
	#===============================================================================
	public function findNext(EntityInterface $Entity): ?EntityInterface {
		$query = 'SELECT * FROM %s WHERE time_insert > ? ORDER BY time_insert ASC LIMIT 1';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$Entity->get('time_insert')]);

		return $this->fetchEntity($Statement);
	}

	#===========================================================================
	# Find last (which means the newest) entity
	#===========================================================================
	public function getLast(): ?EntityInterface {
		$query = 'SELECT * FROM %s ORDER BY time_insert DESC LIMIT 1';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->query($query);
		return $this->fetchEntity($Statement);
	}

	#===========================================================================
	# Get entity count
	#===========================================================================
	public function getCount(array $filter = []): int {
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

		$query = 'SELECT COUNT(id) FROM %s %s';
		$query = sprintf($query, static::getTableName(), $where ?? '');

		$Statement = $this->Database->prepare($query);
		$Statement->execute($params);

		return $Statement->fetchColumn();
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

		$Statement = $this->Database->prepare($query);
		$Statement->execute($params);

		return $this->fetchEntities($Statement);
	}
}
