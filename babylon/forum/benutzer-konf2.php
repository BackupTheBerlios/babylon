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
  $K_ThemenJeSeite = 6;
  $K_BeitraegeJeSeite = 3;
  $K_Signatur = '';
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  include ('../gemeinsam/benutzer-daten.php');
  include ('../gemeinsam/msie.php');
  include_once ('konf/konf.php');
  $msiepng = msie_png ();
  include ('leiste-oben.php');

  $K_Stil = $B_standart_stil;
  
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  echo '<html>
  <head>';
  include ('konf/meta.php');
  metadata ($_SERVER['SCRIPT_FILENAME']);
  
  $stil_datei = "stil/$K_Stil.php";
  include ($stil_datei);
  css_setzen ();

  echo '    <title>Babylon - Einstellungen / Aussehen</title>';

  if (!$K_Egl)
    die ('Zugriff verweigert');

  echo '  </head>
  <body>';
  
  wartung_ankuendigung ();
  
  echo '    <h2>Forumseinstellungen</h2>
    <table cellspacing="0" cellpadding"0">
      <tr>
        <td class="reiter_inaktiv">
          <a href="benutzer-konf1.php">Verhalten</a>
        </td>
        <td class="reiter_aktiv">
          Aussehen
        </td>
        <td class="reiter_inaktiv">
          <a href="benutzer-konf3.php">Profil</a>
        </td>
        <td class="reiter_inaktiv">
          <a href="benutzer-konf4.php">Pers&ouml;nliches</a>
        </td>
      </tr>
    </table>
    <div class="konfiguration">
    <form name="konf" action="benutzer-konf-test2.php" method="post">
      <table width="100%">
        <tr>
          <td>';

 // ##### Stil #####
  echo '            <table cellspacing=\"10\">
              <tr>
                <td colspan="2">
                  <h3>Stil</h3>
                </td>
              </tr>
              <tr>
                <td colspan="2">W&auml;hle aus, in welchem Stil die Forums-, Themen- und Beitragsseiten pr&auml;sentiert werden sollen</td>
              </tr>';

    $i = 0;

    foreach ($B_stile as $stil)
    {
      $stil_name = '';
      $datei = "stil/$stil.php";
      $fd = fopen ($datei, 'r');
      while ($n = fgets ($fd, 34))
      {
        $name = '';
        if ($name = strstr ($n, '// StilName = '))
        {
          $arr = NULL;
          $arr = explode (' ', $name, 4);
          if (count ($arr) == 4)
          {
            $stil_name = $arr[3];
            break; 
          }
        }
      }
      fclose ($fd);
    
    if ((!isset ($stil_name)) or (!strlen ($stil_name)))
      $stil_name = 'Namenlos';
   
    echo "              <tr>
                <td valign=\"middle\">
                  <nobr><input type=\"radio\" name=\"stil\" value=\"$stil\" ";
    if (strcmp ($stil, $K_Stil) == 0)
      echo "checked";
    echo ">$stil_name</input></nobr>
                </td>
                <td valign=\"middle\" width=\"100%\">
                  <img class=\"stil_auswahl\" src=\"stil/$stil.png\" border=\"2\">
                </td>
              </tr>\n";
    $i++;
  }
  echo "            </table>
          </td>
        </tr>
        <tr>
          <td>&nbsp;<p> </td>
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
