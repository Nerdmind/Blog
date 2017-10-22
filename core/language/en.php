<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Internationalization [EN]                  [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# This file contains core internationalization strings for the EN language. If #
# you are a translator, please only use the original EN language file for your #
# translation and open a pull request on GitHub or send your language file via #
# email back to <code@nerdmind.de>.                                            #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

#===============================================================================
# Date element names
#===============================================================================
$LANGUAGE['date_d'] = 'Day';
$LANGUAGE['date_m'] = 'Month';
$LANGUAGE['date_y'] = 'Year';

#===============================================================================
# Time element names
#===============================================================================
$LANGUAGE['time_h'] = 'Hour';
$LANGUAGE['time_m'] = 'Minute';
$LANGUAGE['time_s'] = 'Second';

#===============================================================================
# Day names
#===============================================================================
$LANGUAGE['day_0'] = 'Monday';
$LANGUAGE['day_1'] = 'Tuesday';
$LANGUAGE['day_2'] = 'Wednesday';
$LANGUAGE['day_3'] = 'Thursday';
$LANGUAGE['day_4'] = 'Friday';
$LANGUAGE['day_5'] = 'Saturday';
$LANGUAGE['day_6'] = 'Sunday';

#===============================================================================
# Month names
#===============================================================================
$LANGUAGE['month_01'] = 'January';
$LANGUAGE['month_02'] = 'February';
$LANGUAGE['month_03'] = 'March';
$LANGUAGE['month_04'] = 'April';
$LANGUAGE['month_05'] = 'May';
$LANGUAGE['month_06'] = 'June';
$LANGUAGE['month_07'] = 'July';
$LANGUAGE['month_08'] = 'August';
$LANGUAGE['month_09'] = 'September';
$LANGUAGE['month_10'] = 'October';
$LANGUAGE['month_11'] = 'November';
$LANGUAGE['month_12'] = 'December';

#===============================================================================
# Emoticon explanations
#===============================================================================
$LANGUAGE['emoticon_1F60A'] = 'Smiling face with smiling eyes';
$LANGUAGE['emoticon_1F61E'] = 'Disappointed face';
$LANGUAGE['emoticon_1F603'] = 'Smiling face with open mouth';
$LANGUAGE['emoticon_1F61B'] = 'Face with stuck-out tongue';
$LANGUAGE['emoticon_1F632'] = 'Astonished face';
$LANGUAGE['emoticon_1F609'] = 'Winking face';
$LANGUAGE['emoticon_1F622'] = 'Crying face';
$LANGUAGE['emoticon_1F610'] = 'Neutral face';
$LANGUAGE['emoticon_1F635'] = 'Dizzy face';
$LANGUAGE['emoticon_1F612'] = 'Unamused face';
$LANGUAGE['emoticon_1F60E'] = 'Smiling face with sunglasses';
$LANGUAGE['emoticon_1F61F'] = 'Worried face';
$LANGUAGE['emoticon_1F602'] = 'Face with tears of joy';
$LANGUAGE['emoticon_1F604'] = 'Smiling face with open mouth and smiling eyes';

#===============================================================================
# Error messages
#===============================================================================
$LANGUAGE['error_security_csrf'] = 'The security token does not matches the security token at server.';
$LANGUAGE['error_database_exec'] = 'An unexpected error occurred while communicating with the database.';

#===============================================================================
# Fulltext search
#===============================================================================
$LANGUAGE['search_no_results'] = 'Sorry, there are no search results for "%s".';

#===============================================================================
# Authentication
#===============================================================================
$LANGUAGE['authentication_failure'] = 'The username or password is incorrect.';

#===============================================================================
# Items [singular]
#===============================================================================
$LANGUAGE['page'] = 'Page';
$LANGUAGE['post'] = 'Post';
$LANGUAGE['user'] = 'User';

#===============================================================================
# Items [plural]
#===============================================================================
$LANGUAGE['pages'] = 'Pages';
$LANGUAGE['posts'] = 'Posts';
$LANGUAGE['users'] = 'Users';

#===============================================================================
# Actions
#===============================================================================
$LANGUAGE['select'] = 'Show';
$LANGUAGE['insert'] = 'Create';
$LANGUAGE['update'] = 'Edit';
$LANGUAGE['delete'] = 'Delete';
$LANGUAGE['search'] = 'Search';
$LANGUAGE['remove'] = 'Remove';

#===============================================================================
# Previous items
#===============================================================================
$LANGUAGE['prev_page'] = 'Previous page';
$LANGUAGE['prev_post'] = 'Previous post';
$LANGUAGE['prev_user'] = 'Previous user';

#===============================================================================
# Next items
#===============================================================================
$LANGUAGE['next_page'] = 'Next page';
$LANGUAGE['next_post'] = 'Next post';
$LANGUAGE['next_user'] = 'Next user';

#===============================================================================
# Item overview
#===============================================================================
$LANGUAGE['page_overview'] = 'Page overview';
$LANGUAGE['post_overview'] = 'Post overview';
$LANGUAGE['user_overview'] = 'User overview';

#===============================================================================
# Items select
#===============================================================================
$LANGUAGE['select_page'] = 'Show page';
$LANGUAGE['select_post'] = 'Show post';
$LANGUAGE['select_user'] = 'Show user';

#===============================================================================
# Items insert
#===============================================================================
$LANGUAGE['insert_page'] = 'Create page';
$LANGUAGE['insert_post'] = 'Create post';
$LANGUAGE['insert_user'] = 'Create user';

#===============================================================================
# Items update
#===============================================================================
$LANGUAGE['update_page'] = 'Edit page';
$LANGUAGE['update_post'] = 'Edit post';
$LANGUAGE['update_user'] = 'Edit user';

#===============================================================================
# Items delete
#===============================================================================
$LANGUAGE['delete_page'] = 'Delete page';
$LANGUAGE['delete_post'] = 'Delete post';
$LANGUAGE['delete_user'] = 'Delete user';

#===============================================================================
# Items search
#===============================================================================
$LANGUAGE['search_page'] = 'Search in pages';
$LANGUAGE['search_post'] = 'Search in posts';

#===============================================================================
# Item insert titles
#===============================================================================
$LANGUAGE['title_page_insert'] = $LANGUAGE['insert_page'];
$LANGUAGE['title_post_insert'] = $LANGUAGE['insert_post'];
$LANGUAGE['title_user_insert'] = $LANGUAGE['insert_user'];

#===============================================================================
# Item update titles
#===============================================================================
$LANGUAGE['title_page_update'] = $LANGUAGE['update_page'];
$LANGUAGE['title_post_update'] = $LANGUAGE['update_post'];
$LANGUAGE['title_user_update'] = $LANGUAGE['update_user'];

#===============================================================================
# Item delete titles
#===============================================================================
$LANGUAGE['title_page_delete'] = $LANGUAGE['delete_page'];
$LANGUAGE['title_post_delete'] = $LANGUAGE['delete_post'];
$LANGUAGE['title_user_delete'] = $LANGUAGE['delete_user'];

#===============================================================================
# Item search titles
#===============================================================================
$LANGUAGE['title_page_search'] = $LANGUAGE['search_page'];
$LANGUAGE['title_post_search'] = $LANGUAGE['search_post'];

#===============================================================================
# Item overview titles
#===============================================================================
$LANGUAGE['title_page_overview'] = "{$LANGUAGE['page_overview']} [%d]";
$LANGUAGE['title_post_overview'] = "{$LANGUAGE['post_overview']} [%d]";
$LANGUAGE['title_user_overview'] = "{$LANGUAGE['user_overview']} [%d]";

#===============================================================================
# Search titles
#===============================================================================
$LANGUAGE['title_search_request'] = 'Fulltext search';
$LANGUAGE['title_search_results'] = 'Results for "%s"';

#===============================================================================
# Feed names
#===============================================================================
$LANGUAGE['feed_name_items'] = '%s [all content]';
$LANGUAGE['feed_name_pages'] = '%s [only pages]';
$LANGUAGE['feed_name_posts'] = '%s [only posts]';
?>