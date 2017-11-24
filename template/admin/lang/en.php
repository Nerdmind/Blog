<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Admin: Internationalization [EN]           [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# This file contains template internationalization strings for the EN language #
# and can also override the existing core internationalization strings.        #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

#===============================================================================
# Date format
#===============================================================================
$LANGUAGE['date_format'] = '[Y]-[M]-[D]';

#===============================================================================
# Item last text
#===============================================================================
$LANGUAGE['last_post'] = 'Last post';
$LANGUAGE['last_page'] = 'Last page';
$LANGUAGE['last_user'] = 'Last user';

#===============================================================================
# Insert item description
#===============================================================================
$LANGUAGE['insert_page_desc'] = 'Here you can create and publish a new page.';
$LANGUAGE['insert_post_desc'] = 'Here you can create and publish a new post.';
$LANGUAGE['insert_user_desc'] = 'Here you can create and publish a new user.';

#===============================================================================
# Update item description
#===============================================================================
$LANGUAGE['update_page_desc'] = 'Here you can edit an existing page and save the changes.';
$LANGUAGE['update_post_desc'] = 'Here you can edit an existing post and save the changes.';
$LANGUAGE['update_user_desc'] = 'Here you can edit an existing user and save the changes.';

#===============================================================================
# Delete item description
#===============================================================================
$LANGUAGE['delete_page_desc'] = 'If you do not need this page anymore, you can permanently delete it by clicking the following button.';
$LANGUAGE['delete_post_desc'] = 'If you do not need this post anymore, you can permanently delete it by clicking the following button.';
$LANGUAGE['delete_user_desc'] = 'If you do not need this user anymore, you can permanently delete it by clicking the following button.';

#===============================================================================
# Search item description
#===============================================================================
$LANGUAGE['search_page_desc'] = 'Here you can search a page with the <em>boolean full-text search</em> (see <a href="https://dev.mysql.com/doc/refman/5.6/en/fulltext-boolean.html">MySQL documentation</a>).';
$LANGUAGE['search_post_desc'] = 'Here you can search a post with the <em>boolean full-text search</em> (see <a href="https://dev.mysql.com/doc/refman/5.6/en/fulltext-boolean.html">MySQL documentation</a>).';

#===============================================================================
# Item overview description
#===============================================================================
$LANGUAGE['overview_page_desc'] = 'Here you can see all existing pages.';
$LANGUAGE['overview_post_desc'] = 'Here you can see all existing posts.';
$LANGUAGE['overview_user_desc'] = 'Here you can see all existing users.';

#===============================================================================
# Dashboard
#===============================================================================
$LANGUAGE['overview_dashboard_text'] = 'Dashboard';
$LANGUAGE['overview_dashboard_desc'] = 'Welcome to the administration area. Here you can manage your content.';

#===============================================================================
# Database
#===============================================================================
$LANGUAGE['overview_database_text'] = 'Database';
$LANGUAGE['overview_database_desc'] = 'Perform database operations with SQL commands.';

#===============================================================================
# Authentication
#===============================================================================
$LANGUAGE['authentication_text'] = 'Authentication';
$LANGUAGE['authentication_desc'] = 'To manage your content, you have to authenticate yourself first.';

#===============================================================================
# No items exists
#===============================================================================
$LANGUAGE['home_no_pages'] = 'There is no last page to display here. You have to insert a new page first.';
$LANGUAGE['home_no_posts'] = 'There is no last post to display here. You have to insert a new post first.';
$LANGUAGE['home_no_users'] = 'There is no last user to display here. You have to insert a new user first.';

#===============================================================================
# Delete user warning
#===============================================================================
$LANGUAGE['delete_user_warning'] = '<strong>WARNING</strong>: If you delete this user, all posts and pages belonging to this user will also be deleted!';

#===============================================================================
# Database warning
#===============================================================================
$LANGUAGE['database_warning'] = 'Some commands can have dangerous effects if you do not know what you are doing!';

#===============================================================================
# Error 403
#===============================================================================
$LANGUAGE['403_heading_text'] = 'Access denied';
$LANGUAGE['403_heading_desc'] = 'You are denied to access this resource because you do not have the necessary permissions.';

#===============================================================================
# Error 404
#===============================================================================
$LANGUAGE['404_heading_text'] = 'Not found';
$LANGUAGE['404_heading_desc'] = 'The requested resource could not be found on this server.';

#===============================================================================
# "Are you sure?" question
#===============================================================================
$LANGUAGE['sure'] = 'Are you sure?';

#===============================================================================
# Login and logout
#===============================================================================
$LANGUAGE['login'] = 'Login';
$LANGUAGE['logout'] = 'Logout';

#===============================================================================
# Placeholders
#===============================================================================
$LANGUAGE['placeholder_search'] = 'Enter search term …';

#===============================================================================
# Labels
#===============================================================================
$LANGUAGE['label_slug'] = 'Slug';
$LANGUAGE['label_user'] = 'User';
$LANGUAGE['label_name'] = 'Title';
$LANGUAGE['label_insert'] = 'Created';
$LANGUAGE['label_update'] = 'Updated';
$LANGUAGE['label_fullname'] = 'Name';
$LANGUAGE['label_mailaddr'] = 'Email';
$LANGUAGE['label_username'] = 'Username';
$LANGUAGE['label_password'] = 'Password';
$LANGUAGE['label_language'] = 'Language';

#===============================================================================
# Markdown
#===============================================================================
$LANGUAGE['markdown_bold'] = 'Bold';
$LANGUAGE['markdown_italic'] = 'Italic';
$LANGUAGE['markdown_heading'] = 'Heading';
$LANGUAGE['markdown_link'] = 'Link';
$LANGUAGE['markdown_image'] = 'Image';
$LANGUAGE['markdown_code'] = 'Code block';
$LANGUAGE['markdown_quote'] = 'Quote';
$LANGUAGE['markdown_list_ul'] = 'List [unordered]';
$LANGUAGE['markdown_list_ol'] = 'List [ordered]';
?>