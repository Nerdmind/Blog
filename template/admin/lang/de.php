<?php
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
# Admin: Internationalization [DE]           [Thomas Lange <code@nerdmind.de>] #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#
#                                                                              #
# This file contains template internationalization strings for the DE language #
# and can also override the existing core internationalization strings.        #
#                                                                              #
#%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%#

#===============================================================================
# Date format
#===============================================================================
$LANGUAGE['date_format'] = '[D].[M].[Y]';

#===============================================================================
# Item last text
#===============================================================================
$LANGUAGE['last_post'] = 'Letzter Post';
$LANGUAGE['last_page'] = 'Letzte Seite';
$LANGUAGE['last_user'] = 'Letzter Benutzer';

#===============================================================================
# Insert item description
#===============================================================================
$LANGUAGE['insert_page_desc'] = 'Hier kannst du eine neue Seite erstellen und veröffentlichen.';
$LANGUAGE['insert_post_desc'] = 'Hier kannst du einen neuen Beitrag erstellen und veröffentlichen.';
$LANGUAGE['insert_user_desc'] = 'Hier kannst du einen neuen Benutzer erstellen und veröffentlichen.';

#===============================================================================
# Update item description
#===============================================================================
$LANGUAGE['update_page_desc'] = 'Hier kannst du eine vorhandene Seite bearbeiten und die Änderungen abspeichern.';
$LANGUAGE['update_post_desc'] = 'Hier kannst du einen vorhandenen Beitrag bearbeiten und die Änderungen abspeichern.';
$LANGUAGE['update_user_desc'] = 'Hier kannst du einen vorhandenen Benutzer bearbeiten und die Änderungen abspeichern.';

#===============================================================================
# Delete item description
#===============================================================================
$LANGUAGE['delete_page_desc'] = 'Falls du diese Seite nicht mehr benötigst kannst du sie über den folgenden Button permanent löschen.';
$LANGUAGE['delete_post_desc'] = 'Falls du diesen Beitrag nicht mehr benötigst kannst du ihn über den folgenden Button permanent löschen.';
$LANGUAGE['delete_user_desc'] = 'Falls du diesen Benutzer nicht mehr benötigst kannst du ihn über den folgenden Button permanent löschen.';

#===============================================================================
# Search item description
#===============================================================================
$LANGUAGE['search_page_desc'] = 'Hier kannst du mit der <em>booleschen Volltextsuche</em> eine Seite suchen (siehe <a href="https://dev.mysql.com/doc/refman/5.6/en/fulltext-boolean.html">MySQL-Dokumentation</a>).';
$LANGUAGE['search_post_desc'] = 'Hier kannst du mit der <em>booleschen Volltextsuche</em> einen Beitrag suchen (siehe <a href="https://dev.mysql.com/doc/refman/5.6/en/fulltext-boolean.html">MySQL-Dokumentation</a>).';

#===============================================================================
# Item overview description
#===============================================================================
$LANGUAGE['overview_page_desc'] = 'Hier siehst du alle vorhandenen Seiten.';
$LANGUAGE['overview_post_desc'] = 'Hier siehst du alle vorhandenen Beiträge.';
$LANGUAGE['overview_user_desc'] = 'Hier siehst du alle vorhandenen Benutzer.';

#===============================================================================
# Dashboard
#===============================================================================
$LANGUAGE['overview_dashboard_text'] = 'Dashboard';
$LANGUAGE['overview_dashboard_desc'] = 'Willkommen im Administrationsbereich. Hier kannst du deine Inhalte verwalten.';

#===============================================================================
# Database
#===============================================================================
$LANGUAGE['overview_database_text'] = 'Datenbank';
$LANGUAGE['overview_database_desc'] = 'Datenbankoperationen mit SQL-Befehlen durchführen.';

#===============================================================================
# Authentication
#===============================================================================
$LANGUAGE['authentication_text'] = 'Authentifizierung';
$LANGUAGE['authentication_desc'] = 'Um deine Inhalte zu verwalten musst du dich zuerst authentifizieren.';

#===============================================================================
# No items exists
#===============================================================================
$LANGUAGE['home_no_pages'] = 'Es gibt keine letzte Seite zum anzeigen hier. Du musst zuerst eine erstellen.';
$LANGUAGE['home_no_posts'] = 'Es gibt keinen letzten Beitrag zum anzeigen hier. Du musst zuerst einen erstellen.';
$LANGUAGE['home_no_users'] = 'Es gibt keinen letzten Benutzer zum anzeigen hier. Du musst zuerst einen erstellen.';

#===============================================================================
# Delete user warning
#===============================================================================
$LANGUAGE['delete_user_warning'] = '<strong>WARNUNG</strong>: Wenn du diesen Benutzer löschst werden alle ihm zugehörigen Beiträge und Seiten ebenfalls gelöscht!';

#===============================================================================
# Database warning
#===============================================================================
$LANGUAGE['database_warning'] = 'Manche Befehle können gefährliche Auswirkungen haben, wenn du nicht weißt, was du tust!';

#===============================================================================
# Error 403
#===============================================================================
$LANGUAGE['403_heading_text'] = 'Zugriff verweigert';
$LANGUAGE['403_heading_desc'] = 'Der Zugriff auf diese Ressource des Servers wurde dir verweigert, da du die dafür notwendigen Berechtigungen nicht besitzt.';

#===============================================================================
# Error 404
#===============================================================================
$LANGUAGE['404_heading_text'] = 'Nicht gefunden';
$LANGUAGE['404_heading_desc'] = 'Die angeforderte Ressource konnte auf diesem Server nicht gefunden werden.';

#===============================================================================
# "Are you sure?" question
#===============================================================================
$LANGUAGE['sure'] = 'Bist du sicher?';

#===============================================================================
# Login and logout
#===============================================================================
$LANGUAGE['login'] = 'Einloggen';
$LANGUAGE['logout'] = 'Ausloggen';

#===============================================================================
# Placeholders
#===============================================================================
$LANGUAGE['placeholder_search'] = 'Suchbegriff eingeben …';

#===============================================================================
# Labels
#===============================================================================
$LANGUAGE['label_slug'] = 'Slug';
$LANGUAGE['label_user'] = 'Benutzer';
$LANGUAGE['label_name'] = 'Titel';
$LANGUAGE['label_insert'] = 'Erstellt';
$LANGUAGE['label_update'] = 'Bearbeitet';
$LANGUAGE['label_fullname'] = 'Name';
$LANGUAGE['label_mailaddr'] = 'E-Mail';
$LANGUAGE['label_username'] = 'Username';
$LANGUAGE['label_password'] = 'Passwort';
$LANGUAGE['label_language'] = 'Sprache';

#===============================================================================
# Markdown
#===============================================================================
$LANGUAGE['markdown_bold'] = 'Fett';
$LANGUAGE['markdown_italic'] = 'Kursiv';
$LANGUAGE['markdown_heading'] = 'Überschrift';
$LANGUAGE['markdown_link'] = 'Link';
$LANGUAGE['markdown_image'] = 'Bild';
$LANGUAGE['markdown_code'] = 'Codeblock';
$LANGUAGE['markdown_quote'] = 'Zitat';
$LANGUAGE['markdown_list_ul'] = 'Liste [ungeordnet]';
$LANGUAGE['markdown_list_ol'] = 'Liste [geordnet]';
?>