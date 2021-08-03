# PHP7 blogging application
Easy blogging application written with PHP7! The application comes with a fulltext search functionality for posts and with customizable themes and languages. You can build your own theme if the default theme does not satisfy you.

![Default theme](https://nmnd.de/file/p/github-blog/default-theme.png)

## Administration interface
![Administration interface](https://nmnd.de/file/p/github-blog/admin-template-2.png)

## Content editor
![Content editor](https://nmnd.de/file/p/github-blog/admin-template-1.png)

## Requirements
* PHP version `>= 7.3`!
* MariaDB version `>= 10.2.2` or MySQL version `>= 8.0`!

## Installation
1. Clone the repository to the target directory (usually your *document root*). (Alternatively you also can download a specific release as ZIP archive and extract it to the target destination and skip step 2.)
2. Check out the latest tag (or the tag you wish) by running `git tag -l` and `git checkout <tag>`. If you wish to get the newest code and features which are not yet included in a release tag, you can use the `master` branch.
3. Create your MariaDB/MySQL database and import `core/db/database.sql`.
4. Copy `core/configuration-example.php` to `core/configuration.php` and customize the configuration and set in any case the settings for the database connection.
5. Navigate your browser to `/admin/auth.php` and authenticate with the default username `ChangeMe` and the password `changeme` (please note that the username is case-sensitive).

## Documentation
You can find more information about the configuration and customization in the wiki:

* [GitHub](https://github.com/Nerdmind/Blog/wiki)
* [Mirror](https://code.nerdmind.de/blog/wiki/)
