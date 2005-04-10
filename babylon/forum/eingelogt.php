<?php
/* Copyright 2003, 2004, 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

include_once ('konf/konf.php');

function eingelogt ()
{
  global $B_cookie_id, $B_cookie_sw;
  
  if ((!isset ($_COOKIE['$B_cookie_id'])) or (!isset ($_COOKIE['$B_cookie_sw'])))
  {
    $K_Egl = FALSE;
    return;
  }
  if (! is_int (intval ($_COOKIE['$B_cookie_id'])))
    return FALSE;
  $id = addslashes ($_COOKIE['$B_cookie_id']);
  $sw = $_COOKIE['$B_cookie_sw'];

  if (isset ($id))
  {
    $erg = mysql_query ("SELECT Cookie
                         FROM Benutzer
                         WHERE BenutzerId=\"$id\"")
      or die ('F0041: Cookie konnte nicht aus der Datenbank gelesen werden');
    if (mysql_num_rows ($erg) == 1)
    {
      $zeile = mysql_fetch_row ($erg);
      if (strcmp ($sw, $zeile[0]) == 0)
      {
        return (TRUE);
      }
    }
  }
  return (FALSE);        
}
?>
