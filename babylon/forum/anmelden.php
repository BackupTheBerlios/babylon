<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  include ('konf/konf.php');
  include ('wartung/wartung-info.php');
  
// Standart Konfiguration. Man darf absolut nix ;-)
$BenutzerId = -1;
$K_Egl = FALSE;
$K_Lesen = 0;
$K_Schreiben = 0;
$K_Admin = 0;
$K_AdminForen = 0;
$K_ThemenJeSeite = 6;
$K_BeitraegeJeSeite = 3;
$K_Stil = 'std';
$K_Signatur = '';
$K_SprungSpeichern = 0;
$K_BaumZeigen = 'j';

$fid = 0;
$tid = 0;
$sid = 0;
$bid = 0;
$neu = FALSE;

include ('../gemeinsam/benutzer-daten.php');

benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                      $K_AdminForen,  $K_ThemenJeSeite, $K_BeitraegeJeSeite,
		      $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);
				
if ($K_Schreiben == 0){
  echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"
       \"http://www.w3.org/TR/html4/loose.dtd\">
<html>
<head>
  <link href=\"/forum/stil/$B_standart_stil.css\" rel=\"stylesheet\" type=\"text/css\">
  <title>$B_seitentitel_start / Forum Anmeldung</title>
</head>
<body>";

wartung_ankuendigung ();

echo "  <h2>Anmelden</h2>

  <form action=\"anmelden-test.php\" method=\"post\">
    <table>
      <tr>
        <td colspan=\"2\"><b>Forumdaten</b</td>
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
        <td colspan=\"2\"><img src=\"/grafik/dummy.png\" width=\"1\" height=\"20\" border=\"0\" alt=\"\"></td>
      </tr>
      <tr>
        <td colspan=\"2\"><b>Personendaten</b></td>
      </tr>
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
          <button><img src=\"/grafik/PenWrite.png\" width=\"24\" height=\"24\" alt=\"\">Anmelden</button>
        </td>
        <td>
          <input type=\"hidden\" name=\"param\" value=\"$_POST[param]\">
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
            und das Passwort aus wenigstens sechs Zeichen bestehen.
          </font>
        </td>
      </tr>
      <tr>
        <td colspan=\"2\">
          <font size=\"-1\">
            <b>Datenschutzhinweis :</b><br>
            Dein Benutzername und Dein realer Vor- und Nachname sind f&uuml;r alle Besucher des Forums frei<br>
            einzusehen. Deine E-Mail-adresse ist nur den Betreibern dieses Forums ($B_betreiber)<br>
            zug&auml;nglich. Sie wird nicht an dritte weiter gegeben. Allerdings k&ouml;nnen wir nicht<br>
            f&uuml;r die Datensicherheit garantieren,  so da&szlig; bei einen Einburch in unseren Server diese<br>
            Daten entwendet werden k&ouml;nnten.
          </font>
        </td>
      </tr>
    </table>
  </form>";
  }
  else
  {
    echo 'Du hast auf diese Seite keine Zugriffsrechte';
  }
?>  
</body>
</html>

