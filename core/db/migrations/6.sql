CREATE TABLE `category` (
	`id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
	`time_insert` datetime NOT NULL,
	`time_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`parent` tinyint(3) UNSIGNED DEFAULT NULL,
	`slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
	`name` varchar(100) NOT NULL,
	`body` text NOT NULL,
	`argv` varchar(250) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `time_insert` (`time_insert`),
	UNIQUE KEY `slug` (`slug`),
	KEY `category_parent` (`parent`),
	FULLTEXT KEY `search` (`name`, `body`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `post` ADD `category` tinyint(3) UNSIGNED DEFAULT NULL AFTER `user`,
	ADD KEY `post_category` (`category`);

ALTER TABLE `category` ADD CONSTRAINT `category_parent` FOREIGN KEY (`parent`)
	REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `post` ADD CONSTRAINT `post_category` FOREIGN KEY (`category`)
	REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
