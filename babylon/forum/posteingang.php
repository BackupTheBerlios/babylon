<?PHP;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  $titel = 'Forum / Posteingang';
  include_once ('kopf.php');

  if (!$K_Egl)
    die ("Diese Seite steht nur zur Verfuegung wenn Du ins Forum eingeloggt bist!<br>Was willst Du hier eigentlich?");

  echo '  <table width="100%" cellpadding="0" cellspacing="0" border="2">';

  $letzter = mysql_query ("SELECT LogoutStempel
                           FROM Benutzer
                           WHERE BenutzerId = $BenutzerId")
    or die ('Benutzerdaten zu den letzten Beiraegen konnten nicht ermittelt werden');
  if (mysql_num_rows ($letzter) != 1)
    die ('Internern Datenbankfehler: BenutzerId nicht oder mehrfach vorhanden');
  $zeile = mysql_fetch_row ($letzter);
  $logout_stempel = $zeile[0];
  
  if (isset ($_POST['menge']))
    $menge = intval ($_POST['menge']);
  else
    $menge = 100;
  switch ($menge)
  {
    case 25:
    case 50:
    case 100:
      $zeitraum = $logout_stempel;
      $limit = $menge;
    break;

    case 15:
    case 30:
    case 60:
      $stempel = time ();
      $zeitraum = max ($logout_stempel, ($stempel - $menge * 60));
      $limit = 100;
    break;
    
    case 24:
      $stempel = time ();
      $zeitraum = max ($logout_stempel, $stempel - $menge * 3600);
      $limit = 100;
    break;
    default:
      die ("Es wurde eine ung&uuml;ltige Mengenangabe &uuml;bergeben!");
  }

  $beitraege = mysql_query ("SELECT ForumId, ThemaId, BeitragId, Titel, StempelLetzter, Autor
                             FROM Beitraege
                             WHERE StempelLetzter > \"$zeitraum\"
                               AND BeitragTyp & 8 = 8
                               AND Autor != \"$Benutzer\"
                               AND Gesperrt = 'n'
                             ORDER BY StempelLetzter DESC
                             LIMIT $limit")
    or die ('Die neuen Beitraege konnten nicht ermittelt werden<br>'. mysql_error ());

  if (mysql_num_rows ($beitraege))
  {
    echo '        <tr>
           <th class="ueber">Thema</th>
           <th class="ueber">Autor</th>
           <th class="ueber">Erstellt</th>
         </tr>';

    while ($zeile = mysql_fetch_row ($beitraege))
    {
      if (!((1 << $zeile[0] & $K_Lesen) or (1 << $zeile[0] & $B_leserecht)))
        continue;
       
      $alias = rawurlencode ($zeile[5]);
      setlocale (LC_TIME, 'de_DE');
      $datum = strftime ("%d.%b.%Y", $zeile[4]);
      $zeit = date ("H.i:s", $zeile[4]);

      echo "       <tr>
          <td class=\"col-hell\" width=\"100%\"><a href=\"beitraege.php?fid=&tid=$zeile[1]&bid=$zeile[2]&sid=-1\">$zeile[3]</a></td>
          <td align=\"center\" class=\"col-dunkel\"><a href=\"mitglieder-profil.php?alias=$alias\">$zeile[5]</a></td>
          <td align=\"center\" class=\"col-hell\"><nobr>$datum $zeit</nobr></td>
        </tr>";
    }
  }
  else
    echo '    <tr>
       <td align="center"><font size="+1">
         Es sind keine neuen Beitr&auml;ge seit Deinem letzten Besuch verfasst worden.
       </font></td>
     </tr>';

  mysql_close ($db);

  $option_val = array (25, 50, 100, 15, 30, 60, 24);
  $option_text = array ('letzten 25 Beitr&auml;ge',
                        'letzten 50 Beitr&auml;ge',
                        'letzten 100 Beitr&auml;ge',
                        'Beitr&auml;ge der letzten 15 Min.',
                        'Beitr&auml;ge der letzten 30 Min.',
                        'Beitr&auml;ge der letzten 60 Min.',
                        'Beitr&auml;ge der letzten 24 Std.');
  
  echo '</table>
        <table width="100%" border="0" cellpadding="20">
          <tr align="center">
            <form action="posteingang.php" method="post"
              <td>
                <b>Zeige die</b>
                <select name="menge" size="1" default="3">';

  for ($i = 0; $i < 7; $i++)
  {
    if ($option_val[$i] == $menge)
      echo "<option value=\"$option_val[$i]\" selected>$option_text[$i]</option>";
    else
      echo "<option value=\"$option_val[$i]\">$option_text[$i]</option>";
  }
                  
  echo '              </select>
                <button type="submit" title"Auswahl anzeigen"><b>Los!</b></button>
                <br><font size="-2">Es werden nur Beit&auml;ge die seit Deinem letzten Logout hinzukamen gezeigt</font>
              </td>
            </form>
          </tr>
       </table>';
 
  include ('leiste-unten.php');
  leiste_unten (NULL, $B_version, $B_subversion);
  echo '  </body>
</html>';
?>
