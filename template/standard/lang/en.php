<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Nerdmind: Internationalization [EN]         Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# This file contains template internationalization strings for the EN language #
# and is completely independend from the core internationalization strings.    #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

$LANGUAGE['date_format'] = '[Y]-[M]-[D] [H]:[I]';

$LANGUAGE['feed_only_posts'] = 'only posts';
$LANGUAGE['feed_only_pages'] = 'only pages';

$LANGUAGE['navigation_home_text'] = 'Home';
$LANGUAGE['navigation_home_desc'] = '%s';

$LANGUAGE['navigation_search_text'] = 'Search';
$LANGUAGE['navigation_search_desc'] = 'Fulltext search';

$LANGUAGE['home_heading_text'] = 'Welcome to %s';
$LANGUAGE['home_heading_desc'] = 'Here you can see the last 10 published posts. Have fun!';

$LANGUAGE['user_heading_text'] = '%s <code>[<a href="">%s</a>]</code>';
$LANGUAGE['user_heading_desc'] = '%s has published a total count of <b>%d</b> posts and <b>%d</b> pages.';

$LANGUAGE['post_base_heading_desc'] = '[Page: <b>%d</b>] Here you can see all published <strong>posts</strong> ordered by the date of publication.';
$LANGUAGE['page_base_heading_desc'] = '[Page: <b>%d</b>] Here you can see all published <strong>pages</strong> ordered by the date of publication.';
$LANGUAGE['user_base_heading_desc'] = '[Page: <b>%d</b>] Here you can see all existing <strong>users</strong> ordered by the date of creation.';

$LANGUAGE['post_main_heading_text'] = '%s';
$LANGUAGE['post_main_heading_desc'] = 'By: %s (published on: <em>%s</em>)';

$LANGUAGE['page_main_heading_text'] = '%s';
$LANGUAGE['page_main_heading_desc'] = 'By: %s (published on: <em>%s</em>)';

$LANGUAGE['403_heading_text'] = 'Access denied';
$LANGUAGE['403_heading_desc'] = 'You are denied to access this resource because you do not have the necessary permissions.';

$LANGUAGE['404_heading_text'] = 'Not found';
$LANGUAGE['404_heading_desc'] = 'The requested resource could not be found on this server.';

$LANGUAGE['search_base_heading_text'] = 'Fulltext search';
$LANGUAGE['search_base_heading_desc'] = 'If you are looking for a specific <strong>post</strong>, then the <a href="https://dev.mysql.com/doc/refman/5.5/en/fulltext-boolean.html" target="_blank">full-text search function</a> of the MySQL database could help you.';

$LANGUAGE['search_result_heading_text'] = 'Search results for <code>%s</code>';
$LANGUAGE['search_result_heading_desc'] = 'Congratulations, your search request seems to have been successful!';

$LANGUAGE['search_form_placeholder'] = 'Enter search term …';
?>