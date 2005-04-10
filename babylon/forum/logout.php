<?php
/* Copyright 2003, 2004, 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

include_once ('konf/konf.php');

if (isset ($_COOKIE["$B_cookie_id"]) and isset ($_COOKIE["$B_cookie_sw"]))
{
  $id = intval ($_COOKIE["$B_cookie_id"]);
  $sw = $_COOKIE["$B_cookie_sw"];


  $erg = mysql_query ("SELECT Cookie
                       FROM Benutzer
                       WHERE BenutzerId=\"$id\"")
    or die ('F0052: Benutzerdaten konnten nicht aus der Datenbank abgerufen werden');
  
  if (mysql_num_rows ($erg) > 1)
  {
    mysql_close ($db);
    die ('F0053: Interner Datenbankfehler bei der Abmeldung<br>bitte wende dich an den Seitenmeister');
  }
  else if (mysql_num_rows ($erg) == 1)
  {
    $zeile = mysql_fetch_row ($erg);
    if (strcmp ($sw, $zeile[0]) == 0)
    {
      setcookie($B_cookie_id, '', NULL, '/');
      setcookie($B_cookie_sw, '', NULL, '/');
      $stempel = time ();
      mysql_query ("UPDATE Benutzer
                    SET Eingeloggt=\"n\", Cookie=NULL, LogoutStempel=\"$stempel\"
                    WHERE BenutzerId=\"$id\"")
        or die ('F0054: Es ist nicht m&ouml;glich die Datenbank informationen zu aktuallisieren');
    }  
  }
  mysql_close ($db);
}
include('gz-foren.php');
?>
