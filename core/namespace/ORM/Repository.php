<?php
namespace ORM;
use Database;

abstract class Repository {
	protected $Database;
	protected $entities = [];

	abstract public static function getTableName(): string;
	abstract public static function getClassName(): string;

	public function __construct(Database $Database) {
		$this->Database = $Database;
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
	protected function fetchInstance(int $identifier) {
		return $this->entities[$identifier] ?? FALSE;
	}

	#===============================================================================
	# Removes an entity from the runtime cache
	#===============================================================================
	protected function removeInstance(int $identifier) {
		if(isset($this->entities[$identifier])) {
			unset($this->entities[$identifier]);
		}
	}

	#===========================================================================
	# Insert entity
	#===========================================================================
	public function insert(EntityInterface $Entity): bool {
		$attributes = $Entity->getFilteredAttributes();

		foreach($attributes as $field => $value) {
			$fields[] = $field;
			$values[] = '?';
		}

		$fields = implode(', ', $fields ?? []);
		$values = implode(', ', $values ?? []);

		$query = 'INSERT INTO %s (%s) VALUES(%s)';
		$query = sprintf($query, static::getTableName(), $fields, $values);

		$Statement = $this->Database->prepare($query);
		return $Statement->execute(array_values($attributes));
	}

	#===========================================================================
	# Update entity
	#===========================================================================
	public function update(EntityInterface $Entity): bool {
		$attributes = $Entity->getFilteredAttributes();

		foreach($attributes as $field => $value) {
			$params[] = "$field = ?";
		}

		$params = implode(', ', $params ?? []);

		$query = 'UPDATE %s SET %s WHERE id = '.intval($Entity->getID());
		$query = sprintf($query, static::getTableName(), $params);

		$Statement = $this->Database->prepare($query);
		return $Statement->execute(array_values($attributes));
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
	public function find(int $id): ?EntityInterface {
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

		if($Entity = $Statement->fetchObject(static::getClassName())) {
			$this->storeInstance($Entity->getID(), $Entity);
			return $Entity;
		}

		return NULL;
	}

	#===============================================================================
	# Find previous entity
	#===============================================================================
	public function findPrev(EntityInterface $Entity): ?EntityInterface {
		$query = 'SELECT * FROM %s WHERE time_insert < ? ORDER BY time_insert DESC LIMIT 1';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$Entity->get('time_insert')]);

		if($Entity = $Statement->fetchObject(static::getClassName())) {
			$this->storeInstance($Entity->getID(), $Entity);
			return $Entity;
		}

		return NULL;
	}

	#===============================================================================
	# Find next entity
	#===============================================================================
	public function findNext(EntityInterface $Entity): ?EntityInterface {
		$query = 'SELECT * FROM %s WHERE time_insert > ? ORDER BY time_insert ASC LIMIT 1';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$Entity->get('time_insert')]);

		if($Entity = $Statement->fetchObject(static::getClassName())) {
			$this->storeInstance($Entity->getID(), $Entity);
			return $Entity;
		}

		return NULL;
	}

	#===========================================================================
	# Find last (which means the newest) entity
	#===========================================================================
	public function getLast(): ?EntityInterface {
		$query = 'SELECT * FROM %s ORDER BY time_insert DESC LIMIT 1';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->query($query);

		if($Entity = $Statement->fetchObject(static::getClassName())) {
			$this->storeInstance($Entity->getID(), $Entity);
			return $Entity;
		}

		return NULL;
	}

	#===========================================================================
	# Get entity count
	#===========================================================================
	public function getCount(): int {
		$query = 'SELECT COUNT(id) FROM %s';
		$query = sprintf($query, static::getTableName());

		return $this->Database->query($query)->fetchColumn();
	}

	#===========================================================================
	# Get paginated entity list
	#===========================================================================
	public function getPaginated(string $order, int $limit, int $offset = 0): array {
		return $this->getAll([], $order, "$offset,$limit");
	}

	#===========================================================================
	# Get all entities
	#===========================================================================
	public function getAll(array $filter = [], string $order = null, string $limit = null): array {
		$select = 'SELECT * FROM '.static::getTableName();
		$wheres = [];
		$params = [];

		if(!empty($filter)) {
			foreach($filter as $column => $value) {
				$wheres[] = "$column = ?";
				$params[] = $value;
			}

			$where = 'WHERE '.implode(' AND ', $wheres);
		}

		if($order) {
			$order = "ORDER BY $order";
		}

		if($limit) {
			$limit = "LIMIT $limit";
		}

		$query = "$select %s %s %s";
		$query = sprintf($query, $where ?? '', $order ?? '', $limit ?? '');

		$Statement = $this->Database->prepare($query);
		$Statement->execute($params);

		if($entities = $Statement->fetchAll($this->Database::FETCH_CLASS, static::getClassName())) {
			$this->storeMultipleInstances($entities);
			return $entities;
		}

		return [];
	}

	#===============================================================================
	# Get entities based on search query
	#===============================================================================
	public function search(string $search, array $filter = []): array {
		if($search === '*') {
			return $this->getAll([], NULL, 20);
		}

		if(strlen($filter['year'] ?? '') !== 0) {
			$extend[] = 'YEAR(time_insert) = ? AND';
			$params[] = $filter['year'];
		}

		if(strlen($filter['month'] ?? '') !== 0) {
			$extend[] = 'MONTH(time_insert) = ? AND';
			$params[] = $filter['month'];
		}

		if(strlen($filter['day'] ?? '') !== 0) {
			$extend[] = 'DAY(time_insert) = ? AND';
			$params[] = $filter['day'];
		}

		$dateparts = implode(' ', $extend ?? []);

		$query = 'SELECT * FROM %s WHERE %s MATCH(name, body)
			AGAINST(? IN BOOLEAN MODE) LIMIT 20';
		$query = sprintf($query, static::getTableName(), $dateparts);

		$Statement = $this->Database->prepare($query);
		$Statement->execute(array_merge($params ?? [], [$search]));

		if($entities = $Statement->fetchAll($this->Database::FETCH_CLASS, static::getClassName())) {
			$this->storeMultipleInstances($entities);
			return $entities;
		}

		return [];
	}

	#===============================================================================
	# Get a list of distinct days
	#===============================================================================
	public function getDistinctDays(): array {
		$query = 'SELECT DISTINCT DAY(time_insert) AS d FROM %s ORDER BY d';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->query($query);

		if($result = $Statement->fetchAll($this->Database::FETCH_COLUMN)) {
			return $result;
		}

		return [];
	}

	#===============================================================================
	# Get a list of distinct months
	#===============================================================================
	public function getDistinctMonths(): array {
		$query = 'SELECT DISTINCT MONTH(time_insert) AS m FROM %s ORDER BY m';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->query($query);

		if($result = $Statement->fetchAll($this->Database::FETCH_COLUMN)) {
			return $result;
		}

		return [];
	}

	#===============================================================================
	# Get a list of distinct years
	#===============================================================================
	public function getDistinctYears(): array {
		$query = 'SELECT DISTINCT YEAR(time_insert) AS y FROM %s ORDER BY y';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->query($query);

		if($result = $Statement->fetchAll($this->Database::FETCH_COLUMN)) {
			return $result;
		}

		return [];
	}
}
