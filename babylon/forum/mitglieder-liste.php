<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  $titel = 'Forum / Mitglieder';
  include_once ('kopf.php');

  echo '  <table width="100%" cellspacing="0" cellpadding="0" border="2">
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
    $sort = 'r';
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
  
  setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'de', 'ge');
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
   echo '          </table>';

  include ('leiste-unten.php');
  leiste_unten (NULL, $B_version, $B_subversion);

  echo '  </body>
</html>';
?>
