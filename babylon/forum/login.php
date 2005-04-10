<?php
/* Copyright 2003, 2004, 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  include ('../gemeinsam/msie.php');
  $msiepng = msie_png();
  include ('konf/konf.php');
  $Benutzer = $_POST['benutzer'];
  $Param = $P_POST['param'];
  
  echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"
     \"http://www.w3.org/TR/html4/loose.dtd\">
<html>
<head>
<link href=\"/forum/stil/$B_standart_stil.css\" rel=\"stylesheet\" type=\"text/css\">
<title>$B_seitentitel_start / Forum Login</title>
</head>
<body>
  <h2>Einloggen</h2>
  <form action=\"login-test.php\" method=\"post\">
  <table>
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
        <button type=\"submit\"><img src=\"/grafik/Key$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Einloggen</button>
        <input type=\"hidden\" name=\"param\" value=\"$Param\">
      </td>
    </tr>
  </table>
  </form>
  
  <font size=\"-1\"><b>Ab diesen Punkt m&uuml;ssen Cookies aktiviert sein</b></font><br>
  <img src=\"/grafik/dummy.png\" width=\"1\" height=\"30\" border=\"0\" alt=\"\">
  <h3>Passwort vergessen?</h3>
      
  <form  action=\"passwort-anfordern.php\" method=\"post\">
  <table>
    <tr>
      <td colspan=\"2\">Gib deinen Benutzername und die E-Mailadresse an, unter der Du Dich angemeldet hast:</td>
    </tr>
    <tr>
      <td>Benutzername</td>
      <td width=\"100%\"><input name=\"benutzer\" type=\"text\" size=\"30\" maxlength=\"32\"><p></td>
    </tr>
    <tr>
      <td>E-mail</td>
      <td><input name=\"email\" type=\"text\" size=\"30\" maxlength=\"32\"><p></td>
    </tr>
    <tr>
      <td colspan=\"2\"><button type=\"submit\" name=\"flaeche\" value=\"passwort-anfordern\">Passwort anfordern</button></td>
    </tr>
  </table>
  </form>
  
  <font size=\"-1\">
    Um Missbrauch zu vermeiden, wird bis zur Aktivierung Deines neuen Passworts, Deine IP hinterlegt.
  </font>
</body>
</html>";
?>
