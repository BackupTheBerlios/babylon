<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function db_verbinden ()
{
    $db = mysql_connect ("localhost", "db_benutzer", "db_passwort")
    or die ("<b>F0039: Es konnte keine Verbindung zur Datenbank hergestellt werden</b>");
  mysql_select_db ("babylon")
    or die ("F0040: Die Datenbank konnte nicht ausgew&auml;hlt werden");
  return $db;
}
?>
