<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function leiste_oben ($egl, $startseite_link)
{
  $startseite_link = htmlentities (stripslashes ($startseite_link));
  echo "    <table width=\"100%\">
      <tr>
        <td align=\"left\" style=\"width:100%\">
          <table width=\"100%\">\n";
                
  if (is_readable ('../gemeinsam/impressum.php'))
    echo '           <tr>
           <td>
             <a href="../gemeinsam/impressum.php"><sub>Impressum<p></sub></a>
           </td>
         </tr>';
  echo "            <tr>
              <td>
                <a href=\"../index.php\" title=\"$startseite_link\"><img src=\"/grafik/forum-logo$msiepng.png\" border=\"0\" alt=\"$startseite_link\"></a>
              </td>
            </tr>
          </table>
        </td>
        <td align=\"right\">
          <form action=\"foren.php\" method=\"post\">
            <button type=\"submit\" title=\"zur&uuml;ck zur Foren&uuml;bersicht\"><img src=\"/grafik/Heim.png\" width=\"24\" height=\"24\" alt=\"\">Foren&uuml;bersicht</button>
          </form>
        </td>
        <td align=\"right\">
          <form action=\"mitglieder-liste.php\" method=\"post\">
            <button type=\"submit\" title=\"Die Mitglieder dieses Forums\"><img src=\"/grafik/Mitglieder$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Die Mitglieder</button>
          </form>
        </td>\n";
    
  if ($egl)
  {
    echo "       <td align=\"right\">
         <form action=\"benutzer-konf1.php\" method=\"post\">
           <button type=\"submit\" title=\"Passe Deine pers&ouml;nlichen\nEinstellungen an\"><img src=\"/grafik/Heim$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Mein Forum</button>
         </form>
       </td>
       <td align=\"right\"><img src=\"/grafik/dummy.png\" width=\"32\" height=\"1\" border=\"0\" alt=\"\"></td>
       <td align=\"right\">
         <form action=\"logout.php\" method=\"post\">
           <button type=\"submit\" title=\"Aus dem Forum ausloggen\"><img src=\"/grafik/Key$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Ausloggen</button>
         </form>
       </td>\n";
  }
  else
  {
  
    echo "        <td align=\"right\">
          <form action=\"login.php\" method=\"post\">
            <button type=\"submit\" title=\"Passe Deine pers&ouml;nlichen\nEinstellungen an\"><img src=\"/grafik/Heim$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Mein Forum</button>
          </form>
        </td>
        <td align=\"right\">
          <form action=\"anmelden-bedingung.php\" method=\"post\">
            <button type=\"submit\" title=\"Melde Dich im Forum an\num selbst Beitr&auml;ge zu verfassen\"><img src=\"/grafik/PenWrite$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Anmelden</button>
          </form>
        </td>
        <td align=\"right\"><img src=\"/grafik/dummy.png\" width=\"32\" height=\"1\" border=\"0\" alt=\"\"></td>
        <td>
          <form action=\"login.php\" method=\"post\">
            <button type=\"submit\" title=\"In's Forum einloggen\"><img src=\"/grafik/Key$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Einloggen</button>
          </form>
        </td>\n";
  }
  echo "      </tr>
    </table>\n";
}
?>
