<?php
class Migrator {
	private $Database;
	private $version;
	private $directory;
	private $migrations = [];

	const CURRENT_SCHEMA_VERSION = 6;

	#===============================================================================
	# Fetch on-disk schema version from migration table
	#===============================================================================
	public function __construct(Database $Database) {
		$this->Database = $Database;

		try {
			$Statement = $Database->query('SELECT schema_version FROM migration');

			# Explicitly check for FALSE, because result can be "0"
			if(($this->version = $Statement->fetchColumn()) === FALSE) {
				throw new Exception('The migration table does exist, but there is
					no row containing the currently used on-disk schema version!');
			}
		} catch(PDOException $Exception) {
			if($Exception->getCode() === '42S02') /* Table not found */ {
				$this->version = $this->determineFallbackSchemaVersion();
				$this->createMigrationTable($this->version);
			} else {
				throw $Exception;
			}
		}
	}

	#===============================================================================
	# Specify the directory which contains the migration files
	#===============================================================================
	public function setMigrationsDir(string $directory): void {
		if(!is_readable($this->directory = rtrim($directory, '/'))) {
			throw new Exception('Migrator cannot read migration directory.');
		};
	}

	#===============================================================================
	# Check if new migrations needs to be applied
	#===============================================================================
	public function isMigrationNeeded(): bool {
		$databaseSchema = $this->version;
		$codebaseSchema = self::CURRENT_SCHEMA_VERSION;
		return $databaseSchema < $codebaseSchema;
	}

	#===============================================================================
	# Check if this is an unsupported downgrade attempt
	#===============================================================================
	public function isDowngradeAttempt(): bool {
		$databaseSchema = $this->version;
		$codebaseSchema = self::CURRENT_SCHEMA_VERSION;
		return $databaseSchema > $codebaseSchema;
	}

	#===============================================================================
	# Add a migration to the queue
	#===============================================================================
	private function enqueue(int $sequence, string $migration): void {
		$this->migrations[$sequence] = $migration;
	}

	#===============================================================================
	# Remove a migration from the queue
	#===============================================================================
	private function dequeue(int $sequence): void {
		unset($this->migrations[$sequence]);
	}

	#===============================================================================
	# Get the currently used on-disk schema version
	#===============================================================================
	public function getVersionFromTable(): int {
		return $this->version;
	}

	#===============================================================================
	# Get an array with all migration commands
	#===============================================================================
	public function getMigrations(): array {
		$databaseSchema = $this->version;
		$codebaseSchema = self::CURRENT_SCHEMA_VERSION;

		if(!$this->isMigrationNeeded()) {
			return [];
		}

		foreach(range($databaseSchema+1, $codebaseSchema) as $number) {
			$file = sprintf("%s/%d.sql", $this->directory, $number);

			if(!is_readable($file)) {
				throw new Exception("Migrator cannot read migration file: »{$file}«");
			}
			$this->enqueue($number, file_get_contents($file));
		}

		return $this->migrations;
	}

	#===============================================================================
	# Run migrations sequentially
	#===============================================================================
	public function runMigrations(): array {
		foreach($this->getMigrations() as $sequence => $migration) {
			try {
				if($this->Database->query($migration)) {
					$this->dequeue($sequence);
					$this->updateMigrationTable($sequence);
					$migrated[] = $sequence;
				}
			} catch(PDOException $Exception) {
				$error = 'Migration from %d to %d failed with PDO error:<br>';
				$error .= sprintf($error, $sequence-1, $sequence);
				$error .= sprintf('<code>%s</code>', $Exception->getMessage());

				if(!empty($migrated ?? [])) {
					$error .= '<br>The following migrations were successful: ';
					$error .= '<code>'.implode(', ', $migrated).'</code>';
				}

				throw new Exception($error);
			}
		}

		return $migrated ?? [];
	}

	#===============================================================================
	# Update the migration table with the specified schema version
	#===============================================================================
	private function updateMigrationTable(int $version): int {
		$query = 'UPDATE migration SET schema_version = ?';
		$Statement = $this->Database->prepare($query);

		if($Statement->execute([$version])) {
			return $this->version = $version;
		}
	}

	#===============================================================================
	# Create the migration table with the specified schema version
	#===============================================================================
	private function createMigrationTable(int $version): bool {
		$create = 'CREATE TABLE migration (schema_version smallint(4) NOT NULL)
			ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
		$insert = 'INSERT INTO migration (schema_version) VALUES (?)';

		$this->Database->query($create);
		$Statement = $this->Database->prepare($insert);

		return $Statement->execute([$version]);
	}

	#===============================================================================
	# Determine on-disk schema version if migration table does not exist
	#===============================================================================
	private function determineFallbackSchemaVersion(): int {
		# If the migration table does not yet exist, the user may have upgraded from
		# an older release of the application and sits either at 0, 1, 2, 3 or 4. So
		# we run some checks against the tables to determine the schema version.
		$test[4] = 'SHOW COLUMNS FROM post WHERE Field = "argv" AND Type = "varchar(250)"';
		$test[3] = 'SHOW INDEX FROM post WHERE Key_name = "search"';
		$test[2] = 'SHOW INDEX FROM post WHERE Key_name = "time_insert"';
		$test[1] = 'SHOW COLUMNS FROM post WHERE Field = "argv"';

		foreach($test as $version => $query) {
			try {
				$Statement = $this->Database->query($query);

				if ($Statement && $Statement->fetch()) {
					return $version;
				}
			} catch(PDOException $Exception) {}
		}

		return 0;
	}
}
