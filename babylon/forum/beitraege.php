<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */


// #####################
// #  Initialisierung  #
// #####################

  $titel = 'Forum / Beit&auml;ge';
  $skript = 'j';
  include_once ('kopf.php');
  include ('beitrag-pharsen.php');
  
  if (!((1 << $fid & $K_Lesen) or (1 << $fid & $B_leserecht)))
  {
    mysql_close ($db);
    die ('<h2>Du bist nicht berechtigt dieses Forum zu lesen!</h2>
          </body></html>');
  }

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
      $arg = $ansicht == 't' ? "ThemaId = '$tid' AND BeitragTyp = 2" : "StrangId = '$sid' AND BeitragTyp & 4 = 4";
      $gesperrt = $K_Admin & 1 << $fid ? '' : "AND Gesperrt  = 'n'";

      $erg = mysql_query ("SELECT Titel, NumBeitraege
                           FROM Beitraege
                           WHERE $arg $gesperrt")
          or die ('F0021: Beitragszahl konnte nicht ermittelt werden');

      $zeile = mysql_fetch_row ($erg);
      $saetze = $zeile[1];
      $thema = stripslashes ($zeile[0]);
      
      $bjs = $K_BeitraegeJeSeite;

      $bid_sprung = $bid;
    // wir kommen direkt aus den Themen und setzend die tid auf 2^31 -1;
    // so gehen wir bestimmt hinter den letzten satz
       if ($bid == -1)
           $bid = 0xffffffff;

   // die beitraege die wir darestellen wollen
      echo '        <tr>
          <td>
            <table width="100%" cellspacing="0" cellpadding="0" border="0">';

      $arg = $ansicht == 't' ? "ThemaId = $tid " : "StrangId = $sid";
      $beitraege = mysql_query ("SELECT BeitragId, Autor, StempelLetzter, Inhalt, Gesperrt
                                 FROM Beitraege
                                 WHERE $arg
                                   AND BeitragTyp & 8 = 8
                                   AND BeitragId <= $bid
                                   $gesperrt
                                 ORDER BY BeitragId DESC LIMIT $bjs")
        or die ('F0023: Beitr&auml;ge konnten nicht gelesen werden');

      $erster = TRUE;
      while ($zeile = mysql_fetch_row ($beitraege))
      {
        if ($zeile[0] == $zid)
        {
          $zitat_autor = $zeile[1];
          $zitat_stempel = $zeile[2];
          $zitat_inhalt = stripslashes ($zeile[3]);
        }

        $erg = mysql_query ("SELECT BenutzerId, Atavar, KonfSignatur
                             FROM Benutzer
                             WHERE Benutzer = \"$zeile[1]\"")
          or die ('Es konnte die BenutzerId des Benutzers fuer den Atavar nicht ermittelt werden');
        if (mysql_num_rows ($erg) != 1)
          die ('F&uuml;r den &uuml;bergebenen Bentuzernamen ist kein oder mehrere Eintr&auml;ge vorhanden');

        $benutzer_zeile = mysql_fetch_row ($erg);
        $atavar = $benutzer_zeile[1] == 'j' ? $benutzer_zeile[0] : '-1';

        if ($benutzer_zeile[2] != NULL)
          $inhalt = $zeile[3] . '<br /><br/>--<br/>' . beitrag_pharsen ($benutzer_zeile[2]);
        else
          $inhalt = $zeile[3];
        $inhalt = stripslashes ($inhalt);
      
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

     // Administrator fuer dieses Forum?
        if ($K_Admin & 1 << $fid)
        {
          $sperren = $zeile[4] == 'j' ? 'n' : 'j';
          $grafik = $sperren == 'n' ? 'gesperrt' : 'lesbar';
      
          echo "<tr>
              <td colspan=\"4\">
                <div class=\"admin-leiste\">
                  <a href=\"beitrag-sperren.php?fid=$fid&tid=$tid&sid=$sid&bid=$zeile[0]&bid_sprung=$bid_sprung&sperren=$sperren\">Lesbar
                  <img src=\"/grafik/$grafik.png\" border=\"0\" alt=\"$grafik\"></a>
                </div>
              </td>
            </tr>";
        }
      }

      echo '            </table>
         </td>
       </tr>';
    }
 
    $limit = $bjs * 6;
  // wir holen die Beitraege nach dem gewuenschten Satz, fuer die Seitenumschalter;
  // ausreichend fuer 5 Seiten ...
    $arg = $ansicht == 't' ? "ThemaId = $tid" : "StrangId = $sid";
    $erg = mysql_query ("SELECT BeitragId
                         FROM Beitraege
                         WHERE BeitragTyp & 8 = 8
                           AND $arg
                           AND BeitragId <=$bid
                           $gesperrt
                         ORDER BY BeitragId DESC
                         LIMIT $limit")
      or die ('F0025: BeitragIds f&uuml;r die Seitenumschalter konnten nicht geholt werden');

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
        $arg = $ansicht == 't' ? "ThemaId = $tid" : "StrangId = $sid";
        $folge = mysql_query ("SELECT BeitragId
                               FROM Beitraege
                               WHERE BeitragTyp & 8 = 8
                                 AND $arg
                                 AND BeitragId < $bid
                                 $gesperrt
                               ORDER BY BeitragId DESC")
          or die ('F0027: Anzahl der Folgebeitr&auml;ge konnte nicht ermittelt werden');
        $folgebeitraege = mysql_num_rows ($folge) - ($bjs - 1);
      }
    }
    else
      $folgebeitraege = 0;

  // ... und dann holen wir auch noch die Beitraege die davor liegen
    $limit = $bjs * 5;
    $arg = $ansicht == 't' ? "ThemaId = $tid" : "StrangId = $sid";
    $erg = mysql_query ("SELECT BeitragId
                         FROM Beitraege
                         WHERE BeitragTyp & 8 = 8
                           AND $arg
                           AND BeitragId > $bid
                           $gesperrt
                         ORDER BY BeitragId DESC
                         LIMIT $limit")
      or die ('F0029: Die Beitr&auml;ge vor dem aktuellen konnten nicht ermittelt werden');
		       
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
          {
            $titel = htmlentities (stripslashes ($_GET['titel']));
            echo "Thema <input name=\"titel\" value=\"$titel\" size=\"50\"><p>\n";
          }
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
                            <button type=\"submit\" accesskey=\"s\">
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
  leiste_unten (NULL, $B_version, $B_subversion);
  echo '  </body>
</html>';
?>
