<?php
/* Copyright 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  // Wir verwenden hier keinerlei Fehlermeldungen, da sie vom Rss-Aggregator
  // evtl. falsch als Beitraege interpretiert werden. 

  include_once ('konf/konf.php');

  $erg = mysql_query ("SELECT Titel, Autor, Link, Inhalt, Stempel
                       FROM Rss
                       WHERE Stempel = 0")
    or exit();
  
  // xml-Kopf
  echo "<?xml version=\"1.0\"?>\n<rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">\n";
  
  // channel
  $zeile = mysql_fetch_row ($erg);
  // Nicht einmal der Kopf ist da; Fehlerhafte Systemkonfiguration... 
  if (!$zeile)
    exit ();
  echo "  <channel>
    <title>$zeile[0]</title>
    <link>$zeile[2]</link>
    <description>$zeile[3]</description>
  </channel>\n";

  $erg = mysql_query ("SELECT Titel, Autor, Link, Inhalt, Stempel
                       FROM Rss
                       WHERE Stempel != 0
                       ORDER BY Stempel DESC")
    or exit();

  while ($zeile = mysql_fetch_row ($erg))
  {
    $titel = stripslashes($zeile[0]);
    // Zeilen die in der Rss-Tabelle nicht gefuellt sind geben wir nicht aus
    if (!strlen ($titel))
      continue;

    $autor = stripslashes($zeile[1]);
    $seite =  stripslashes($zeile[2]);
    $inhalt =  stripslashes($zeile[3]);
    
    $zeit = date ("r", $zeile[4]);
    setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
    $zeit2 = strftime ("%d.%m", $zeile[4]) . ' ' .  date ("H.i:s", $zeile[4]);

    echo "  <item>
    <pubDate>$zeit</pubDate>
    <title>$autor: $zeit2 - $titel</title>
    <link>$seite</link>
    <description>$inhalt</description>
  </item>\n";
  }

  // rss Abschluss
  echo "</rss>\n";
?>
