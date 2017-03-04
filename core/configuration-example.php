<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Application configuration                  [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# [see documentation]                                                          #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

#===============================================================================
# Core configuration
#===============================================================================
Application::set('CORE.LANGUAGE', 'en');
Application::set('CORE.SEND_304', FALSE);

#===============================================================================
# Blog configuration
#===============================================================================
Application::set('BLOGMETA.NAME', 'My Techblog');
Application::set('BLOGMETA.DESC', '[a creative description]');
Application::set('BLOGMETA.HOME', 'Home');
Application::set('BLOGMETA.MAIL', 'mail@example.org');
Application::set('BLOGMETA.LANG', 'en');

#===============================================================================
# Settings for database connection
#===============================================================================
Application::set('DATABASE.HOSTNAME', 'localhost');
Application::set('DATABASE.BASENAME', 'blog');
Application::set('DATABASE.USERNAME', '');
Application::set('DATABASE.PASSWORD', '');

#===============================================================================
# Backend configuration
#===============================================================================
Application::set('ADMIN.TEMPLATE', 'admin');
Application::set('ADMIN.LANGUAGE', Application::get('CORE.LANGUAGE'));

#===============================================================================
# Settings for template configuration
#===============================================================================
Application::set('TEMPLATE.NAME', 'standard');
Application::set('TEMPLATE.LANG', Application::get('CORE.LANGUAGE'));

#===============================================================================
# Protocol, hostname and path for this installation
#===============================================================================
Application::set('PATHINFO.PROT', $_SERVER['REQUEST_SCHEME']);
Application::set('PATHINFO.HOST', $_SERVER['HTTP_HOST']);
Application::set('PATHINFO.BASE', '');

#===============================================================================
# Enable or disable the use of slug URLs for item permalinks
#===============================================================================
Application::set('PAGE.SLUG_URLS', TRUE);
Application::set('POST.SLUG_URLS', TRUE);
Application::set('USER.SLUG_URLS', TRUE);

#===============================================================================
# Number of items to display on feed and overview sites
#===============================================================================
Application::set('PAGE.LIST_SIZE', 10);
Application::set('POST.LIST_SIZE', 10);
Application::set('USER.LIST_SIZE', 10);
Application::set('PAGE.FEED_SIZE', 25);
Application::set('POST.FEED_SIZE', 25);

#===============================================================================
# Settings for item URL generation (you have to change the .htaccess as well!)
#===============================================================================
Application::set('PAGE.DIRECTORY', 'page');
Application::set('POST.DIRECTORY', 'post');
Application::set('USER.DIRECTORY', 'user');

#===============================================================================
# Enable or disable the use of emoticons for item content
#===============================================================================
Application::set('PAGE.EMOTICONS', TRUE);
Application::set('POST.EMOTICONS', TRUE);
Application::set('USER.EMOTICONS', TRUE);

#===============================================================================
# Number of characters to display in the items <meta> description
#===============================================================================
Application::set('PAGE.DESCRIPTION_SIZE', 200);
Application::set('POST.DESCRIPTION_SIZE', 200);
Application::set('USER.DESCRIPTION_SIZE', 200);

#===============================================================================
# "ORDER BY" clause for item sorting on feed and overview sites
#===============================================================================
Application::set('PAGE.LIST_SORT', 'time_insert DESC');
Application::set('POST.LIST_SORT', 'time_insert DESC');
Application::set('USER.LIST_SORT', 'time_insert DESC');
Application::set('PAGE.FEED_SORT', 'time_insert DESC');
Application::set('POST.FEED_SORT', 'time_insert DESC');

#===============================================================================
# Item attributes used to generate the <guid> hash for feed items
#===============================================================================
Application::set('PAGE.FEED_GUID', ['id', 'time_insert']);
Application::set('POST.FEED_GUID', ['id', 'time_insert']);
?>