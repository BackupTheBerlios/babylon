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
  $K_BeitraegeJeSeite = 3;
  $K_Stil = "std";
  $K_Signatur ="";
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  include ("../gemeinsam/db-verbinden.php");
  include ("../gemeinsam/benutzer-daten.php");
  include ("../gemeinsam/msie.php");
  include_once ("konf/konf.php");
  $msiepng = msie_png ();
  include ("leiste-oben.php");

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  echo "<html>
  <head>";
  include ("konf/meta.php");
  metadata ($_SERVER["SCRIPT_FILENAME"]);
  
  $stil_datei = "stil/" . $K_Stil . ".php";
  include ($stil_datei);
  css_setzen ();

  echo "    <title>Babylon - Einstellungen / Verhalten</title>\n";

  if (!$K_Egl)
    die ("Zugriff verweigert");

  echo "  </head>
  <body>
    <h2>Forumseinstellungen</h2>
    <table cellspacing=\"0\" cellpadding\"0\">
      <tr>
        <td class=\"reiter_aktiv\">
          Verhalten
        </td>
        <td class=\"reiter_inaktiv\">
          <a href=\"benutzer-konf2.php\">Aussehen</a>
        </td>
         <td class=\"reiter_inaktiv\">
          <a href=\"benutzer-konf3.php\">Profil</a>
        </td>       
        <td class=\"reiter_inaktiv\">
          <a href=\"benutzer-konf4.php\">Pers&ouml;nliches</a>
        </td>
      </tr>
    </table>
    <div class=\"konfiguration\">
    <form name=\"konf\" action=\"benutzer-konf-test1.php\" method=\"post\">
      <table>
        <tr>
          <td>\n";

  // ##### Signatur #####
  $signatur = str_replace ("<br />", "", $K_Signatur);
  $signatur = ereg_replace ("(<img src=\"smileys)(.*)(.png\" alt=\")(.*)(\">)", "\\4", $signatur);
  echo "            <table>
              <tr>
                <td><h3>Signatur</h3></td>
              </tr>
              <tr>
                <td>Diese Signatur wird an all Deine Beitr&auml;ge angeh&auml;ngt. Sie darf die gleichen html-Befehle enthalten, die f&uuml;r Forumsbeitr&auml;ge erlaubt sind. Die L&auml;nge ist auf 255 Zeichen inklusive der html-Befehle beschr&auml;nkt. Referenzen zu externen Dateien - auch Bilder - sind nicht erlaubt.</td>
              </tr>
              <tr>
                <td><textarea name=\"signatur\" cols=\"50\" rows=\"5\" maxlength=\"255\">" . $signatur . "</textarea></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>\n";
  // ##### Themen / Beitraege je Seite #####
  echo "            <table>
              <tr>
                <td><h3>Verhalten</h3>
                <h4>Wieviele Themen / Beitr&auml;ge sollen je Seite angezeigt werden?</h4></td>
              </tr>\n";

  $je_seite = array (3, 6, 10, 20);
  echo "              <tr>
                <td>
                  <table>
                    <tr>
                      <td>
                        <table>
                          <tr>
                            <td>Themen je Seite</td>
                          </tr>\n";
  foreach ($je_seite as $je)
  {
    if ($je == $K_ThemenJeSeite)
      echo "                          <tr>
                            <td valign=\"middle\"><nobr><input type=\"radio\" name=\"themen_je_seite\" value=\"$je\" checked>" . $je  . "</input></nobr></td>
                          </tr>";
    else
      echo "                          <tr>
                            <td valign=\"middle\"><nobr><input type=\"radio\" name=\"themen_je_seite\" value=\"$je\">" . $je  . "</input></nobr></td></tr>
                          </tr>\n";
  }

  echo "                        </table>
                      </td>
                      <td>
                        <table>
                          <tr>
                            <td>Beitr&auml;ge je Seite</td>
                          </tr>";
  foreach ($je_seite as $je)
  {
    if ($je == $K_BeitraegeJeSeite)
      echo "                          <tr>
                            <td valign=\"middle\"><nobr><input type=\"radio\" name=\"beitraege_je_seite\" value=\"$je\" checked>" . $je  . "</input></nobr></td></tr>
                          </tr>\n";
    else
      echo "                          <tr>
                            <td valign=\"middle\"><nobr><input type=\"radio\" name=\"beitraege_je_seite\" value=\"$je\">" . $je  . "</input></nobr></td></tr>
                          </tr>\n";
  }

  echo "                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>\n";

  // ##### Sprungziel bei Speicherung eines Beitrags #####
  
  echo "              <tr>
                 <td><h4>Wohin soll nach dem Absenden eines Beitrages gesprungen werden?</h4></td>
              </tr>\n";

  $sprung_name = array ("Zur &Uuml;bersicht der Foren",
                        "Zur &Uuml;bersicht der Themen im zuletzt besuchten Forum",
			"Zur Baumansicht des zuletzt besuchten Themas",
			"In den Beitragsstrang des zuletzt besuchten Themas",
			"Zu den zuletzt besuchten Beitr&auml;gen eines Themas");
  $sprung_wert = array ("forum", "thema", "baum", "strang", "beitrag");
  $i = 0;

  foreach ($sprung_name as $sprung)
  {
    if ($i == $K_SprungSpeichern)
      echo "              <tr>
                <td valign=\"middle\"><nobr><input type=\"radio\" name=\"sprung_speichern\" value=\"" . $sprung_wert[$i] . "\" \"checked\">" . $sprung_name[$i]  . "</input></nobr></td>
              </tr>\n";
    else
      echo "              <tr>
                <td valign=\"middle\"><nobr><input type=\"radio\" name=\"sprung_speichern\" value=\"" . $sprung_wert[$i] . "\">" . $sprung_name[$i]  . "</input></nobr></td>
              </tr>\n";
    $i++;
  }

  // #### Ob der Beitragsbaum gezeigt werden soll #####
  echo "              <tr>
                 <td><h4>Soll wenn man von den Themen kommt der Beitragsbaum gezeigt werden?</h4>
                         Alternativ wird direkt in die Beitr&auml;ge gesprungen so als h&auml;tte man in der Baumansicht \"Alle Beitr&auml;ge auf ein Mal anzeigen\" gew&auml;hlt<p></td>
              </tr>
              <tr>
                <td><input type=\"checkbox\" name=\"baum_zeigen\"";
  if ($K_BaumZeigen == 'j')
    echo " \"checked\"";
  echo ">Beitragsbaum anzeigen</input></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>&nbsp;<p> </td>
        </tr>
        <tr>
          <td>
            <table width=\"100%\">
              <tr>
                <td>
                  <button name=\"speichern\"><img src=\"/grafik/Speichern$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">&Auml;nderungen speichern</button>
                </td>
                <td align=\"right\">
                  <button name=\"zurueck\"><img src=\"/grafik/PfeilLinks$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Zur&uuml;ck zum Forum</button>
                </td>         
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </form>
    </div>
  </body>
</html>\n";
?>
