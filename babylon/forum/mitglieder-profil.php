<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                       "http://www.w3.org/TR/html4/loose.dtd">
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
  include ('benutzer-eingaben.php');
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

  $alias = addslashes ($_GET['alias']);

  $erg = mysql_query ("SELECT BenutzerId, Benutzer, VName,
                              NName, Anmeldung, Beitraege,
                              Themen, LetzterBeitrag, Atavar,
                              ProfilNameZeigen, ProfilEMail, ProfilHomepage,
                              ProfilOrt, NachrichtAnnehmen, NachrichtAnnehmenAnonym,
                              AtavarData, Gruppe
                       FROM Benutzer
                       WHERE Benutzer = \"$alias\"")
    or die ('Profildaten konnten nicht ermittelt werden');
  if (!mysql_num_rows ($erg))
    die ('Ungueltige Benutzerkennung');

  $profil = mysql_fetch_row ($erg);

  echo "  <title>Forum / Profil von $profil[1]</title>
</head>
<body>";
  
  wartung_ankuendigung ();
  
  echo '    <table width="100%">';

  $gehe_zu = 'foren';
  leiste_oben ($K_Egl);
  
 
  if ($profil[9] == 'j')
    $name = "$profil[2] $profil[3]<br>";
  else
    $name = '';
   
   
  if ($profil[7])
  {
    setlocale (LC_TIME, "de_DE");
    $datum = strftime ("%d.%b.%Y", $profil[7]);
    $zeit = date ("H.i:s", $profil[7]);
    $letzter_beitrag = "Letzter Beitrag am $datum um $zeit<br>";
  }

  if ($profil[12])
    $ort = "Wohnort: $profil[12]";
  else
    $ort = '';

  echo '    </table>
        <table border="2" width="100%">
          <tr>
            <td>';

  if ($profil[8] == 'j')
    echo "<img src=\"atavar-ausgeben.php?atavar=$profil[0]\">";
  else
    echo 'Kein Atavar<br>eingerichtet';
 
  echo "            </td>
            <td width=\"50%\">
              $profil[1]<br>
              $name
              $ort
            </td>
            <td width=\"50%\">
              $profil[5] Beitr&auml;ge geschrieben<br>
              $profil[6] Themen er&ouml;ffnet<br>
              $letzter_beitrag
            </td>
          </tr>
          <tr>
            <td colspan=\"3\">
              Email: ";
  if ($profil[10])
    if ($B_profil_links)
    {
      if (email_adresse_gueltig ($profil[10]))
        echo "<a href=\"mailto:$profil[10]\">$profil[10]</a>";
      else
        echo "$profil[10]";
    }
    else
      echo "$profil[10]";             
  
  echo '<br>            Homepage: ';
  
  if ($profil[11])
    if ($B_profil_links)
    {
      if (preg_match ('/http:\/\/.*/', $profil[11]))
        echo "<a href=\"$profil[11]\">$profil[11]</a>";
      else
        echo "<a href=\"http://$profil[11]\">$profil[11]</a>";
    }
    else
      echo "$profil[11]";
  echo '          </td>
          </tr>
        </table>';

  if ($profil[13] == 'n')
    echo "<p>$profil[1] m&ouml;chte keine privaten Nachrichten per E-Mail erhalten";
  else
  {

      echo "<h3>Private Nachricht an $profil[1]</h3>
            <form action=\"private-nachricht.php\" method=\"post\">
            <textarea name=\"text\" cols=\"80\" rows=\"4\"></textarea><br>
            <table>
              <tr>
                <td>
                  <button type=\"submit\" accesskey=\"s\" title=\"Die Nachricht per EMail an $profil[1] schicken\">
                    <table>
                      <tr>
                        <td>
                          <img src=\"/grafik/Schicken$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">
                         </td>
                         <td>
                           Nachricht abschicken<br>
                           <font size=\"-4\">(Alt+s)</font>
                         </td>
                       </tr>
                     </table>
                  </button>
                </td>";
    if ($profil[14] == 'n')
      echo "<td>
      <font size=\"-2\">$profil[1] hat nur der Zusendung nicht anonymer E-Mails zugestimmt.<br>
      Mit dem Absenden einer Nachricht an $profil[1] stimmst Du ausdr&uuml;cklich zu,<br>
      dass Dein realer Name und Deine E-Mail Adresse an $profil[1] weiter gegeben werden darf.</font><br>
            </td>";
    else
      echo "<td>
              <input type=\"checkbox\" name=\"Anonym\">Nachricht Anonym verschicken</input>
            </td>";
    echo "</tr>
        </table>
        <input type=\"hidden\" name=\"Empfaenger\" value=\"$profil[0]\">
      </form>";
  }

// Benutzergruppe festlegen
  if ($K_AdminForen)
  {
    $erg = mysql_query ("SELECT VorlageId, Name
                         FROM BenutzerVorlage")
      or die ('Die Benutzergruppen konnten nicht ermittelt werden');

    echo "<form action=\"benutzer-gruppe-aendern-test.php\" method=\"post\">
          Benutzer $profil[1] in Benutzergruppe
            <select name=\"vorlage\" size=\"1\" title=\"Benutzergruppe ausw&auml;hlen\">";

    while ($zeile = mysql_fetch_row ($erg))
    {
      if (strcmp ($zeile[1], $profil[16]) == 0)
        echo "<option value=\"$zeile[0]\" selected>$zeile[1]</option>";
      else
        echo "<option value=\"$zeile[0]\">$zeile[1]</option>";
    }

    $alias = rawurlencode ($_GET['alias']);
    echo "  </select>
            <input type=\"hidden\" name=\"benutzerid\" value=\"$profil[0]\">
            <input type=\"hidden\" name=\"alias\" value=\"$alias\">
            <button type=\"submit\">verschieben</button>
	  </form>
	  <br>
          <a href=\"benutzer-vorlage.php\">Benutzervorlagen verwalten</a>";
  }
 
  include ('leiste-unten.php');
  leiste_unten (NULL, $B_version, $B_subversion);

  echo '    </table>
  </body>
</html>';
?>
