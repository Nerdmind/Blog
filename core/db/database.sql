-- =============================================================================
-- Internal information table for migrations
-- =============================================================================
CREATE TABLE `migration` (`schema_version` smallint(4) NOT NULL)
	ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO `migration` (`schema_version`) VALUES (6);

-- =============================================================================
-- Table structure for category entities
-- =============================================================================
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

-- =============================================================================
-- Table structure for page entities
-- =============================================================================
CREATE TABLE `page` (
	`id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`time_insert` datetime NOT NULL,
	`time_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`user` tinyint(3) UNSIGNED NOT NULL,
	`slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
	`name` varchar(100) NOT NULL,
	`body` text NOT NULL,
	`argv` varchar(250) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `time_insert` (`time_insert`),
	UNIQUE KEY `slug` (`slug`),
	KEY `page_user` (`user`),
	FULLTEXT KEY `search` (`name`, `body`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================================================
-- Table structure for post entities
-- =============================================================================
CREATE TABLE `post` (
	`id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	`time_insert` datetime NOT NULL,
	`time_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`user` tinyint(3) UNSIGNED NOT NULL,
	`category` tinyint(3) UNSIGNED DEFAULT NULL,
	`slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
	`name` varchar(100) NOT NULL,
	`body` text NOT NULL,
	`argv` varchar(250) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `time_insert` (`time_insert`),
	UNIQUE KEY `slug` (`slug`),
	KEY `post_user` (`user`),
	KEY `post_category` (`category`),
	FULLTEXT KEY `search` (`name`, `body`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================================================
-- Table structure for user entities
-- =============================================================================
CREATE TABLE `user` (
	`id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT,
	`time_insert` datetime NOT NULL,
	`time_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`slug` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
	`username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
	`password` char(64) CHARACTER SET latin1 DEFAULT NULL,
	`fullname` varchar(40) NOT NULL,
	`mailaddr` varchar(60) NOT NULL,
	`body` text NOT NULL,
	`argv` varchar(250) DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE KEY `time_insert` (`time_insert`),
	UNIQUE KEY `slug` (`slug`),
	UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================================================
-- Add foreign keys for entity relationships
-- =============================================================================
ALTER TABLE `category` ADD CONSTRAINT `category_parent` FOREIGN KEY (`parent`)
	REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `page` ADD CONSTRAINT `page_user` FOREIGN KEY (`user`)
	REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `post` ADD CONSTRAINT `post_user` FOREIGN KEY (`user`)
	REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `post` ADD CONSTRAINT `post_category` FOREIGN KEY (`category`)
	REFERENCES `category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- =============================================================================
-- Insert some demo data
-- =============================================================================
INSERT INTO `user` (`id`, `time_insert`, `time_update`, `slug`, `username`, `password`, `fullname`, `mailaddr`, `body`, `argv`) VALUES
(1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'john-doe', 'ChangeMe', '$2y$10$jH48L1K1y9dB303aI2biN.ob0biZDuUbMxPKadi3wDqOIxj6yNT6K', 'John Doe', 'mail@example.org', 'Hello, I\'m John Doe! 😎\r\n\r\nA user like me can currently considered more of an *author* which can be assigned to many posts (and pages). There is no user permission system in the administration area currently, so every user with valid login credentials can do anything!', NULL);
INSERT INTO `category` (`id`, `time_insert`, `time_update`, `parent`, `slug`, `name`, `body`, `argv`) VALUES
(1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL, 'examples', 'Examples', 'This is a category. Only posts can be assigned to a category, but it is not necessary, so categories are optional. If you want to use them, you have a lot of space here to describe the content of your category, include images and other stuff like in a posts content. It is even possible to nest categories *unlimited*, but for the sake of user experience, I recommend *not* overdoing it. 😂', NULL);
INSERT INTO `page` (`id`, `time_insert`, `time_update`, `user`, `slug`, `name`, `body`, `argv`) VALUES
(1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 'example-page', 'Example page', 'OK. You discovered that there is also a page functionality. But what is the difference between a **page** and a **post**?\r\n\r\n## The difference\r\nA page is intended for \"timeless\" objects like your imprint, the privacy-policy, the about page, your FAQ\'s and similar stuff. Posts [appear in the RSS feed]({BASE_URL: \"feed/\"}), pages do not. Posts can be assigned {CATEGORY: 1, \"to a category\"}, pages cannot. Posts [can be searched]({BASE_URL: \"search/\"}) by the visitor, pages cannot. Besides this, there is not much more difference between a post and a page currently.', NULL);
INSERT INTO `post` (`id`, `time_insert`, `time_update`, `user`, `category`, `slug`, `name`, `body`, `argv`) VALUES
(1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 1, 1, 'hello-world', 'Hello World!', 'Welcome! This is the automatically created first post on your new blog. You can type [*Markdown*](https://daringfireball.net/projects/markdown/) into the content editor to format your content. This system uses [*Parsedown*](https://parsedown.org/) internally, a very fast *Markdown* parser/transformer library written in PHP!\r\n\r\nIn this post you can see several examples to [format your content with *Markdown*](https://daringfireball.net/projects/markdown/syntax) and especially how to use the customizable [*content functions*](https://github.com/Nerdmind/Blog/wiki/Content-functions) provided by this blogging system. After you are familiar with the text formatting and done with the exploration of your new blogging system, you can delete this post and create your own one. 😃\r\n\r\n![Demo image: Computer Guy (Public Domain)]({FILE_URL: \"image/content/computer-guy-public-domain.svg\"})\r\n\r\n## How to cross-reference items in your content?\r\nIf you want to reference another item (e.g. a post or a category) in your content, please do not put the URL to it hard coded into the editor. Consider what happens if you change your blog\'s address (or just the base directory) in the future. You would need to change all the hard coded URLs in your content which is inflexible and not cool. Therefore, you can use the following so-called *content functions* to link an item or a resource of your installation dynamically within your content.\r\n\r\n### Example\r\nReference another post:\r\n> Hello there! Check out this post: {POST: 1}\r\n\r\n### Example\r\nReference a category with customized link text:\r\n> Hello there! Check out {CATEGORY: 1, \"the demo category\"}!\r\n\r\n## How to dynamically link to other resources?\r\nYou can link any other resource (e.g. a file) which is located anywhere within your document root dynamically, either relative to your base directory (the installation directory) or relative to the `rsrc` directory where you store your files, images and other stuff. The `BASE_URL` and `FILE_URL` *content functions* will return the pure plain text URL (extended by the first argument).\r\n\r\n### Example\r\n> Hello there. Check out [the README]({BASE_URL: \"readme.md\"})!\r\n\r\n## How to format content with Markdown?\r\nTo see how this post is formatted with *Markdown*, just open this post in the editor in the administration area. 😉', NULL);
