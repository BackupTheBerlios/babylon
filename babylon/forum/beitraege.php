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

    if ($K_Egl)
    {
      echo "    <form action=\"beitrag-senden.php\" method=\"post\" name=\"eform\">\n";
      // benoetigen wir fuer die "bei antwort-mail funktion
      benutzer_daten_persoenlich ($foo, $foo, $foo, $K_EMail, $foo, $foo, $foo);
    }
    
    echo "    <table class=\"beitraege\">\n";
    
    if ($neu == FALSE)
    {
      $bjs = $K_BeitraegeJeSeite;
      $gesperrt = $K_Admin & 1 << $fid ? '' : "AND Gesperrt  = 'n'";
      $arg = $ansicht == 't' ? "ThemaId = $tid" : "StrangId = $sid";
      $erg = mysql_query ("SELECT BeitragId
                           FROM Beitraege
                           WHERE BeitragTyp & 8 = 8
                             AND $arg
                             $gesperrt
                           ORDER BY BeitragId DESC")
        or die ('F0025: BeitragIds f&uuml;r die Seitenumschalter konnten nicht geholt werden');

      $saetze = mysql_num_rows ($erg);
      $bids = array();
      while ($zeile = mysql_fetch_row ($erg))
        $bids[] = $zeile[0];
      reset ($bids);

      // Wir extrahieren die bids von den Beitraegen die wir darstellen wollen
      if ($bid == -1)
        $beitraege = array_slice ($bids, 0, $bjs);
      else
      {
       $i = 0;
        while ($val = current ($bids))
        {
          if ($val == $bid)
            break;
          next ($bids);
          $i++;
        }
        if ($val != $bid)
          die ('Illegale bid uebergeben');
        // wir wollen immer auf saubere Seitengrenzen springen
        $akt_seite = intval (floor ($i / $bjs));
        $i = $akt_seite * $bjs;
        $beitraege = array_slice ($bids, $i, $bjs);
      }

      $erg = mysql_query ("SELECT Titel
                           FROM Beitraege
                           WHERE $arg $gesperrt")
          or die ('F0021: Titel konnte nicht ermittelt werden');

      $zeile = mysql_fetch_row ($erg);
      $thema = stripslashes ($zeile[0]);
      
      $bid_sprung = $bid;
      $bids_holen = implode (' OR BeitragId = ', $beitraege);
      $arg = $ansicht == 't' ? "ThemaId = $tid " : "StrangId = $sid";
      $beitraege = mysql_query ("SELECT BeitragId, Autor, StempelLetzter, Inhalt, Gesperrt
                                 FROM Beitraege
                                 WHERE $arg
                                   AND BeitragTyp & 8 = 8
                                   AND (BeitragId = $bids_holen)
                                 ORDER BY BeitragId DESC")
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
      
          echo "	    <tr>
              <td colspan=\"3\">
                <div class=\"admin-leiste\">
                  <a href=\"beitrag-sperren.php?fid=$fid&amp;tid=$tid&amp;sid=$sid&amp;bid=$zeile[0]&amp;bid_sprung=$bid_sprung&sperren=$sperren\">Lesbar
                  <img src=\"/grafik/$grafik.png\" border=\"0\" alt=\"$grafik\"></a>
                </div>
              </td>
            </tr>";
        }
      }
    }
    echo "    </table>\n";


//  #####################
//  # die Seitenauswahl #
//  #####################

    if ($saetze > $bjs)
    {
      echo "    <div align=\"center\">";
      $seiten = ceil ($saetze / $bjs);
      $vor = max ($akt_seite - 5, 0);
      $nach = min ($akt_seite + 6, $seiten);
    
    // vorige Seite
      if ($akt_seite > 0)
      {
        $i = ($akt_seite -1) * $bjs;
        echo"<a href=\"beitraege.php?fid=$fid&amp;tid=$tid&amp;sid=$sid&amp;bid=$bids[$i]\">neuere</a>&nbsp;&nbsp;";
      }
    // noch was vor den Dargestellten vorhanden?
      if ($vor > 0)
        echo '...';
    // die Seiten vor der aktuellen
      for ($x = $vor; $x < $akt_seite; $x++)
      {
        $i = $x * $bjs;
        $j = $x +1;
        echo"&nbsp;<a href=\"beitraege.php?fid=$fid&amp;tid=$tid&amp;sid=$sid&amp;bid=$bids[$i]\">$j</a>&nbsp;";
      }
    // die aktuelle Seite
      $i = $akt_seite + 1;
      echo "<b>$i</b> ";
    // die Seiten nach der aktuellen
      for ($x = $akt_seite + 1; $x < $nach; $x++)
      {
        $i = $x * $bjs;
        $j = $x +1;
        echo"&nbsp;<a href=\"beitraege.php?fid=$fid&amp;tid=$tid&amp;sid=$sid&amp;bid=$bids[$i]\">$j</a>&nbsp;";
      }
    // noch was nach den Dargestellten vorhanden?
      if ($nach < $seiten)
        echo '...';
    // naechste Seite
      if ($akt_seite +1  < $seiten)
      {
        $i = ($akt_seite +1) * $bjs;
        echo"&nbsp;&nbsp;<a href=\"beitraege.php?fid=$fid&amp;tid=$tid&amp;sid=$sid&amp;bid=$bids[$i]\">&auml;ltere</a>";
      }
      
      echo "</div>\n";
    }



    if (1 << $fid & $K_Schreiben and $K_Egl)
    {
      if (isset ($tid) == FALSE and $neu == FALSE)
        die ('Da kein neues Thema erstellt werden soll, dieser Beitrag aber keine Themenkennung hat liegt anscheinend ein interner Fehler vor. Versuch es nochmals. Wenn der Fehler dann immer noch besteht wende dich bitte an den Seitenmeister.');
      else
      {
        echo "    <table>\n";
//  ################
//  # Die Vorschau #
//  ################

        if (isset ($_GET['vorschau']) and $_GET['vorschau'] == 'j')
        {
          include ('beitraege-vorschau.php');
	  $vorschau = beitrag_vorschau_inhalt ($BenutzerId);
          $vorschau_inhalt = beitrag_pharsen ($vorschau);
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

        echo "      <tr>
        <td>
          <table>
            <tr>
              <td>
                <a name=\"textarea\"></a>\n";
        if (isset ($_GET['vorschau']) and $_GET['vorschau'] == 'j')
          beitrag_vorschau_textarea (beitrag_pharsen_ohne_smilies ($vorschau));
        else
        {
          echo "                <textarea name=\"text\" cols=\"80\" rows=\"12\">";

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
          echo "</textarea>
                <p>\n";
         }

//  ##########################
//  # Smielie-Schaltflaechen #
//  ##########################
         echo "                <script type=\"text/javascript\">
                  <!--
                    tabelle_smilies_erstellen ();
                  //-->;
                </script>
              </td>
            </tr>
          </table>
        </td>
      </tr>
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
               echo "                      <input type=\"hidden\" name=\"bid\" value=\"-1\">\n";
             else
               echo "                      <input type=\"hidden\" name=\"bid\" value=\"$bid\">\n";
             if ($neu)
               echo "                      <input type=\"hidden\" name=\"neu\" value=\"1\">";
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
                    </td>
                  </tr>
                </table>
              </td>
              <td>\n";

//  #####################################
//  # Formatierungshilfe-Schaltflaechen #
//  #####################################

         echo "                <script type=\"text/javascript\">
                  <!--
                    tabelle_formate_erstellen ();
                  //-->
                </script>
              </td>
              <td>
                <noscript>
                <font size=\"-1\">Mit aktivem JavaScript erh&auml;ltst Du diverse Formatierungshilfen</font>
                </noscript>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </form>\n";
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
