<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function konf_schreiben ($var, $wert)
{
  global $K_AdminForen;

  if (!$K_AdminForen)
    die ('Zugriff verweigert');

  $erg = mysql_query ("SELECT Typ
                       FROM Konf
                       WHERE Schluessel = \"$var\"")
    or die ('Der Typ der zu setztenden Konfigurationsvariable konnte nicht ermittelt werden');
  if (mysql_num_rows ($erg) != 1)
    die ('Bei der Typermittlung wurde eine unzul&auml;assige Anzahl Treffer zur&uuml;ck gegeben');

  $zeile = mysql_fetch_row ($erg);
  if ($zeile[0] == 'i' or $zeile[0] == 'f' or $zeile[0] == 'b')
  {
    $typ = 'WertInt';
    if ($zeile[0] == 'b')
      $wert = $wert ? 1 : 0;
    mysql_query ("UPDATE Konf
                  SET WertInt = \"$wert\"
                  WHERE Schluessel = \"$var\"")
      or die ("Die Konfiguration konnte nicht aktuallisiert werden");
  }
  else if ($zeile[0] == 't')
  {
    $typ = 'WertText';
     mysql_query ("UPDATE Konf
                  SET WertText = \"$wert\"
                  WHERE Schluessel = \"$var\"")
      or die ('Die Konfiguration konnte nicht aktuallisiert werden');
  }
  else
    die ('Es wird versucht eine Variable mit nicht unterst&uuml;tzten Typ zu speichern');
}
;?>
