<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
     "http://www.w3.org/TR/html4/loose.dtd\>

<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */


// Diese Funktion ist in keiner Weise optimiert...
// Hier kann noch jemand beweisen, was wahre Maenner sind ;-)

function strang_ausgeben (&$daten, $sid, $saetze, $tiefe, $m)
{
  if ($m++ > 100)
    return;
  $i = 0;
  $n = 0;
  $baum = $tiefe;
  while ($daten[$i][1] != $sid and $i < $saetze)
    $i++;

  while ($daten[$i][1] == $sid and $i < $saetze)
  {
    $datum = strftime ("%d.%b.%Y", $daten[$i][7]);
    $zeit = date ("H.i:s", $daten[$i][7]);

//    $t = ($tiefe * 20) % 500;
//    echo "<img src=\"/grafik/dummy.png\" width=\"$t\" height=\"1\" alt=\"\">
//    <a href=\"beitraege.php?tid={$daten[$i][0]}&sid={$daten[$i][1]}&bid={$daten[$i][2]}\">
//    bid:{$daten[$i][2]}  {$daten[$i][5]} {$daten[$i][6]} $datum $zeit inhalt:{$daten[$i][8]}</a><br>";

    $aus1 = "$baum<a class=\"baum\"
             href=\"beitraege.php?fid=$_GET[fid]&tid={$daten[$i][0]}&sid={$daten[$i][1]}&bid={$daten[$i][2]}\">{$daten[$i][5]}";
    $aus2 = "{$daten[$i][6]} $datum $zeit</a><br>";
    echo $aus1;
    $lg = strlen ($daten[$i][5]) + strlen ($aus2) + strlen ($baum);

  // FIXME evtl die maximale Spaltenanzahl aus der Benutzerkonfiguration holen
    if ($lg < 120)
    {
      $aus =  str_repeat ('.', 120 - $lg);
      echo $aus;
    }
    echo $aus2;
    // hier sollten wir noch die richtige laenge berechnen; ausserdem sollte das thema ggfs ein wenig
    // eingekuerzt werden um seltener umbrueche machen zu muessen
    if (strlen ($baum) >= 60)
      $baum = '';
    else
      $baum .= '   ';

  // hier sammeln wir die weiteren Kinder ...
    if ($daten[$i][3] == 'j')
    {
      $weitere[$n] = $i;
      $n++;
    }
    $i++;
  }

  // ... und hier geben wir sie aus
  if (isset ($weitere))
    foreach ($weitere as $i)
    {
      $n = 0;
      while ($n < $saetze)
      {
        if ($daten[$n][4] == $daten[$i][2] and $daten[$n][1] != $daten[$i][1])
          strang_ausgeben ($daten, $daten[$n][1], $saetze, $tiefe . '    ', $m);
        $n++; 
      }
    }
}
  include_once ('konf/konf.php');
  include ('wartung/wartung-info.php');

  // Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $K_Egl = FALSE;
  $K_Lesen = 0;
  $K_Schreiben = 0;
  $K_Admin = 0;
  $K_AdminForen = 0;
  $K_ThemenJeSeite = 6;
  $K_BeitraegeJeSeite = 3;
  $K_Signatur = '';
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  $fid = 0;
  $tid = 0;
  $sid = 0;
  $bid = 0;
  $zid = 0;
  $neu = FALSE;
  include ('get-post.php');
  if (id_von_get_post ($fid, $tid, $sid, $bid, $zid, $neu))
    die ('Illegaler Zugriffsversuch!');

  include ('../gemeinsam/benutzer-daten.php');
  include ('leiste-oben.php');

  $K_Stil = $B_standart_stil;

  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen,  $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  echo '<html><head>';
  include ('konf/meta.php');
  metadata ($_SERVER['SCRIPT_FILENAME']);

  $stil_datei = "stil/$K_Stil.php";
  include ($stil_datei);
  css_setzen ();

  echo '<title>Forum / Beitr&auml;ge</title>
        </head><body>';
  wartung_ankuendigung ();

  if (!((1 << $fid & $K_Lesen) or (1 << $fid & $B_leserecht)))
  {
    mysql_close ($db);
    echo '<h2>Du bist nicht berechtigt dieses Forum zu lesen!</h2>
          </body></html>';
  }
  else
  {
        echo '<table width="100%">';

  $gehe_zu = 'themen';
  leiste_oben ($K_Egl);

  $beitraege = mysql_query ("SELECT ThemaId, StrangId, BeitragId, WeitereKinder,
                             Eltern, Titel, Autor, StempelLetzter
                             FROM Beitraege
                             WHERE ThemaId=\"$tid\" AND BeitragTyp & 8 = 8 AND Gesperrt = 'n'
                             ORDER BY StrangID, BeitragId")
    or die ('F0036: Beitr&auml;ge konnten nicht gelesen werden');
  $i = 0;
  while ($zeile = mysql_fetch_row ($beitraege))
  {
    $daten[$i] = $zeile;
    $i++;
  }
  echo '<tr><td><pre style="line-height:150%">';
  strang_ausgeben ($daten, $daten[0][1], $i, '', 0);
  echo "</pre></td></tr>";
  
  echo "<tr><td><img src=\"/grafik/dummy.png\" width=\"1\" height=\"24\" border=\"0\" alt=\"\">
        <br>
        <a href=\"beitraege.php?tid={$daten[0][0]}&sid=-1&bid=-1\">
        <b>Alle Beitraege auf ein Mal anzeigen</b></a>";
  mysql_close ($db);

//  ##############
//  # Fussleiste #
//  ##############

  include ('leiste-unten.php');
  leiste_unten ();
  echo '    </table>
  </body>
</html>';
  }
?>
