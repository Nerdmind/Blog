<?php
namespace ORM\Repository;
use ORM\Entity\EntityInterface;
use ORM\Entity\Category;

class CategoryRepository extends Repository {
	public static function getTableName(): string { return 'category'; }
	public static function getClassName(): string { return 'ORM\Entity\Category'; }

	#===============================================================================
	# Get number of *posts* assigned to $category
	#===============================================================================
	public function getNumberOfPosts(Category $category): int {
		$query = 'SELECT COUNT(id) FROM %s WHERE category = ?';
		$query = sprintf($query, PostRepository::getTableName());

		$statement = $this->database->prepare($query);
		$statement->execute([$category->getID()]);

		return $statement->fetchColumn();
	}

	#===============================================================================
	# Find category with parents based on primary key
	#===============================================================================
	public function findWithParents($id): array {
		return $this->findWithParentsBy('id', $id);
	}

	#===============================================================================
	# Find category with parents based on specific field comparison
	#===============================================================================
	public function findWithParentsBy(string $field, $value): array {
		$query = 'WITH RECURSIVE tree AS (
			SELECT *, 0 AS _depth FROM %s WHERE %s %s UNION
			SELECT c.*, _depth+1 FROM %s c, tree WHERE tree.parent = c.id
		) SELECT * FROM tree ORDER BY _depth DESC';

		$table = static::getTableName();
		$check = is_null($value) ? 'IS NULL': '= ?';
		$query = sprintf($query, $table, $field, $check, $table);

		$statement = $this->database->prepare($query);
		$statement->execute(
			# If $value is NULL, pass NULL instead of an array with one NULL
			# element. PHP >= 8 will throw a PDOException when the number of
			# bound variables doesn't matches the number of tokens ("?") in
			# the SQL query, which is the case here if $value is NULL.
			is_null($value) ? null : [$value]
		);

		# TODO: Virtual column _depth shall not be fetched into the entity class
		return $this->fetchEntities($statement);
	}

	#===============================================================================
	# Get paginated category tree list
	#===============================================================================
	public function getPaginatedTree(int $limit, int $offset = 0): array {
		$query = 'WITH RECURSIVE tree AS (
			SELECT *, name AS _depth FROM %s WHERE parent IS NULL UNION
			SELECT c.*, CONCAT(_depth, "/", c.name) AS _depth FROM %s c INNER JOIN tree ON tree.id = c.parent
		) SELECT * FROM tree ORDER BY _depth %s';

		$_limit = "LIMIT $limit";

		if($offset) {
			$_limit = "LIMIT $offset,$limit";
		}

		$table = static::getTableName();
		$query = sprintf($query, $table, $table, $_limit);

		$statement = $this->database->prepare($query);
		$statement->execute();

		return $this->fetchEntities($statement);
	}

	#===============================================================================
	# Get number of children categories assigned to $category
	#===============================================================================
	public function getNumberOfChildren(Category $category): int {
		$query = 'WITH RECURSIVE tree AS (
			SELECT * FROM %s WHERE id = ? UNION
			SELECT c.* FROM %s c, tree WHERE tree.id = c.parent
		) SELECT COUNT(id) FROM tree WHERE id != ?';

		$query = sprintf($query,
			static::getTableName(),
			static::getTableName()
		);

		$statement = $this->database->prepare($query);
		$statement->execute([$category->getID(), $category->getID()]);

		return $statement->fetchColumn();
	}

	#===============================================================================
	# Update category (and check for parent/child circular loops)
	#===============================================================================
	public function update(EntityInterface $entity): bool {
		# Skip circular loop check if parent is unchanged
		if(!in_array('parent', $entity->getModifiedKeys())) {
			return parent::update($entity);
		}

		$query = 'SELECT parent FROM %s WHERE id = ?';
		$query = sprintf($query, static::getTableName());

		$statement = $this->database->prepare($query);
		$statement->execute([$entity->getID()]);

		$_parent = $entity->get('parent');

		# Fetch the parent of the *new* parent category and let the while loop run through
		# the tree until either a parent of "NULL" was found or if the new parent category
		# is a *child* of the *current* category which would cause a circular loop.
		while($statement->execute([$_parent]) && $_parent = $statement->fetchColumn()) {
			if($_parent == $entity->getID()) {
				# Set parent of the *new* parent category to the *original* parent category
				# of the *current* category (one level up) to prevent a circular loop.
				$query = 'UPDATE %s SET parent = (SELECT parent FROM %s WHERE id = ?) WHERE id = ?';
				$query = sprintf($query, static::getTableName(), static::getTableName());

				$updateStatement = $this->database->prepare($query);
				$updateStatement->execute([$_parent, $entity->get('parent')]);
				break;
			} elseif($_parent === null) {
				break;
			}
		}

		return parent::update($entity);
	}
}
