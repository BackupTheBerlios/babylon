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
  $K_Stil = "std";
  $K_Signatur ="";
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  include ("../gemeinsam/db-verbinden.php");
  include ("../gemeinsam/benutzer-daten.php");
  include_once ("../gemeinsam/msie.php");
  include_once ("konf/konf.php");
  $msiepng = msie_png ();
  include ("leiste-oben.php");

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);
  echo "<html>
  <head>\n";
  include ("konf/meta.php");
  metadata ($_SERVER["SCRIPT_FILENAME"]);

  $stil_datei = "stil/" . $K_Stil . ".php";
  include ($stil_datei);
  css_setzen ();

  echo "  <title>Forum / Mitglieder</title>
</head>
<body>
  <table width=\"100%\">\n";

  $gehe_zu = "themen";
  leiste_oben ($K_Egl);

  echo "    </table>
    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"2\">
      <tr>
        <th>Alias</th>
        <th>Angemeldet<br>seit</th>
        <th>Beitr&auml;ge<br>geschrieben</th>
        <th>Themen<br>er&ouml;ffnet</th>
        <th>letzter<br>Beitrag</th>
      </tr>\n";
   
  $erg = mysql_query ("SELECT Benutzer, Anmeldung, Beitraege, Themen, LetzterBeitrag
                       FROM Benutzer")
    or die ("Benutzerdaten konnte nicht ermittelt werden");
  
  while ($zeile = mysql_fetch_row ($erg))
  {
    $anmeldung = strftime ('%d.%b.%Y', intval ($zeile[1]));
    $letzter =  strftime ('%d.%b.%Y %H:%M', $zeile[4]);
    echo "<tr>
            <td align=\"center\">$zeile[0]</td>
            <td align=\"center\">$anmeldung</td>
            <td align=\"center\">$zeile[2]</td>
            <td align=\"center\">$zeile[3]</td>
            <td align=\"center\">$letzter</td>
          </tr>";
  } 
   echo "          </table>
        </td>
      </tr>\n";

  include ("leiste-unten.php");
  leiste_unten ();

  echo "    </table>
  </body>
</html>";
?>
