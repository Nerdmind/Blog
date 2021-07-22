<?php
namespace ORM\Repositories;
use ORM\Repository;
use ORM\EntityInterface;
use ORM\Entities\Category;

class CategoryRepository extends Repository {
	public static function getTableName(): string { return 'category'; }
	public static function getClassName(): string { return 'ORM\Entities\Category'; }

	#===============================================================================
	# Get number of *posts* assigned to $Category
	#===============================================================================
	public function getNumberOfPosts(Category $Category): int {
		$query = 'SELECT COUNT(id) FROM %s WHERE category = ?';
		$query = sprintf($query, PostRepository::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$Category->getID()]);

		return $Statement->fetchColumn();
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

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$value]);

		# TODO: Virtual column _depth shall not be fetched into the entity class
		return $this->fetchEntities($Statement);
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

		$Statement = $this->Database->prepare($query);
		$Statement->execute();

		return $this->fetchEntities($Statement);
	}

	#===============================================================================
	# Get number of children categories assigned to $Category
	#===============================================================================
	public function getNumberOfChildren(Category $Category): int {
		$query = 'WITH RECURSIVE tree AS (
			SELECT * FROM %s WHERE id = ? UNION
			SELECT c.* FROM %s c, tree WHERE tree.id = c.parent
		) SELECT COUNT(id) FROM tree WHERE id != ?';

		$query = sprintf($query,
			static::getTableName(),
			static::getTableName()
		);

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$Category->getID(), $Category->getID()]);

		return $Statement->fetchColumn();
	}

	#===============================================================================
	# Update category (and check for parent/child circular loops)
	#===============================================================================
	public function update(EntityInterface $Entity): bool {
		# Entity parent might have changed *in memory*, so we re-fetch the original
		# parent of the entity from the database and save it in a variable.
		# TODO: Repository/Entity class should have a mechanism to detect changes!
		$query = 'SELECT parent FROM %s WHERE id = ?';
		$query = sprintf($query, static::getTableName());

		$Statement = $this->Database->prepare($query);
		$Statement->execute([$Entity->getID()]);

		$parent = $Statement->fetchColumn();

		# If parent is unchanged, circular loop check is not needed.
		if($parent === $Entity->get('parent')) {
			return parent::update($Entity);
		}

		$_parent = $Entity->get('parent');

		# Fetch the parent of the *new* parent category and let the while loop run through
		# the tree until either a parent of "NULL" was found or if the new parent category
		# is a *child* of the *current* category which would cause a circular loop.
		while($Statement->execute([$_parent]) && $_parent = $Statement->fetchColumn()) {
			if($_parent == $Entity->get('id')) {
				# Set parent of the *new* parent category to the *original* parent category
				# of the *current* category (one level up) to prevent a circular loop.
				$query = 'UPDATE %s SET parent = ? WHERE id = ?';
				$query = sprintf($query, static::getTableName());

				$UpdateStatement = $this->Database->prepare($query);
				$UpdateStatement->execute([$parent, $Entity->get('parent')]);
				break;
			} else if($_parent === NULL) {
				break;
			}
		}

		return parent::update($Entity);
	}
}
