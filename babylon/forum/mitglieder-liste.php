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
  include_once ('../gemeinsam/msie.php');
  include_once ('konf/konf.php');
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

  echo '  <title>Forum / Mitglieder</title>
</head>
<body>';
  wartung_ankuendigung ();
  echo '    <table width="100%">';

  $gehe_zu = 'themen';
  leiste_oben ($K_Egl);

  


  echo '    </table>
    <table width="100%" cellspacing="0" cellpadding="0" border="2">
      <tr>';

  $kopf_post = array ('alias', 'angemeldet', 'beitraege', 'themen', 'letzter');

  $kopf = array ('Alias', 'Angemeldet<br>seit', 'Beitr&auml;ge<br>geschrieben',
                 'Themen<br>er&ouml;ffnet', 'letzter<br>Beitrag');

  $db_auswahl = array ('Benutzer', 'Anmeldung', 'Beitraege', 'Themen', 'LetzterBeitrag');

  if (isset ($_GET['kopf_wahl']))
  {
    $wahl = $_GET['kopf_wahl'];
    $sort = $_GET['kopf_sort'];
  }
  else
  {
    $wahl = 'letzter';
    $sort = 'h';
  }
  $nsort = $sort == 'h' ? 'r' : 'h';
  $db_wahl = 'letzter';
  
  for ($x = 0; $x < 5; $x++)
  {
    $w = $kopf_post[$x];
    $k = $kopf[$x];
    echo'             <th class="ueber">
          <table width="100%">
            <tr>';
    
    if (strcmp ($wahl, $w) != 0)
      echo "       <td><a href=\"mitglieder-liste.php?kopf_wahl=$w&kopf_sort=$sort\">$k</a></td>";
    else
    {
      $sel = $sort == 'h' ? 'sel_rauf' : 'sel_runter';
      $db_wahl = $db_auswahl[$x];
      echo "
                   <td><a href=\"mitglieder-liste.php?kopf_wahl=$w&kopf_sort=$nsort\">$k</a></td>
                   <td>
                     <a href=\"mitglieder-liste.php?kopf_wahl=$w&kopf_sort=$nsort\">
                       <img src=\"/grafik/$sel.png\" alt=\"\" border=\"0\">
                     </a>
                   </td>";
    }
    echo '        </tr>
          </table>
        </th>';
  }
  echo '    </tr>';

  $db_sort = $sort == 'h' ? '' : 'DESC';

  $erg = mysql_query ("SELECT Benutzer, Anmeldung, Beitraege, Themen, LetzterBeitrag
                       FROM Benutzer
                       ORDER BY '$db_wahl' $db_sort")
    or die ('Benutzerdaten konnte nicht ermittelt werden');
  
  while ($zeile = mysql_fetch_row ($erg))
  {
    $alias = rawurlencode ($zeile[0]);
    $anmeldung = strftime ('%d.%b.%Y', intval ($zeile[1]));
    $letzter =  $zeile[4] ? strftime ('%d.%b.%Y %H:%M', $zeile[4]) : '-';
    echo "<tr>
            <td align=\"center\" class=\"col-hell\">              
              <a href=\"mitglieder-profil.php?alias=$alias\">$zeile[0]</a>
            </td>
            <td align=\"center\" class=\"col-dunkel\">$anmeldung</td>
            <td align=\"center\" class=\"col-hell\">$zeile[2]</td>
            <td align=\"center\" class=\"col-dunkel\">$zeile[3]</td>
            <td align=\"center\" class=\"col-hell\">$letzter</td>
          </tr>";
  } 
   echo '          </table>
        </td>
      </tr>';

  include ('leiste-unten.php');
  leiste_unten (NULL, $B_version, $B_subversion);

  echo '    </table>
  </body>
</html>';
?>
