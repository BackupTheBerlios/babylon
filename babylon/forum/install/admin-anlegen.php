<?PHP;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

echo "  <h2>Forenadministrator anlegen</h2>\n
  <table>
    <form action=\"admin-anlegen-test.php\" method=\"post\">
      <tr>
        <td collspan=\"2\"><b>Forumdaten</b</td>
      </tr>
      <tr>
        <td>Benutzername</td>
        <td><input name=\"benutzer\" type=\"text\" size=\"30\" maxlength=\"32\" value=\"$_POST[benutzer]\"></td>
      </tr>
      <tr>
        <td>Passwort</td>
        <td><input name=\"passwort1\" type=\"password\" size=\"30\" maxlength=\"32\"></td>
      </tr>
      <tr>
        <td>Passwort<br><font size=-1>(Kontrolle)</font></td>
        <td><input name=\"passwort2\" type=\"password\" size=\"30\" maxlength=\"30\"></td>
      </tr>
      <tr>
        <td collspan=\"2\"><img src=\"/grafik/dummy.png\" width=\"1\" height=\"20\" border=\"0\" alt=\"\"></td>
      </tr>
      <tr>
        <td collspan=\"2\"><b>Personendaten</b></td>
      <tr/>
      <tr>
        <td>E-mail</td>
        <td><input name=\"email\" type=\"text\" size=\"30\" maxlength=\"255\" value=\"$_POST[email]\"></td>
      </tr>
      <tr>
        <td>Vor- / Nachname</td>
        <td><input name=\"vname\" type=\"text\" size=\"30\" maxlength=\"32\" value=\"$_POST[vname]\"><input name=\"nname\" type=\"text\" size=\"30\" maxlength=\"32\" value=\"$_POST[nname]\"><p></td>
      </tr>
      <tr>
        <td colspan=\"2\">&nbsp;<p></td>
      </tr>
      <tr>
        <td>
          <button type=\"submit\">Anmelden</button>
        </td>
      </tr>
      <tr>
        <td colspan=\"2\"><img src=\"/grafik/dummy.png\" width=\"1\" height=\"40\" border=\"0\" alt=\"\"></td>
      </tr>
      <tr>
        <td colspan=\"2\">
          <font size=\"-1\">
            Bitte beachte das Dein Passwort aus Buchstaben, Ziffern und Sonderzeichen bestehen sollte,<br>
            damit es nicht erraten werden kann. Ausserdem mu&szlig; der Benutzername aus wenigstens drei<br>
            und das Passwort aus wenigstens sechs Zeichen bestehen.<p>
          </font>
        </td>
      </tr>
    </form>
  </table>";
?>  

