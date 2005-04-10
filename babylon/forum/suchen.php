<?php
 /* Copyright 2004, 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  $begriff = $_POST['suchbegriff'];
  $titel = "Forum / Suche nach $begriff";
  include_once ('kopf.php');

  $limit = 50;

  $erg = mysql_query ('SELECT VERSION()');
  $zeile = mysql_fetch_row ($erg);
  $version = explode ('.', $zeile[0], 3);
  if (($version[0] >= 4) and (($version[1] > 0) or (intval ($version[2]) > 0)))
    $bmode = ' IN BOOLEAN MODE';
  else
    $bmode = '';

  $erg = mysql_query ("SELECT ForumId, ThemaId, BeitragId, Titel, Inhalt, StempelLetzter, Autor
                       FROM Beitraege
                       WHERE BeitragTyp & 8 = 8
                         AND Gesperrt = 'n'
                         AND MATCH (Inhalt)
                           AGAINST ('$begriff'$bmode)
                       LIMIT $limit")
    or die ('Datenbankfehler bei der Suchanfrage<br>' . mysql_error ());


  if (mysql_num_rows ($erg))
  {
    echo '      <table class="beitrag">
         <tr>
           <th class="ueber" colspan="2">Thema / Beitrag</th>
           <th class="ueber">Autor</th>
           <th class="ueber">Erstellt</th>
         </tr>';

    while ($zeile = mysql_fetch_row ($erg))
    {
      if (!((1 << $zeile[0] & $K_Lesen) or (1 << $zeile[0] & $B_leserecht)))
        continue;
       
      $alias = rawurlencode ($zeile[6]);
      setlocale (LC_TIME, 'de_DE');
      $datum = strftime ("%d.%b.%Y", $zeile[5]);
      $zeit = date ("H.i:s", $zeile[5]);
      $inhalt = stripslashes ($zeile[4]);

      echo "       <tr>
          <td class=\"col-dunkel\" width=\"100%\"><a href=\"beitraege.php?fid=&tid=$zeile[1]&bid=$zeile[2]&sid=-1\">$zeile[3]</a></td>
          <td class=\"col-dunkel\"><a href=\"beitraege.php?fid=&tid=$zeile[1]&bid=-1&sid=-1\"><nobr>(Alle Beitr&auml;ge)</nobr></a></td>
          <td align=\"center\" class=\"col-dunkel\"><a href=\"mitglieder-profil.php?alias=$alias\">$zeile[6]</a></td>
          <td align=\"center\" class=\"col-dunkel\"><nobr>$datum $zeit</nobr></td>
        </tr>
        <tr>
          <td colspan=\"4\" align=\"left\" class=\"col-hell\" width=\"100%\">$inhalt</td>
        </tr><tr><td><br></td></tr>";
    }
    echo '</table>';
  }
  else
  {
    $sb = stripslashes ($begriff);
    echo "  <table border=\"2\" width=\"100%\">
    <tr>
      <td align=\"center\">
        Die Suche nach <i>$sb<i> hat keine Treffer ergeben
      </tr>
    </table>";
  }
  
  include ('leiste-unten.php');
  leiste_unten ($begriff, $B_version, $B_subversion);
  echo '  </body>
</html>';
?>
