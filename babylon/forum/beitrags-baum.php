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
    $inhalt = stripslashes ($daten[$i][5]);

    $aus1 = "$baum<a class=\"baum\"
             href=\"beitraege.php?fid=$_GET[fid]&amp;tid={$daten[$i][0]}&amp;sid={$daten[$i][1]}&amp;bid={$daten[$i][2]}\">$inhalt";
    $aus2 = "{$daten[$i][6]} $datum $zeit</a><br>";
    echo $aus1;
    $lg = strlen ($inhalt) + strlen ($aus2) + strlen ($baum);

  // FIXME evtl die maximale Spaltenanzahl aus der Benutzerkonfiguration holen
    if ($lg < 120)
    {
      $aus =  str_repeat ('.', 120 - $lg);
      echo $aus;
    }
    echo $aus2;
    // hier sollten wir noch die richtige laenge berechnen; ausserdem sollte das thema ggfs ein wenig
    // eingekuerzt werden um seltener umbrueche machen zu muessen
//    if (strlen ($baum) >= 60)
    if ($lg > 116)
      $baum = '';
    else
      $baum .= '  ';

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


$titel = 'Forum / Beitr&auml;ge';
include_once ('kopf.php');

if (!((1 << $fid & $K_Lesen) or (1 << $fid & $B_leserecht)))
{
  mysql_close ($db);
  die ('<h2>Du bist nicht berechtigt dieses Forum zu lesen!</h2>
        </body></html>');
}

$gesperrt = $K_Admin & 1 << $fid ? '' : 'AND Gesperrt  = \'n\'';
  
$beitraege = mysql_query ("SELECT ThemaId, StrangId, BeitragId, WeitereKinder,
                           Eltern, Titel, Autor, StempelLetzter
                           FROM Beitraege
                           WHERE ThemaId=\"$tid\"
                             AND BeitragTyp & 8 = 8
                             $gesperrt
                           ORDER BY StrangID, BeitragId")
  or die ('F0036: Beitr&auml;ge konnten nicht selektiert werden');
$i = 0;
  
while ($zeile = mysql_fetch_row ($beitraege))
{
  $daten[$i] = $zeile;
  $i++;
}
setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');

echo '<pre style="line-height:150%">';
strang_ausgeben ($daten, $daten[0][1], $i, '', 0);
echo "</pre><br>\n";
  
echo "<img src=\"/grafik/dummy.png\" width=\"1\" height=\"24\" border=\"0\" alt=\"\">
      <br>
      <a href=\"beitraege.php?tid={$daten[0][0]}&amp;sid=-1&amp;bid=-1\">
      <b>Alle Beitraege auf ein Mal anzeigen</b></a>";
mysql_close ($db);

include ('leiste-unten.php');
leiste_unten (NULL, $B_version, $B_subversion);
echo '  </body>
</html>';
?>
