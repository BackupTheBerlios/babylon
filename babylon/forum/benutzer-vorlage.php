<?php
/* Copyright 2004, 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  $titel = 'Forum / Benutzervorlage erstellen';
  include_once ('kopf.php');

  if (!$K_AdminForen)
    die ('Zugriff verweigert');
 
  echo '    <table width="100%" cellpadding="6" cellspacing="0" border="0">
      <tr>
        <td>';

  $erg = mysql_query ("SELECT VorlageId, Name, RechtLesen,
                              RechtSchreiben, RechtAdmin, RechtAdminForen,
                              RechtLinks, RechtGrafik
                       FROM BenutzerVorlage")
    or die ('Die Bentuzervorlagen konnten nicht gelesen werden<br>' . mysql_error ());
    
  $vid = isset ($_GET['vid']) ? intval ($_GET['vid']) : 0;
  echo "<form action=\"benutzer-vorlage-test.php\" method=\"post\">
          <p>Zu verwendende Vorlage<br>
          <select name=\"vorlage\" size=\"1\" title=\"Zu bearbeitende Vorlage ausw&auml;hlen\">";

  $i = 0;
  while ($zeile = mysql_fetch_row ($erg))
  {
    if ($zeile[0] == $vid)
    {
      $name = $zeile[1];
      $val = $i;
      $recht_admin_foren = $zeile[5] ? 'checked' : '';
      $recht_links = $zeile[6] == 'j' ? 'checked' : '';
      $recht_grafik = $zeile[7] == 'j' ? 'checked' : '';
      echo "<option value=\"$zeile[0]\" selected>$zeile[1]</option>";
    }
    else
      echo "<option value=\"$zeile[0]\">$zeile[1]</option>";
    $i++;
  }
  echo '   </select>
         <button type="submit" name="vorlage_aendern">Aktualisieren</button><p>
       <td>
     </tr>';

//  if ($val < 0)
    mysql_data_seek ($erg, 0);
//  else 
    mysql_data_seek ($erg, $val);
  $zeile = mysql_fetch_row ($erg);

  $erg = mysql_query ("SELECT Titel, Inhalt, Gesperrt
                       FROM Beitraege
                       WHERE BeitragTyp = 1")
    or die ('Die Forennamen konnten nicht ermittelt werden.');

  echo '<tr>
          <td>
            <table border="1" cellpadding="4">
            <th>Forum</th>
            <th title="Der Benutzer hat das Recht das Forum zu lesen">Recht<br>Lesen</th>
            <th title="Der Benutzer hat das Recht in das Forum zu schreiben">Recht<br>Schreiben</th>
            <th title="Der Benutzer darf in dem Forum Beitr&auml;ge moderieren">Recht<br>Admin</th>';

  for ($x = 0; $x < 31; $x++)
  {
     $forum = mysql_fetch_row ($erg);
     $f = $forum[2] == 'j' ?  "<span style=\"text-decoration:line-through\">$forum[0]</span>" : "$forum[0]";
     $recht_lesen = 1 << $x & $zeile[2] ? 'checked' : '';
     $recht_schreiben = 1 << $x & $zeile[3] ? 'checked' : '';
     $recht_admin = 1 << $x & $zeile[4] ? 'checked' : '';
     echo "<tr>
             <td title=\"$forum[1]\">$f</td>
             <td align=\"center\"><input type=\"checkbox\" name=\"rl$x\" $recht_lesen></td>
             <td align=\"center\"><input type=\"checkbox\" name=\"rs$x\" $recht_schreiben></td>
             <td align=\"center\"><input type=\"checkbox\" name=\"ra$x\" $recht_admin></td>
           </tr>";
  }
  echo "    </table>
          </td>
        </tr>
        <tr>
          <td>
            <input type=\"checkbox\" name=\"raf\" $recht_admin_foren title=\"Der Benutzer darf Foren anlegen
und andere Benutzer verwalten\">
            Forenadministrationsrecht</input>
          </td>
        </tr>
        <tr>
          <td>
            <input type=\"checkbox\" name=\"links\" $recht_links title=\"Der Benutzer darf Links
zu externen Adressen setzen\">
              Links erlauben
            </input>
          </td>
        </tr>
         <tr>
          <td>
            <input type=\"checkbox\" name=\"grafik\" $recht_grafik title=\"Der Benutzer darf
externe Grafiken einbinden\">
              Grafiken erlauben</input>
          </td>
        </tr>";
   echo "<tr>
       <td>
         <table cellpadding=\"6\" cellspacing=\"0\">
           <tr>
             <p>&nbsp;<p>
             <td>&Auml;nderungen in der aktuellen Vorlage Speichern<hr></td>
             <td><button type=\"submit\" name=\"speichern\" value=\"1\">Speichern</button><br>&nbsp;</td>
             <input type=\"hidden\" name=\"alte_vorlage\" value=\"$name\">
           </tr>
           <tr>
             <td>Als neue Vorlage under folgenden Namen speichern
               <input value=\"Neue Vorlage\" name=\"vorlage_name\"></input>
             <hr></td>
             <td><button type=\"submit\" name=\"neue_vorlage\" value=\"1\">Speichern</button><br>&nbsp;</td>
           </tr>      
         </table>
       </td>
     </tr>
   </form>";



  include ('leiste-unten.php');
  leiste_unten (NULL, $B_version, $B_subversion);
  echo '  </body>
</html>';
?>
