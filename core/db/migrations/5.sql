ALTER TABLE `page` DROP FOREIGN KEY `page_user`;
ALTER TABLE `post` DROP FOREIGN KEY `post_user`;

ALTER TABLE `page` MODIFY `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	MODIFY `user` TINYINT UNSIGNED NOT NULL;
ALTER TABLE `post` MODIFY `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	MODIFY `user` TINYINT UNSIGNED NOT NULL;
ALTER TABLE `user` MODIFY `id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `page` ADD CONSTRAINT `page_user` FOREIGN KEY (`user`)
	REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `post` ADD CONSTRAINT `post_user` FOREIGN KEY (`user`)
	REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
