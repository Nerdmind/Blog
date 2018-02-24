# PHP7 blogging application
Easy blogging application written with PHP7! The application comes with a fulltext search functionality for posts and with customizable templates and languages. You can build your own template if the standard template does not satisfy you! You can see the application in action with a custom template on my private blog at [blog.nerdmind.de](https://blog.nerdmind.de/)! 

![Standard template](https://nmnd.de/file/p/github-blog/standard-template.png)

## Administration interface
![Administration interface](https://nmnd.de/file/p/github-blog/admin-template-2.png)

## Content editor
![Content editor](https://nmnd.de/file/p/github-blog/admin-template-1.png)

## Installation
1. Download the repository and extract it to the target directory where it should be installed.
2. Create your MySQL database and import the `database.sql` file.
3. Rename `core/configuration-example.php` to `core/configuration.php` and customize the configuration and set in any case the settings for the database connection.
4. Navigate your browser to `/admin/auth.php` and authenticate with the default username `ChangeMe` and the password `changeme` (please note that the username is case-sensitive).

## Wiki
More information about the configuration and customization on the **wiki** of this repository!