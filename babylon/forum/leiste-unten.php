<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function leiste_unten ()
{
  echo "      <tr>
        <td>
          <table>
            <tr>
              <td width=\"33%\" height=\"100%\" valign=\"bottom\">
             </td>
              <td width=\"34%\" height=\"100%\" valign=\"bottom\" align=\"center\">
                <a href=\"http://babylon.berlios.de\" alt=\"Zu den Seiten des Babylon-Projekts\" title=\"Zu den Seiten des Babylon-Projekts\">
                  <img src=\"/grafik/Babylon$msiepng.png\" border=\"0\" alt=\"\">
                </a>
              </td>
              <td width=\"33%\" height=\"100%\" valign=\"center\" align=\"right\">
                <form action=\"suchen.php\" method=\"post\">
                  <input name=\"benutzer\" type=\"text\" size=\"30\" maxlength=\"64\">
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
