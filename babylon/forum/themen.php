<?php
/* Copyright 2003, 2004, 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  $titel = 'Forum / Themen';
  include_once ('kopf.php');
  include_once ('fehler.php');

  if (!((1 << $fid & $K_Lesen) or (1 << $fid & $B_leserecht)))
  {
    echo '<h2>Du bist nicht berechtigt dieses Forum zu lesen!</h2>
          </body></html>';
    mysql_close ($db);
    die ();
  }
  
  echo "    <table class=\"themen\">\n";
  
  $tjs = $K_ThemenJeSeite;
  $tid_sprung = $tid;
  
  // FIXME wird nicht mehr gebraucht ??
  // wir kommen direkt aus den foren und setzend die tid auf 2^31 -1;
  // so gehen wir bestimmt hinter den letzten satz
//  if ($tid == -1)
//    $tid = 0xffffffff;

  // erst mal alle Themen eines Forums holen
  $gesperrt = $K_Admin & 1 << $fid ? '' : "AND Gesperrt  = 'n'";
  $erg = mysql_query ("SELECT ThemaId
                       FROM Beitraege
                       WHERE BeitragTyp = 2
                         AND ForumId = $fid
                       $gesperrt
                       ORDER BY StempelLetzter DESC")
    or fehler (__FILE__, __LINE__, 0, 'Themen konnten nicht gelesen werden');
 
  $saetze = mysql_num_rows ($erg);
  if ($saetze > 0)
  {
    $tids = array();
    while ($zeile = mysql_fetch_row ($erg))
      $tids[] = $zeile[0];
    reset ($tids);


    // Wir extrahieren die Themen die wir darstellen wollen
    if ($tid == -1)
      $tids_darstellung = array_slice ($tids, 0, $tjs);
    else
    {
      $i = 0;
      while ($val = current ($tids))
      {
        if ($val == $tid)
          break;
        next ($tids);
        $i++;
      }
       if ($val != $tid)
        die ('Illegale tid uebergeben');
      // wir wollen immer auf saubere Seitengrenzen springen
      $akt_seite = intval (floor ($i / $tjs));
      $i = $akt_seite * $tjs;
      $tids_darstellung = array_slice ($tids, $i, $tjs);
    }


    $tids_holen = implode (' OR ThemaId = ', $tids_darstellung);
    $themen = mysql_query ("SELECT ThemaId, Autor, AutorLetzter, StempelLetzter, Titel, NumBeitraege, NumGelesen, Gesperrt
                               FROM Beitraege
                               WHERE BeitragTyp = 2
                                 AND (ThemaId = $tids_holen)
                               ORDER BY BeitragId DESC")
      or fehler (__FILE__, __LINE__, 0, 'Themen konnten nicht gelesen werden');

    $erster = TRUE;
    while ($zeile = mysql_fetch_row ($themen))
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
  }
  echo "          </table>\n";

//  #####################
//  # die Seitenauswahl #
//  #####################

  if ($saetze > $tjs)
  {
    echo "    <div align=\"center\">";
    $seiten = ceil ($saetze / $tjs);
    $vor = max ($akt_seite - 5, 0);
    $nach = min ($akt_seite + 6, $seiten);
  
  // vorige Seite
    if ($akt_seite > 0)
    {
      $i = ($akt_seite -1) * $tjs;
      echo"<a href=\"themen.php?fid=$fid&amp;tid=$tids[$i]\">neuere</a>&nbsp;&nbsp;";
    }
  // noch was vor den Dargestellten vorhanden?
    if ($vor > 0)
      echo '...';
  // die Seiten vor der aktuellen
    for ($x = $vor; $x < $akt_seite; $x++)
    {
      $i = $x * $tjs;
      $j = $x +1;
      echo"&nbsp;<a href=\"themen.php?fid=$fid&amp;tid=$tids[$i]\">$j</a>&nbsp;";
    }
  // die aktuelle Seite
    $i = $akt_seite + 1;
    echo "<b>$i</b> ";
  // die Seiten nach der aktuellen
    for ($x = $akt_seite + 1; $x < $nach; $x++)
    {
      $i = $x * $tjs;
      $j = $x +1;
      echo"&nbsp;<a href=\"themen.php?fid=$fid&amp;tid=$tids[$i]\">$j</a>&nbsp;";
    }
  // noch was nach den Dargestellten vorhanden?
    if ($nach < $seiten)
      echo '...';
  // naechste Seite
    if ($akt_seite +1  < $seiten)
    {
      $i = ($akt_seite +1) * $tjs;
      echo"&nbsp;&nbsp;<a href=\"themen.php?fid=$fid&amp;tid=$tids[$i]\">&auml;ltere</a>";
    }
    
    echo "</div>\n";
  }


  if ($K_Egl)
  {
    echo "    <form action=\"beitraege.php\" method=\"post\">
      <button type=\"submit\" name=\"neu\" value=\"1\" accesskey=\"t\">
        <table>
          <tr>
            <td>
              <img src=\"/grafik/Typewriter$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">
            </td>
            <td>
              Neues&nbsp;Thema<br>
              <font size=\"-4\">(Alt+t)</font>
            </td>
          </tr>
        </table>
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
