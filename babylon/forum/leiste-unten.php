<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function leiste_unten ($suchtext, $version, $subversion)
{
  if ($suchtext)
    $suche = ' value="' . stripslashes (htmlentities ($suchtext)) . '"';
  else
    $suche = '';

  echo '      <tr>
        <td>
          <table>
            <tr>
              <td width="33%" height="100%" valign="bottom">';

  // wir geben das BerliOS logo nur fuer das babylon-projekt auf berlios.de aus
  if (substr_count ($_SERVER['HTTP_HOST'], 'babylon.berlios.de'))
    echo '                Danke f&uuml;rs Hosten!<br>
                <A href="http://developer.berlios.de">
                  <IMG src="http://developer.berlios.de/bslogo.php?group_id=1674" width="124" height="32" border="0" alt="BerliOS Developer Logo">
                </A>';
                
  echo "               </td>
              <td width=\"34%\" height=\"100%\" valign=\"bottom\" align=\"center\">
                <a href=\"http://babylon.berlios.de\" alt=\"Zu den Seiten des Babylon-Projekts\" title=\"Zu den Seiten des Babylon-Projekts\">
                  <img src=\"/grafik/Babylon$msiepng.png\" border=\"0\" alt=\"\">
                </a><br>
                <font color=\"#0000ff\"><sup>Version $version.$subversion</sup></font>
              </td>
              <td width=\"33%\" height=\"100%\" valign=\"center\" align=\"right\">
                <form action=\"suchen.php\" method=\"post\">
                  <input name=\"suchbegriff\" type=\"text\" size=\"30\" maxlength=\"64\"$suche>
                  <button name=\"suchen\" type=\"submit\" title=\"Suchen\">
                    <img src=\"/grafik/Suchen$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Suchen
                  </button>
                </form>
              </td>
            </tr>
          </table>
        </td>
      </tr>\n";
}
?>
