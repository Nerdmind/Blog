<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Internationalization [EN]         Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# This file contains template internationalization strings for the EN language #
# and can also override the existing core internationalization strings.        #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

#===============================================================================
# Date format
#===============================================================================
$LANGUAGE['date_format'] = '[Y]-[M]-[D] [H]:[I]';

#===============================================================================
# Main navigation strings
#===============================================================================
$LANGUAGE['navigation_home_text'] = 'Home';
$LANGUAGE['navigation_home_desc'] = '%s';
$LANGUAGE['navigation_search_text'] = 'Search';
$LANGUAGE['navigation_search_desc'] = 'Fulltext search';

#===============================================================================
# Start page title and description
#===============================================================================
$LANGUAGE['home_heading_text'] = 'Welcome to %s';
$LANGUAGE['home_heading_desc'] = 'Here you can see the last %d published posts. Have fun!';

#===============================================================================
# Item overview description
#===============================================================================
$LANGUAGE['post_overview_heading_desc'] = '[Page: <b>%d</b>] Here you can see all published <strong>posts</strong> ordered by the date of publication.';
$LANGUAGE['page_overview_heading_desc'] = '[Page: <b>%d</b>] Here you can see all published <strong>pages</strong> ordered by the date of publication.';
$LANGUAGE['user_overview_heading_desc'] = '[Page: <b>%d</b>] Here you can see all existing <strong>users</strong> ordered by the date of creation.';

#===============================================================================
# Item main description
#===============================================================================
$LANGUAGE['post_main_heading_desc'] = 'By: %s (published on: <em>%s</em>)';
$LANGUAGE['page_main_heading_desc'] = 'By: %s (published on: <em>%s</em>)';
$LANGUAGE['user_main_heading_desc'] = '»%s« has published a total count of <b>%d</b> posts and <b>%d</b> pages.';

#===============================================================================
# Search request title and description
#===============================================================================
$LANGUAGE['search_base_heading_text'] = 'Fulltext search';
$LANGUAGE['search_base_heading_desc'] = 'If you are looking for a specific <strong>post</strong>, then the <a href="https://dev.mysql.com/doc/refman/5.5/en/fulltext-boolean.html" target="_blank">full-text search function</a> of the MySQL database could help you.';

#===============================================================================
# Search result title and description
#===============================================================================
$LANGUAGE['search_result_heading_text'] = 'Search results for <code>%s</code>';
$LANGUAGE['search_result_heading_desc'] = 'Congratulations, your search request seems to have been successful!';

#===============================================================================
# Search form placeholder text
#===============================================================================
$LANGUAGE['search_form_placeholder'] = 'Enter search term …';

#===============================================================================
# Error 403
#===============================================================================
$LANGUAGE['403_heading_text'] = 'Access denied';
$LANGUAGE['403_heading_desc'] = 'You are denied to access this resource because you do not have the necessary permissions.';

#===============================================================================
# Error 404
#===============================================================================
$LANGUAGE['404_heading_text'] = 'Not found';
$LANGUAGE['404_heading_desc'] = 'The requested resource could not be found.';
?>