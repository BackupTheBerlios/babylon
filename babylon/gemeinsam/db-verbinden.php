<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function db_verbinden ()
{
  $host = 'localhost';
  $nutzer = 'root';
  $passwort = 'x7fM3u6j9f';
  $datenbank = 'babylon';
  
  $db = mysql_connect ($host, $nutzer, $passwort)
    or die ('<b>F0039: Es konnte keine Verbindung zur Datenbank hergestellt werden</b>');
  mysql_select_db ($datenbank)
    or die ('F0040: Die Datenbank konnte nicht ausgew&auml;hlt werden');
  return $db;
}
?>
