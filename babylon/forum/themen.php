<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                       "http://www.w3.org/TR/html4/loose.dtd">
<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $Benutzer = "";
  $K_Egl = FALSE;
  $K_Lesen = 0;
  $K_Schreiben = 0;
  $K_Admin = 0;
  $K_AdminForen = 0;
  $K_ThemenJeSeite = 6;
  $K_Signatur ="";
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  $fid = 0;
  $tid = 0;
  $sid = 0;
  $bid = 0;
  $zid = 0;
  $neu = FALSE;
  include ("get-post.php");
  if (id_von_get_post ($fid, $tid, $sid, $bid, $zid, $neu))
    die ("Illegaler Zugriffsversuch!");

  include ("../gemeinsam/db-verbinden.php");
  include ("../gemeinsam/benutzer-daten.php");
  include ("../gemeinsam/msie.php");
  include_once ("konf/konf.php");
  $msiepng = msie_png();
  include ("leiste-oben.php");

  $db = db_verbinden ();

  $K_Stil = $B_standart_stil;
  $K_BeitraegeJeSeite = $B_beitraege_je_seite;
  
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen,  $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  echo "<html>
  <head>\n";
  include ("konf/meta.php");
  metadata ($_SERVER["SCRIPT_FILENAME"]);

  $stil_datei = "stil/" . $K_Stil . ".php";
  include ($stil_datei);
  css_setzen ();

  echo "    <title>Forum / Themen</title>
  </head>
  <body>\n";

  if (!((1 << $fid & $K_Lesen) or (1 << $fid & $B_leserecht)))
  {
    echo "<h2>Du bist nicht berechtigt dieses Forum zu lesen!</h2>
          </body></html>";
    mysql_close ($db);
  }
  else
  {
  
  echo "    <table width=\"100%\">\n";

  $gehe_zu = "themen";
  leiste_oben ($K_Egl);

  echo "    </table>
    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
      <tr>
        <td>\n";
  
  $erg = mysql_query ("SELECT NumBeitraege
                       FROM Beitraege
                       WHERE BeitragTyp = 1 AND ForumId = $fid")
    or die ("F0056: Beitragszahl konnte nicht ermittelt werden");
  $zeile = mysql_fetch_row ($erg);
  $saetze = $zeile[0];
  
  $tjs = $K_ThemenJeSeite;
  // wir kommen direkt aus den foren und setzend die tid auf 2^31 -1;
  // so gehen wir bestimmt hinter den letzten satz
  if ($tid == -1)
    $tid = 0xffffffff;


  // die Themen die wir darstellen wollen
  $beitraege = mysql_query ("SELECT ThemaId, Autor, AutorLetzter, StempelLetzter, Titel, NumBeitraege, NumGelesen
                             FROM Beitraege
                             WHERE BeitragTyp = 2 AND ForumId = $fid AND ThemaId <= $tid AND Gesperrt = 'n'
                             ORDER BY StempelLetzter DESC
                             LIMIT $tjs")
    or die ("F0057: Themen konnten nicht gelesen werden");
 
 
  $erster = TRUE;
  while ($zeile = mysql_fetch_row ($beitraege))
  {
    $baum = $K_BaumZeigen == 'j' ? TRUE : FALSE;
    $titel = stripslashes ($zeile[4]);
    zeichne_thema ($erster, $fid, $zeile[0], $zeile[1], $zeile[2], $zeile[3], $titel, $zeile[5], $zeile[6], $baum);
    $erster = FALSE;
  } 
   echo "          </table>
        </td>
      </tr>\n";

   $limit = $tjs * 6;
  // wir holen die Themen nach dem gewuenschten Satz, fuer die Seitenumschalter;
  // ausreichend fuer 5 Seiten ...
  $erg = mysql_query ("SELECT ThemaId
                       FROM Beitraege
                       WHERE BeitragTyp = 2 AND ForumId = $fid AND ThemaId <= $tid AND Gesperrt = 'n'
                       ORDER BY StempelLetzter DESC
                       LIMIT $limit")
    or die ("F0058: ThemenIds f&uuml;r die Seitenumschalter konnten nicht geholt werden");

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
    or die ("F0059: Die Themen Id's konnten nicht ermittelt werden");
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
  echo "      <tr align=\"center\">
        <td height=\"50\" valign=\"middle\">\n";
  if ($saetze > $tjs)
  {
    $seiten = ceil ($saetze/ $tjs);
    $aktuelle_seite = floor(($saetze - $folgethemen - 1) / $tjs) + 1;

    if (isset ($tids_vor))
    {
      if (($saetze - $folgethemen - $tjs) > $tjs * 5)
       echo "...";
    
      $seiten_vor = sizeof ($tids_vor);
      $i = $aktuelle_seite - $seiten_vor;
      foreach ($tids_vor as $tids)
      {
        echo "&nbsp;<a href=\"themen.php?fid=$fid&tid=$tids\">$i</a>&nbsp; ";
        $i++;
      }
    }

    echo "$aktuelle_seite ";
    
    if (isset ($tids_nach))
    {    
      $i = $aktuelle_seite + 1;
      foreach ($tids_nach as $tids)
      {
        echo "&nbsp;<a href=\"themen.php?fid=$fid&tid=$tids\">$i</a>&nbsp; ";
        $i++;
      }

      if ($folgethemen > $tjs * 5)
        echo "...";
    }
  }
  // das wars mit den Themen...

  
  echo "\n        </td>
      </tr>
      <tr>
        <td>\n";
  if ($K_Egl)
  {
    echo "          <form action=\"beitraege.php\" method=\"post\">
            <button name=\"neu\" value=\"1\">
              <img src=\"/grafik/Typewriter$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Neues Thema
            </button>
            <input type=\"hidden\" name=\"fid\" value=\"$fid\">
          </form>\n";
  }
  else
  {
    echo "          <form action=\"login.php\" method=\"post\">
            <button name=\"gehe_zu\" value=\"beitraege\">
              <img src=\"/grafik/Typewriter$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Neues Thema
            </button>
            <input type=\"hidden\" name=\"neu\" value=\"1\">
            <input type=\"hidden\" name=\"fid\" value=\"$fid\">
          </form>\n";
  }
  echo "        </td>
      </tr>\n";

  include ("leiste-unten.php");
  leiste_unten ();

  echo "    </table>
  </body>
</html>";
}
?>
