<?php
namespace ORM;

trait RepositorySearch {
	# See "search" method for more details.
	private $lastSearchOverallCount = 0;

	#===============================================================================
	# Get entities based on search query
	#===============================================================================
	public function search(string $search, array $filter = [], int $limit = NULL, int $offset = 0): array {
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

		if(is_numeric($filter['user'] ?? NULL)) {
			$extend[] = 'user = ? AND';
			$params[] = $filter['user'];
		}

		if(is_numeric($filter['category'] ?? NULL)) {
			$extend[] = 'category = ? AND';
			$params[] = $filter['category'];
		}

		if($limit) {
			$limit = "LIMIT $offset,$limit";
		}

		$dateparts = implode(' ', $extend ?? []);

		$query = 'SELECT *, COUNT(*) OVER() AS _count FROM %s WHERE %s MATCH(name, body)
			AGAINST(? IN BOOLEAN MODE) %s';
		$query = sprintf($query, static::getTableName(), $dateparts, $limit ?? 'LIMIT 20');

		$Statement = $this->Database->prepare($query);
		$Statement->execute(array_merge($params ?? [], [$search]));

		if($entities = $this->fetchEntities($Statement)) {
			# Temporary (maybe crappy) solution to prevent a second count query.
			# Virtual column "_count" does not belong into the entities.
			$this->lastSearchOverallCount = $entities[0]->get('_count');
		}

		return $entities;
	}

	#===============================================================================
	# Get the number of overall results for the last performed search
	#===============================================================================
	public function getLastSearchOverallCount(): int {
		return $this->lastSearchOverallCount;
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
