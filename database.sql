-- =============================================================================
-- Table structure for page items
-- =============================================================================
CREATE TABLE `page` (
	`id` smallint(6) NOT NULL,
	`time_insert` datetime NOT NULL,
	`time_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`user` tinyint(4) NOT NULL,
	`slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
	`name` varchar(100) NOT NULL,
	`body` text NOT NULL,
	`argv` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================================================
-- Table structure for post items
-- =============================================================================
CREATE TABLE `post` (
	`id` smallint(6) NOT NULL,
	`time_insert` datetime NOT NULL,
	`time_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`user` tinyint(4) NOT NULL,
	`slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
	`name` varchar(100) NOT NULL,
	`body` text NOT NULL,
	`argv` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================================================
-- Table structure for user items
-- =============================================================================
CREATE TABLE `user` (
	`id` tinyint(4) NOT NULL,
	`time_insert` datetime NOT NULL,
	`time_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`slug` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
	`username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
	`password` char(64) CHARACTER SET latin1 DEFAULT NULL,
	`fullname` varchar(40) NOT NULL,
	`mailaddr` varchar(60) NOT NULL,
	`body` text NOT NULL,
	`argv` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================================================
-- Insert demo page, post and user
-- =============================================================================
INSERT INTO `page` (`id`, `time_insert`, `time_update`, `user`, `slug`, `name`, `body`, `argv`) VALUES
(1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 'example-page', 'Example Page', 'OK. You discovered that there is also a page functionality. But what is the difference between a **page** and a **post**? This is simple: There is not really much difference. But you can style posts and pages within the templates CSS completely independent from each other. For example, use **pages** for things like your imprint, your terms of use, your FAQ or other stuff. And **posts** for your main blog posts. A **page** (and also a **user**) has exactly the same functionality as already described within the [first post]({POST[1]})! 8)', NULL);
INSERT INTO `post` (`id`, `time_insert`, `time_update`, `user`, `slug`, `name`, `body`, `argv`) VALUES
(1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 'hello-world', 'Hello World!', 'Hello! This is the automatically generated first post on your new blog installation. You can type [Markdown](https://daringfireball.net/projects/markdown/) plaintext into the editor to format your content as you want. In this post you can see several examples to [format your content with Markdown](https://daringfireball.net/projects/markdown/syntax) and with the special features provided by this blog application. After you are familiar with the text formatting and done with the exploration of your new blog application, you can delete this post and create your own. Have fun! :)\r\n\r\n![Demo image: Computer Guy (Public Domain)]({FILE[\"image/content/computer-guy-public-domain.svg\"]})\r\n\r\n## Parsing emoticons (if `POST.EMOTICONS` is `TRUE` within your `configuration.php`)\r\n> You can insert one or more of the following emoticons into your posts by typing the emoticon as simple ASCII text. The emoticon parser will convert your ASCII emoticon to the HTML multibyte unicode equivalent. Each emoticon comes with an further explanation if you just hold your mouse over a emoticons face:  \r\n> :) :( :D :P :O ;) ;( :| :X :/ 8) :S xD ^^\r\n\r\n## Dynamic internal URLs for items\r\nIf you want to link an item, please do not put the URL to the item hardcoded into your content! What if you want to change your site address (or the base directory) in the future? Then you have to change all links in your content. This is not cool! Thus, you can use the following code **without spaces between the braces** by knowing the ID of an item to link it dynamically:\r\n\r\n1. Example: `{ POST[1] }`  \r\n{POST[1]}\r\n\r\n2. Example: `{ PAGE[1] }`  \r\n{PAGE[1]}\r\n\r\n3. Example: `{ USER[1] }`  \r\n{USER[1]}\r\n\r\n## Dynamic internal URLs for other resources\r\nThis also applies to any other resource that exists in the blog system and that you want to link to! You can link any other resource dynamically either relative to your base directory or relative to your resource directory for static content:\r\n\r\n* Example: `{ BASE[\"foo/bar/\"] }`  \r\n{BASE[\"foo/bar/\"]}\r\n\r\n* Example: `{ FILE[\"foo/bar/\"] }`  \r\n{FILE[\"foo/bar/\"]}\r\n\r\n### Anywhere …\r\nYou can use these codes anywhere in your markdown plaintext. This codes will be pre-parsed before the markdown parser gets the content. If the markdown parser begins then all codes already have been converted into the URLs.', NULL);
INSERT INTO `user` (`id`, `time_insert`, `time_update`, `slug`, `username`, `password`, `fullname`, `mailaddr`, `body`, `argv`) VALUES
(1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'change-me', 'ChangeMe', '$2y$10$jH48L1K1y9dB303aI2biN.ob0biZDuUbMxPKadi3wDqOIxj6yNT6K', 'John Doe', 'mail@example.org', 'Describe yourself.', NULL);

-- =============================================================================
-- Add keys for table columns
-- =============================================================================
ALTER TABLE `page` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `time_insert` (`time_insert`), ADD UNIQUE KEY `slug` (`slug`), ADD KEY `page_user` (`user`);
ALTER TABLE `post` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `time_insert` (`time_insert`), ADD UNIQUE KEY `slug` (`slug`), ADD KEY `post_user` (`user`);
ALTER TABLE `user` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `time_insert` (`time_insert`), ADD UNIQUE KEY `slug` (`slug`), ADD UNIQUE KEY `username` (`username`);

-- =============================================================================
-- Add FULLTEXT indexes for table columns
-- =============================================================================
ALTER TABLE `page` ADD FULLTEXT KEY `search` (`name`, `body`);
ALTER TABLE `post` ADD FULLTEXT KEY `search` (`name`, `body`);

-- =============================================================================
-- Add AUTO_INCREMENT for primary keys
-- =============================================================================
ALTER TABLE `page` MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;
ALTER TABLE `post` MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user` MODIFY `id` tinyint(4)  NOT NULL AUTO_INCREMENT;

-- =============================================================================
-- Add foreign keys for data integrity
-- =============================================================================
ALTER TABLE `page` ADD CONSTRAINT `page_user` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `post` ADD CONSTRAINT `post_user` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;