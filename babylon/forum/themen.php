<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  $titel = 'Forum / Themen';
  include_once ('kopf.php');

  if (!((1 << $fid & $K_Lesen) or (1 << $fid & $B_leserecht)))
  {
    echo '<h2>Du bist nicht berechtigt dieses Forum zu lesen!</h2>
          </body></html>';
    mysql_close ($db);
    die ();
  }
  
  echo "    <table class=\"themen\">\n";
  
  $erg = mysql_query ("SELECT NumBeitraege
                       FROM Beitraege
                       WHERE BeitragTyp = 1 AND ForumId = $fid")
    or die ('F0056: Beitragszahl konnte nicht ermittelt werden');
  $zeile = mysql_fetch_row ($erg);
  $saetze = $zeile[0];
  
  $tjs = $K_ThemenJeSeite;
  $tid_sprung = $tid;
  // wir kommen direkt aus den foren und setzend die tid auf 2^31 -1;
  // so gehen wir bestimmt hinter den letzten satz
  if ($tid == -1)
    $tid = 0xffffffff;


  // die Themen die wir darstellen wollen
  $gesperrt = $K_Admin & 1 << $fid ? '' : 'AND Gesperrt  = \'n\'';
   
  $beitraege = mysql_query ("SELECT ThemaId, Autor, AutorLetzter, StempelLetzter, Titel,
                               NumBeitraege, NumGelesen, Gesperrt
                             FROM Beitraege
                             WHERE BeitragTyp = 2
                               AND ForumId = $fid
                               AND ThemaId <= $tid
                               $gesperrt
                             ORDER BY StempelLetzter DESC
                             LIMIT $tjs")
    or die ('F0057: Themen konnten nicht gelesen werden');
 
 
  $erster = TRUE;
  while ($zeile = mysql_fetch_row ($beitraege))
  {
    $baum = $K_BaumZeigen == 'j' ? TRUE : FALSE;
    $titel = stripslashes ($zeile[4]);
    $param = array ('Erster' => $erster,
                    'ForumId' => $fid,
                    'ThemaId' => $zeile[0],
                    'Autor' => $zeile[1],
                    'AutorLetzter' => $zeile[2],
                    'StempelLetzter' => $zeile[3],
                    'Titel' => $titel,
                    'NumBeitraege' => $zeile[5],
                    'NumGelesen' => $zeile[6],
                    'BaumZeigen' => $baum);
    zeichne_thema ($param);
    $erster = FALSE;
    
   // Administrator fuer dieses Forum?
    if ($K_Admin & 1 << $fid)
    {
      $sperren = $zeile[7] == 'j' ? 'n' : 'j';
      $grafik = $sperren == 'n' ? 'gesperrt' : 'lesbar';
      
      echo "<tr>
          <td colspan=\"4\">
            <div class=\"admin-leiste\">
              <a href=\"thema-sperren.php?fid=$fid&tid=$zeile[0]&tid_sprung=$tid_sprung&sperren=$sperren\">Lesbar
              <img src=\"/grafik/$grafik.png\" border=\"0\" alt=\"$grafik\"></a>
            </div>
          </td>
        </tr>";
    }
  } 
/*   echo "          </table>
        </td>
      </tr>\n";*/

   $limit = $tjs * 6;
  // wir holen die Themen nach dem gewuenschten Satz, fuer die Seitenumschalter;
  // ausreichend fuer 5 Seiten ...
  $erg = mysql_query ("SELECT ThemaId
                       FROM Beitraege
                       WHERE BeitragTyp = 2 AND ForumId = $fid AND ThemaId <= $tid AND Gesperrt = 'n'
                       ORDER BY StempelLetzter DESC
                       LIMIT $limit")
    or die ('F0058: ThemenIds f&uuml;r die Seitenumschalter konnten nicht geholt werden');

  $zeilen = mysql_num_rows ($erg);
  if ($zeilen > $tjs)
  {
    // wir ueberlesen den kompletten ersten satz daten, da sie ja schon dargestellt sind
    for ($i = 0; $i < $tjs; $i++)
      mysql_fetch_row ($erg);
    $i = 0;
    $n = 0;
    while ($zeile = mysql_fetch_row ($erg))
    {
      if (($i % $tjs) == 0)
      {
        $tids_nach[$n] = $zeile[0];
        $n++;
      }
      $i++;
    }
    if ($zeilen < $limit)
      $folgethemen = $zeilen - ($tjs - 1);
    else
    {
      $folge = mysql_query ("SELECT ThemaId
                             FROM Beitraege
                             WHERE BeitragTyp = 2 AND ForumId = $fid AND ThemaId < $tid AND Gesperrt = 'n'
                             ORDER BY StempelLetzter DESC");
      $folgethemen = mysql_num_rows ($folge) - ($tjs - 1);
    }
  }
  else
    $folgethemen = 0;

  // ... und dann holen wir auch noch die Themen die davor liegen
  $limit = $tjs * 5;
  $erg = mysql_query ("SELECT ThemaId
                       FROM Beitraege
                       WHERE BeitragTyp = 2 AND ForumId = $fid AND ThemaId > $tid AND Gesperrt = 'n'
                       ORDER BY StempelLetzter DESC
                       LIMIT $limit")
    or die ('F0059: Die Themen Id\'s konnten nicht ermittelt werden');
  if (mysql_num_rows ($erg) > 0)
  {
    $i = 0;
    $n = 0;
    while ($zeile = mysql_fetch_row ($erg))
    {
      if ($i % $tjs == 0)
      {
        $tids_vor[$n] = $zeile[0];
        $n++;
      }
      $i++;
    }
  }

  mysql_close ($db);

  // die Seitenauswahl
    if ($saetze > $tjs)
  {
    echo "      <tr align=\"center\">
        <td>\n";

    $seiten = ceil ($saetze/ $tjs);
    $aktuelle_seite = floor(($saetze - $folgethemen - 1) / $tjs) + 1;

    if (isset ($tids_vor))
    {
      if (($saetze - $folgethemen - $tjs) > $tjs * 5)
       echo '...';
    
      $seiten_vor = sizeof ($tids_vor);
      $i = $aktuelle_seite - $seiten_vor;
      foreach ($tids_vor as $tids)
      {
        echo "&nbsp;<a href=\"themen.php?fid=$fid&amp;tid=$tids\">$i</a>&nbsp; ";
        $i++;
      }
    }

    echo "$aktuelle_seite ";
    
    if (isset ($tids_nach))
    {    
      $i = $aktuelle_seite + 1;
      foreach ($tids_nach as $tids)
      {
        echo "&nbsp;<a href=\"themen.php?fid=$fid&amp;tid=$tids\">$i</a>&nbsp; ";
        $i++;
      }

      if ($folgethemen > $tjs * 5)
        echo '...';
    }
  }
  // das wars mit den Themen...

  
  echo "        </td>
      </tr>
    </table>\n";
  if ($K_Egl)
  {
    echo "    <form action=\"beitraege.php\" method=\"post\">
      <button name=\"neu\" value=\"1\">
        <img src=\"/grafik/Typewriter$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Neues Thema
      </button>
      <input type=\"hidden\" name=\"fid\" value=\"$fid\">
    </form>\n";
  }
  else
  {
    echo "    <form action=\"login.php\" method=\"post\">
      <button>
        <img src=\"/grafik/Typewriter$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Neues Thema
      </button>
     <input type=\"hidden\" name=\"neu\" value=\"1\">
     <input type=\"hidden\" name=\"fid\" value=\"$fid\">
    </form>\n";
  }

  include ('leiste-unten.php');
  leiste_unten (NULL, $B_version, $B_subversion);

  echo '  </body>
</html>';
?>
