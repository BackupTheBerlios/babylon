<?PHP;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

/*
datei - die Datei in der der Fehler auftrat
zeile - die Zeile in der der Fehler auftrat
typ - der Fehlertyp (bislang: 0 SQL-Fehler, 1 sonstige
meldung - eine zusaetzliche Meldung die mit ausgegeben werden soll
*/

function fehler ($datei, $zeile, $typ, $meldung)
{
  $fehlertyp = $typ == 0 ? 'Datenbankfehler' : 'Fehler';


  if ($datei)
  {
    echo "<h2>Fehler</h2>
    In <tt>$datei</tt> ist in Zeile $zeile ein $fehlertyp aufgetreten.<p>
    Babylon lieferte folgende Fehlermeldung:<br>";
  }
 
  echo "$meldung<p>";

  if ($typ == 0)
  {
    echo "Die Datenbank lieferte folgende Fehlermeldung:<br>";
    echo mysql_error ();
  }
  exit;
}   
