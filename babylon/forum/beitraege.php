<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                       "http://www.w3.org/TR/html4/loose.dtd">
<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */


// #####################
// #  Initialisierung  #
// #####################

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
  $K_ThemenJeSeite = 6;
  $K_Signatur = '';
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  $fid = 0;
  $tid = 0;
  $sid = 0;
  $bid = 0;
  $zid = 0;
  $neu = 0;
  include ('get-post.php');
  if (id_von_get_post ($fid, $tid, $sid, $bid, $zid, $neu))
    die ('Illegaler Zugriffsversuch!');

  include ('../gemeinsam/benutzer-daten.php');
  include ('../gemeinsam/msie.php');
  $msiepng = msie_png ();
  include ('leiste-oben.php');
  
  $K_BeitraegeJeSeite = $B_beitraege_je_seite;
  $K_Stil = $B_standart_stil;

  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  include ('beitrag-pharsen.php');

// ##########
// #  Kopf  #
// ##########

  echo '<html>
  <head>
    <script src="js/formathilfe.js" type="text/javascript"></script>';
  include ('konf/meta.php');
  metadata ($_SERVER['SCRIPT_FILENAME']);

  $stil_datei = "stil/$K_Stil.php";
  include ($stil_datei);
  css_setzen ();

  echo '    <title>Forum / Foren</title>
  </head>
  <body>';
  wartung_ankuendigung ();
  
  if (!((1 << $fid & $K_Lesen) or (1 << $fid & $B_leserecht)))
  {
    echo '<h2>Du bist nicht berechtigt dieses Forum zu lesen!</h2>
          </body></html>';
    mysql_close ($db);
  }
  else
  {
    echo '    <table width="100%">';

    $gehe_zu = 'themen';
    leiste_oben ($K_Egl);
// Ende der Kopftabelle
    echo '    </table>';
// ##################
// #  Die Beitraege #
// ##################
 
    if (!$neu)
    {
      if ($sid == -1)
        $ansicht = 't';
      else $ansicht = 's';
    }

    echo '      <table width="100%">';

    if ($K_Egl)
    {
      echo '    <form action="beitrag-senden.php" method="post">';
      // benoetigen wir fuer die "bei antwort-mail funktion
      benutzer_daten_persoenlich ($foo, $foo, $foo, $K_EMail, $foo, $foo, $foo);
    }
    
    if ($neu == FALSE)
    {
      if ($ansicht == 't')
        $erg = mysql_query ("SELECT Titel, NumBeitraege
                             FROM Beitraege
                             WHERE ThemaId=\"$tid\" AND BeitragTyp = 2 AND Gesperrt = 'n'")
          or die ('F0021: Beitragszahl konnte nicht ermittelt werden');
      else
        $erg = mysql_query ("SELECT Titel, NumBeitraege
                             FROM Beitraege
                             WHERE StrangId=\"$sid\" AND BeitragTyp & 4 = 4 AND Gesperrt = 'n'")
          or die ('F0022: Beitragszahl konnte nicht ermittelt werden');
      $zeile = mysql_fetch_row ($erg);
      $saetze = $zeile[1];
      $thema = stripslashes ($zeile[0]);
      
      $bjs = $K_BeitraegeJeSeite;

    // wir kommen direkt aus den Themen und setzend die tid auf 2^31 -1;
    // so gehen wir bestimmt hinter den letzten satz
       if ($bid == -1)
           $bid = 0xffffffff;

   // die beitraege die wir darestellen wollen
      echo '        <tr>
          <td>
            <table width="100%" cellspacing="0" cellpadding="0" border="0">';
      if ($ansicht == 't')
          $beitraege = mysql_query ("SELECT BeitragId, Autor, StempelLetzter, Inhalt
                                     FROM Beitraege
                                     WHERE ThemaId = $tid AND BeitragTyp & 8 = 8 AND BeitragId <= $bid AND Gesperrt = 'n'
                                     ORDER BY BeitragId DESC LIMIT $bjs")
        or die ('F0023: Beitr&auml;ge konnten nicht gelesen werden');
      else
        $beitraege = mysql_query ("SELECT BeitragId, Autor, StempelLetzter, Inhalt
                                   FROM Beitraege
                                   WHERE StrangId = $sid AND BeitragTyp & 8 = 8 AND BeitragId <= $bid AND Gesperrt = 'n'
                                   ORDER BY BeitragId DESC LIMIT $bjs")
        or die ('F0024: Beitr&auml;ge konnten nicht gelesen werden');

      $erster = TRUE;
      while ($zeile = mysql_fetch_row ($beitraege))
      {
        if ($zeile[0] == $zid)
        {
          $zitat_autor = $zeile[1];
          $zitat_stempel = $zeile[2];
          $zitat_inhalt = stripslashes ($zeile[3]);
        }

        $erg = mysql_query ("SELECT BenutzerId
                             FROM Benutzer
                             WHERE Benutzer = \"$zeile[1]\"")
          or die ('Es konnte die BenutzerId des Benutzers fuer den Atavar nicht ermittelt werden');
        if (mysql_num_rows ($erg) == 1)
        {
          $atavar_zeile = mysql_fetch_row ($erg);
          $atavar_datei = "atavar/$atavar_zeile[0].jpg";
          $atavar = (is_readable ($atavar_datei)) ? $atavar_zeile[0] : '-1';
        }
        else
          $atavar = -1;

        $inhalt = stripslashes ($zeile[3]);
      
        $param = array ('Erster' => $erster,
                        'ForumId' => $fid,
                        'BeitragId' => $zeile[0],
                        'Autor' => $zeile[1],
                        'StempelLetzter' => $zeile[2],
                        'Thema' => $thema,
                        'Inhalt' => $inhalt,
                        'Egl' => $K_Egl,
                        'Atavar' => $atavar);
        zeichne_beitrag ($param);
        $erster = FALSE;
      }

      echo '            </table>
         </td>
       </tr>';
    }
 
    $limit = $bjs * 6;
  // wir holen die Beitraege nach dem gewuenschten Satz, fuer die Seitenumschalter;
  // ausreichend fuer 5 Seiten ...
    if ($ansicht == 't')
      $erg = mysql_query ("SELECT BeitragId
                           FROM Beitraege
                           WHERE BeitragTyp & 8 = 8 AND ThemaId = $tid AND BeitragId <=$bid AND Gesperrt = 'n'
                           ORDER BY BeitragId DESC
                           LIMIT $limit")
        or die ('F0025: BeitragIds f&uuml;r die Seitenumschalter konnten nicht geholt werden');
    else
       $erg = mysql_query ("SELECT BeitragId
                           FROM Beitraege
                           WHERE BeitragTyp & 8 = 8 AND StrangId = $sid AND BeitragId <=$bid AND Gesperrt = 'n'
                           ORDER BY BeitragId DESC
                           LIMIT $limit")
        or die ('F0026: BeitragIds f&uuml;r die Seitenumschalter konnten nicht geholt werden'); 

    $zeilen = mysql_num_rows ($erg);
    if ($zeilen > $bjs)
    {
    // wir ueberlesen den kompletten ersten satz daten, da sie ja schon dargestellt sind
      for ($i = 0; $i < $bjs; $i++)
        mysql_fetch_row ($erg);
      $i = 0;
      $n = 0;
      while ($zeile = mysql_fetch_row ($erg))
      {
        if (($i % $bjs) == 0)
        {
          $bids_nach[$n] = $zeile[0];
          $n++;
        }
        $i++;
      }
      if ($zeilen < $limit)
        $folgebeitraege = $zeilen - ($bjs - 1);
      else
      {
        if ($ansicht == 't')
          $folge = mysql_query ("SELECT BeitragId
                                 FROM Beitraege
                                 WHERE BeitragTyp & 8 = 8 AND ThemaId = $tid AND BeitragId < $bid AND Gesperrt = 'n'
                                 ORDER BY BeitragId DESC")
          or die ('F0027: Anzahl der Folgebeitr&auml;ge konnte nicht ermittelt werden');
        else
          $folge = mysql_query ("SELECT BeitragId
                                 FROM Beitraege
                                 WHERE BeitragTyp & 8 = 8 AND StrangId = $sid AND BeitragId < $bid AND Gesperrt = 'n'
                                 ORDER BY BeitragId DESC")
          or die ('F0028: Anzahl der Folgebeitr&auml;ge konnte nicht ermittelt werden');
        $folgebeitraege = mysql_num_rows ($folge) - ($bjs - 1);
      }
    }
    else
      $folgebeitraege = 0;

  // ... und dann holen wir auch noch die Beitraege die davor liegen
    $limit = $bjs * 5;
    if ($ansicht == 't')
      $erg = mysql_query ("SELECT BeitragId
                           FROM Beitraege
                           WHERE BeitragTyp & 8 = 8 AND ThemaId = $tid AND BeitragId > $bid AND Gesperrt = 'n'
                           ORDER BY BeitragId DESC
                           LIMIT $limit")
        or die ('F0029: Die Beitr&auml;ge vor dem aktuellen konnten nicht ermittelt werden');
    else
      $erg = mysql_query ("SELECT BeitragId
                           FROM Beitraege
                           WHERE BeitragTyp & 8 = 8 AND StrangId = $sid AND BeitragId > $bid AND Gesperrt = 'n'
                           ORDER BY BeitragId DESC
                           LIMIT $limit")
        or die ('F0030: Die Beitr&auml;ge vor dem aktuellen konnten nicht ermittelt werden');
		       
    if (mysql_num_rows ($erg) > 0)
    {
      $i = 0;
      $n = 0;
      while ($zeile = mysql_fetch_row ($erg))
      {
        if ($i % $bjs == 0)
        {
          $bids_vor[$n] = $zeile[0];
          $n++;
        }
        $i++;
      }
    }
//  #####################
//  # die Seitenauswahl #
//  #####################

    echo '        <tr align="center">
          <td height="50" valign="middle">';

    if ($saetze > $bjs)
    {
      $seiten = ceil ($saetze/ $bjs);
      $aktuelle_seite = floor(($saetze - $folgebeitraege - 1) / $bjs) + 1;

      if (isset ($bids_vor))
      {
        if (($saetze - $folgebeitraege - $bjs) > $bjs * 5)
          echo '...';
    
        $seiten_vor = sizeof ($bids_vor);
        $i = $aktuelle_seite - $seiten_vor;
        foreach ($bids_vor as $bids)
        {
          echo"&nbsp;<a href=\"beitraege.php?fid=$fid&tid=$tid&sid=$sid&bid=$bids\">$i</a>&nbsp;";
          $i++;
        }
      }

      echo "$aktuelle_seite ";
    
      if (isset ($bids_nach))
      {    
        $i = $aktuelle_seite + 1;
        foreach ($bids_nach as $bids)
        {
          echo"&nbsp;<a href=\"beitraege.php?fid=$fid&tid=$tid&sid=$sid&bid=$bids\">$i</a>&nbsp;";
          $i++;
        }

        if ($folgebeitraege > $bjs * 5)
          echo '...';
      }
    }
  // das wars mit den Beitraegen...

    echo "\n          </td>
        </tr>\n";
    
    if (1 << $fid & $K_Schreiben and $K_Egl)
    {
      if (isset ($tid) == FALSE and $neu == FALSE)
        die ('Da kein neues Thema erstellt werden soll, dieser Beitrag aber keine Themenkennung hat liegt anscheinend ein interner Fehler vor. Versuch es nochmals. Wenn der Fehler dann immer noch besteht wende dich bitte an den Seitenmeister.');
      else
      {
      
//  ################
//  # Die Vorschau #
//  ################

        if (isset ($_GET['vorschau']) and $_GET['vorschau'] == 'j')
        {
          include ('beitraege-vorschau.php');
          $vorschau_inhalt = beitrag_vorschau_inhalt ($BenutzerId);
          beitrag_vorschau ($vorschau_inhalt);
        }


//  ################
//  # Titeleingabe #
//  ################

        if ($neu)
        {
          echo '<tr><td>';
          if (isset ($_GET['titel']))
            echo "Thema <input name=\"titel\" value=\"$_GET[titel]\" size=\"50\"><p>\n";
          else
            echo 'Thema <input name="titel" size="50"><p>';
          echo "<input type=\"hidden\" name=\"fid\" value=\"$fid\"  
                </td></tr>\n";
        }
        
//  ###################
//  # Beitragseingabe #
//  ###################

        echo '        <tr>
          <td>
            <table>
              <tr>
                <td>
                  <a name="textarea"></a>';
        if (isset ($_GET['vorschau']) and $_GET['vorschau'] == 'j')
          beitrag_vorschau_textarea ($vorschau_inhalt);
        else
        {
          echo '                  <textarea name="text" cols="80" rows="12">';

//  ##########
//  # Zitate #
//  ##########

          if (isset ($zitat_inhalt))
          {
            $zitat = str_replace ("<br />", "\n", $zitat_inhalt);
            $zitat = preg_replace ('/<img src="smileys\/\w+\.png" alt="(...)">/', '\\1', $zitat);
            $zitat_datum = strftime ("%d.%b.%Y", $zitat_stempel);
            $zitat_zeit = date ("H.i", $zitat_stempel);

            echo "<div class=\"zitat\"><b>$zitat_autor tat am $zitat_datum um $zitat_zeit der Welt folgendes kund:</b><p>$zitat</div>";
          }
          echo '</textarea>
                  <p>';
         }

//  ##########################
//  # Smielie-Schaltflaechen #
//  ##########################
         echo "                  <script type=\"text/javascript\">
                    <!--
                      tabelle_smilies_erstellen ();
                    //-->;
                  </script>
                </td>
              </tr>
            </table>
            <tr>
              <td>
                <table>
                  <tr>
                    <td>
                      <table>
                        <tr>
                          <td>
                            <button type=\"submit\" name=\"gehe_zu\" value=\"themen\" accesskey=\"s\">
                              <table>
                                <tr>
                                  <td>
                                    <img src=\"/grafik/Schicken$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">
                                  </td>
                                  <td>
                                    Beitrag abschicken<br>
                                    <font size=\"-4\">(Alt+s)</font>
                                  </td>
                                </tr>
                              </table>
                            </button>
                            <input type=\"hidden\" name=\"fid\" value=\"$fid\">      
                            <input type=\"hidden\" name=\"tid\" value=\"$tid\">
                            <input type=\"hidden\" name=\"sid\" value=\"$sid\">\n";
             if ($bid == 0xffffffff)
               echo '                      <input type="hidden" name="bid" value="-1">';
             else
               echo "                      <input type=\"hidden\" name=\"bid\" value=\"$bid\">\n";
             if ($neu)
               echo '                      <input type="hidden" name="neu" value="1">'; 
             echo "                    </td>
                          <td>
                            <button type=\"submit\" name=\"vorschau\" value=\"j\" accesskey=\"v\">
                              <table>
                                <tr>
                                  <td>
                                    <img src=\"/grafik/Vorschau$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">
                                  </td>
                                  <td>
                                    Beitragsvorschau<br>
                                    <font size=\"-4\">(Alt+v)</font>
                                  </td>
                                </tr>
                              </table>
                            </button>
                          </td>
                        </tr>
                        <tr>
                          <td colspan=\"2\">
                            <input type=\"checkbox\" name=\"antwort_mail\">
                              <font size=\"-2\">
                                Antworten auf diesen Beitrag per Mail an <b>$K_EMail</b> schicken
                              </font>
                            </input>
                          </td>
                        </tr>
                      </table>\n";

//  #####################################
//  # Formatierungshilfe-Schaltflaechen #
//  #####################################

         echo '                  <script type="text/javascript">
                    <!--
                      tabelle_formate_erstellen ();
                    //-->;
                  </script>
                  <noscript>
                    </td>
                    <td>
                      <font size="-1">Mit aktivem JavaScript erh&auml;ltst Du diverse Formatierungshilfen</font>
                  </noscript>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </form>';
      }
    }

// #############################
// # Lesezaehler aktualisieren #
// #############################
   if (!substr_count ($_SERVER['HTTP_REFERER'], '/forum/beitraege.php'))
     mysql_query ("UPDATE Beitraege
                   SET NumGelesen = NumGelesen + 1
                   WHERE BeitragTyp = 2 AND ThemaId = \"$tid\"")
       or die ('Lesez&auml;hler konnte nicht aktualisiert werden');
   
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
