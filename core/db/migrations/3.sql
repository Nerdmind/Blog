ALTER TABLE `post` ENGINE=InnoDB;
ALTER TABLE `post` DROP INDEX `body`;
ALTER TABLE `page` ADD FULLTEXT KEY `search` (`name`, `body`);
ALTER TABLE `post` ADD FULLTEXT KEY `search` (`name`, `body`);
ALTER TABLE `post` ADD CONSTRAINT `post_user` FOREIGN KEY (`user`)
	REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
