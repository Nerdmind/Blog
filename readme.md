# PHP7 blogging application
Easy blogging application written with PHP7! The application comes with a fulltext search functionality for posts and with customizable themes and languages. You can build your own theme if the default theme does not satisfy you.

**Notice:**  
This project is currently **not in active development**, but the *master* branch is considered to be stable.

![Default theme](https://nmnd.de/file/p/github-blog/default-theme.png)

## Administration interface
![Administration interface](https://nmnd.de/file/p/github-blog/admin-template-2.png)

## Content editor
![Content editor](https://nmnd.de/file/p/github-blog/admin-template-1.png)

## Installation
1. Download the repository and extract it to the target directory where it should be installed.
2. Create your MySQL database and import the `database.sql` file.
3. Rename `core/configuration-example.php` to `core/configuration.php` and customize the configuration and set in any case the settings for the database connection.
4. Navigate your browser to `/admin/auth.php` and authenticate with the default username `ChangeMe` and the password `changeme` (please note that the username is case-sensitive).

## Documentation
You can find more information about the configuration and customization in the wiki:

* [GitHub](https://github.com/Nerdmind/Blog/wiki)
* [Mirror](https://code.nerdmind.de/blog/wiki/)