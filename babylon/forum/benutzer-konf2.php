<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  $titel = 'Babylon - Einstellungen / Aussehen';
  include_once ('kopf.php');

  if (!$K_Egl)
    die ('Zugriff verweigert');

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
            <button name=\"speichern\"><img src=\"/grafik/Speichern$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">&Auml;nderungen speichern</button>
          </td>
        </tr>
      </table>
    </form>
    </div>\n";

include ('leiste-unten.php');
leiste_unten (NULL, $B_version, $B_subversion);
  echo '  </body>
</html>';
?>
