<?php
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

include ('../gemeinsam/db-verbinden.php');
$db = db_verbinden();

$id = intval ($_GET['atavar']);
$erg = mysql_query ("SELECT AtavarData
                     FROM Benutzer
                     WHERE BenutzerId = \"$id\"")
           or die ('Atavardaten konnten nicht gelesen werden');

if (mysql_num_rows ($erg))
{
  $zeile = mysql_fetch_row ($erg);
  Header ('Content-type:image/jpg');
  echo $zeile[0];
}
?>
