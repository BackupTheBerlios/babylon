<!--DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                       "http://www.w3.org/TR/html4/loose.dtd"-->
<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  include_once ('konf/konf.php');
  include ('wartung/wartung-info.php');

// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $Benutzer = '';
  $K_Egl = FALSE;
  $K_Lesen = 0;
  $K_Schreiben = 0;
  $K_Admin = 0;
  $K_AdminForen = 0;
  $K_Signatur = '';
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  include ('../gemeinsam/benutzer-daten.php');
  include_once ('../gemeinsam/msie.php');
  $msiepng = msie_png ();
  include ('leiste-oben.php');

  $K_Stil = $B_standart_stil;

  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  echo '<html>
  <head>';
  include ('konf/meta.php');
  metadata ($_SERVER['SCRIPT_FILENAME']);

  $stil_datei = "stil/$K_Stil.php";
  include ($stil_datei);
  css_setzen ();

  echo '    <title>Forum / Foren</title>
  </head>
  <body>';
  
  wartung_ankuendigung ();
  
  echo '    <table width="100%">';

  $gehe_zu = 'themen';
  leiste_oben ($K_Egl);
  echo '        <tr>
          <td>
            <table width="100%" cellpadding="0" cellspacing="0" border="0">';
  
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
        </td>
      </tr>
      <tr>
        <td><img src="/grafik/dummy.png" width="1" height="30" alt=""></td>
      </tr>';
 
  if ($K_AdminForen)
  {
    echo "      <tr>
        <td>
          <form action=\"forum-anlegen.php\" method=\"post\">
            <button type=\"submit\"><img src=\"/grafik/Typewriter$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Neues Forum</button>
          </form>
        </td>
      </tr>\n";
  }
  include ('leiste-unten.php');
  leiste_unten (NULL);
  echo '    </table>
  </body>
</html>';
?>
