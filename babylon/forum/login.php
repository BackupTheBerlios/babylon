<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  include ('../gemeinsam/msie.php');
  $msiepng = msie_png();
  include ('konf/konf.php');
  $Benutzer = $_POST['benutzer'];
  $GeheZu = $P_POST['gehe_zu'];
  $Param = $P_POST['param'];
  
  echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<html>
<head>
<link href=\"/forum/stil/std.css\" rel=\"stylesheet\" type=\"text/css\">
<title>$B_seitentitel_start / Forum Login</title>
</head>
<body>
  <h2>Einloggen</h2>
  <table>
    <form action=\"login-test.php\" method=\"post\">
      <tr>
        <td>Benutzername</td>
        <td><input name=\"benutzer\" type=\"text\" size=\"30\" maxlength=\"32\" value=\"$benutzer\"><p></td>
      </tr>
      <tr>
        <td>Passwort</td>
        <td><input name=\"passwort\" type=\"password\" size=\"30\" maxlength=\"32\"></td>
      </tr>
      <tr>
        <td><img src=\"/grafik/dummy.png\" width=\"1\" height=\"16\" alt=\"\"></td>
      </tr>
      <tr>
        <td colspan=\"2\">
          <button type=\"submit\" name=\"gehe_zu\" value=\"$GeheZu\"><img src=\"/grafik/Key$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Einloggen</button>
          <input type=\"hidden\" name=\"param\" value=\"$Param\"></input>
        </td>
      </tr>
    </form>
    <tr>
      <td colspan=\"2\">&nbsp;<br><font size=\"-1\"><b>Ab diesen Punkt m&uuml;ssen Cookies aktiviert sein</b></font></td>
    </tr>     
    <form  action=\"passwort-anfordern.php\" method=\"post\">
      <tr>
        <td colspan=\"2\"><img src=\"/grafik/dummy.png\" width=\"1\" height=\"40\" border=\"0\" alt=\"\"></td>
      </tr>
      <tr>
        <td colspan=\"2\"><h3>Passwort vergessen?</h3></td>
      </tr>
      <tr>
        <td colspan=\"2\">Gib deinen Benutzername und die E-Mailadresse an, unter der Du Dich angemeldet hast:</td>
      </tr>
      <tr>
        <td>Benutzername</td>
        <td><input name=\"benutzer\" type=\"text\" size=\"30\" maxlength=\"32\"><p></td>
      </tr>
      <tr>
        <td>E-mail</td>
        <td><input name=\"email\" type=\"text\" size=\"30\" maxlength=\"32\"><p></td>
      </tr>
      <tr>
        <td colspan=\"2\"><button type=\"submit\" name=\"flaeche\" value=\"passwort-anfordern\">Passwort anfordern</button></td>
      </tr>
    </form>
  </table>
  <font size=\"-1\">
    Um Missbrauch zu vermeiden, wird bis zur Aktivierung Deines neuen Passworts, Deine IP hinterlegt.
  </font>
</body>
</html>";
;?>

