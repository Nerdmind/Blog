<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Standard: Internationalization [DE]         Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# This file contains template internationalization strings for the DE language #
# and can also override the existing core internationalization strings.        #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

#===============================================================================
# Date format
#===============================================================================
$LANGUAGE['date_format'] = '[D].[M].[Y] [H]:[I]';

#===============================================================================
# Main navigation strings
#===============================================================================
$LANGUAGE['navigation_home_text'] = 'Home';
$LANGUAGE['navigation_home_desc'] = '%s';
$LANGUAGE['navigation_search_text'] = 'Suche';
$LANGUAGE['navigation_search_desc'] = 'Volltextsuche';

#===============================================================================
# Start page title and description
#===============================================================================
$LANGUAGE['home_heading_text'] = 'Willkommen bei %s';
$LANGUAGE['home_heading_desc'] = 'Hallo! Hier siehst du erst einmal die letzten %d veröffentlichten Beiträge. Viel Spaß!';

#===============================================================================
# Item overview description
#===============================================================================
$LANGUAGE['post_overview_heading_desc'] = '[Seite: <b>%d</b>] Hier siehst du alle veröffentlichten <strong>Beiträge</strong> nach dem Zeitpunkt der Veröffentlchung sortiert.';
$LANGUAGE['page_overview_heading_desc'] = '[Seite: <b>%d</b>] Hier siehst du alle veröffentlichten <strong>Seiten</strong> nach dem Zeitpunkt der Veröffentlchung sortiert.';
$LANGUAGE['user_overview_heading_desc'] = '[Seite: <b>%d</b>] Hier siehst du alle vorhandenen <strong>Benutzer</strong> nach dem Zeitpunkt der Erstellung sortiert.';

#===============================================================================
# Item main description
#===============================================================================
$LANGUAGE['post_main_heading_desc'] = 'Von: %s (veröffentlicht am: <em>%s</em>)';
$LANGUAGE['page_main_heading_desc'] = 'Von: %s (veröffentlicht am: <em>%s</em>)';
$LANGUAGE['user_main_heading_desc'] = 'Bisher wurden von »%s« insgesamt <b>%d</b> Beiträge und <b>%d</b> Seiten veröffentlicht.';

#===============================================================================
# Search request title and description
#===============================================================================
$LANGUAGE['search_base_heading_text'] = 'Volltextsuche';
$LANGUAGE['search_base_heading_desc'] = 'Wenn du einen bestimmten <strong>Beitrag</strong> suchst, dann kann dir die <a href="https://dev.mysql.com/doc/refman/5.5/en/fulltext-boolean.html" target="_blank">Volltext-Suchfunktion</a> der MySQL-Datenbank bestimmt weiterhelfen.';

#===============================================================================
# Search result title and description
#===============================================================================
$LANGUAGE['search_result_heading_text'] = 'Suchergebnisse für <code>%s</code>';
$LANGUAGE['search_result_heading_desc'] = 'Herzlichen Glückwunsch, deine Suchanfrage scheint erfolgreich gewesen zu sein!';

#===============================================================================
# Search form placeholder text
#===============================================================================
$LANGUAGE['search_form_placeholder'] = 'Suchbegriff eingeben …';

#===============================================================================
# Error 403
#===============================================================================
$LANGUAGE['403_heading_text'] = 'Zugriff verweigert';
$LANGUAGE['403_heading_desc'] = 'Der Zugriff auf diese Ressource wurde dir verweigert, da du die dafür notwendigen Berechtigungen nicht besitzt.';

#===============================================================================
# Error 404
#===============================================================================
$LANGUAGE['404_heading_text'] = 'Nicht gefunden';
$LANGUAGE['404_heading_desc'] = 'Die angeforderte Ressource konnte nicht gefunden werden.';
?>