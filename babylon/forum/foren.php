<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  $titel = 'Forum / Foren';
  include_once ('kopf.php');

  echo '            <table width="100%" cellpadding="0" cellspacing="0" border="0">';
  
  $beitraege = mysql_query ("SELECT ForumId, NumBeitraege, StempelLetzter, Titel, Inhalt
                             FROM Beitraege
                             WHERE BeitragTyp = 1 AND Gesperrt = 'n'")
    or die ('F0042: Forenzahl konnte nicht ermittelt werden');

  $erster = TRUE;
  $f = 0;
  // Die normalen Foren...
  while ($zeile = mysql_fetch_row ($beitraege))
  {
    if ((1 << $f & $K_Lesen) or (1 << $f & $B_leserecht))
    {
      $param = array ('Erster' => $erster,
                      'ForumId' => $zeile[0],
                      'NumBeitraege' => $zeile[1],
                      'StempelLetzter' => $zeile[2],
                      'Titel' => $zeile[3],
                      'Inhalt' => $zeile[4]);
      zeichne_forum ($param);
      $erster = FALSE;
    }
    $f++;
  }

  // ...und jetzt noch den Posteingang
  if ($K_Egl)
  {
    $letzter = mysql_query ("SELECT LetzterBeitrag, LogoutStempel
                             FROM Benutzer
                             WHERE BenutzerId = $BenutzerId")
      or die ('Benutzerdaten zu den letzten Beiraegen konnten nicht ermittelt werden');
    if (mysql_num_rows ($letzter) != 1)
      die ('Internern Datenbankfehler: BenutzerId nicht oder mehrfach vorhanden');
    $zeile = mysql_fetch_row ($letzter);

  // FIXME: Sollen wir hier ein sicherheits-zeitfenster geben?
    $neuer_als = max ($zeile[0], $zeile[1]);

    $beitraege = mysql_query ("SELECT BeitragId
                               FROM Beitraege
                               WHERE StempelLetzter > $neuer_als
                                 AND BeitragTyp & 8 = 8 
                                 AND Gesperrt = 'n'
                               LIMIT 100")
      or die ('Die neuen Beitraege konnten nicht ermittelt werden');
    $num_beitraege = mysql_num_rows ($beitraege);

    $param = array ('Erster' => FALSE,
                    'ForumId' => -1,
                    'NumBeitraege' => $num_beitraege,
                    'StempelLetzter' => 0,
                    'Titel' => 'Posteingang',
                    'Inhalt' => 'Alles was seit Deinem Letzten Logout neu geschrieben wurde');
    zeichne_forum ($param);
  }

  mysql_close ($db);
  echo '          </table>
       <img src="/grafik/dummy.png" width="1" height="30" alt="">';
 
  if ($K_AdminForen)
  {
    echo "      <form action=\"forum-anlegen.php\" method=\"post\">
            <button type=\"submit\"><img src=\"/grafik/Typewriter$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Neues Forum</button>
          </form>\n";
  }
  include ('leiste-unten.php');
  leiste_unten (NULL, $B_version, $B_subversion);
  echo '  </body>
</html>';
?>
