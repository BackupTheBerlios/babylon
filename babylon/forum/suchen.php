<!--DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                       "http://www.w3.org/TR/html4/loose.dtd"-->
<?PHP;
 /* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
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

  echo '    <title>Forum / Foren</title>
  </head>
  <body>';
  
  wartung_ankuendigung (); 

  echo '    <table width="100%">';

  $gehe_zu = 'themen';
  leiste_oben ($K_Egl);

  $begriff = $_POST['suchbegriff'];
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
    echo '        <tr>
     <td>
       <table class="beitrag">
         <tr>
           <th class="ueber" colspan="2">Thema / Beitrag</th>
           <th class="ueber">Autor</th>
           <th class="ueber">Erstellt</th>
         </tr>';

    while ($zeile = mysql_fetch_row ($erg))
    {
      if (!((1 << $zeile[0] & $K_Lesen) or (1 << $zeile[0] & $B_leserecht)))
        continue;
        
      setlocale (LC_TIME, 'de_DE');
      $datum = strftime ("%d.%b.%Y", $zeile[5]);
      $zeit = date ("H.i:s", $zeile[5]);
      $inhalt = stripslashes ($zeile[4]);

      echo "       <tr>
          <td class=\"col-dunkel\" width=\"100%\"><a href=\"beitraege.php?fid=&tid=$zeile[1]&bid=$zeile[2]&sid=-1\">$zeile[3]</a></td>
          <td class=\"col-dunkel\"><a href=\"beitraege.php?fid=&tid=$zeile[1]&bid=-1&sid=-1\"><nobr>(Alle Beitr&auml;ge)</nobr></a></td>
          <td align=\"center\" class=\"col-dunkel\"><a href=\"mitglieder-profil.php?alias=$zeile[6]\">$zeile[6]</a></td>
          <td align=\"center\" class=\"col-dunkel\"><nobr>$datum $zeit</nobr></td>
        </tr>
        <tr>
          <td colspan=\"4\" align=\"left\" class=\"col-hell\" width=\"100%\">$inhalt</td>
        </tr>";
    }
    echo '</table>
        </td>
      </tr>';
  }
  
  include ('leiste-unten.php');
  leiste_unten ($begriff);
  echo '    </table>
  </body>
</html>';
;?>
