CREATE TABLE `migration` (`schema_version` smallint(4) NOT NULL)
	ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO `migration` (`schema_version`) VALUES (5);
