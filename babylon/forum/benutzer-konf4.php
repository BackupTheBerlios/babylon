<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                       "http://www.w3.org/TR/html4/loose.dtd">

<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  include_once ('konf/konf.php');
  include ('wartung/wartung-info.php');

// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $K_Egl = FALSE;
  $K_EMail = '';
  $K_Alias = '';
  $K_VName = '';
  $K_NName = '';
  $K_Veroeffentlichen = 'n';

  include_once ('../gemeinsam/db-verbinden.php');
  include_once ('../gemeinsam/benutzer-daten.php');
  include ('../gemeinsam/msie.php');
  $msiepng = msie_png ();
  include ('leiste-oben.php');

  benutzer_daten_persoenlich ($BenutzerId, $K_Egl, $K_Stil,
                              $K_EMail, $K_Alias,
			      $K_VName, $K_NName);

  echo '<html>
  <head>';
  include ('konf/meta.php');
  metadata ($_SERVER['SCRIPT_FILENAME']);

  $stil_datei = "stil/$K_Stil.php";
  include ($stil_datei);
  css_setzen ();

  echo '    <title>Babylon - Einstellungen / Pers&ouml;nliches</title>';

  if (!$K_Egl)
    die ('Zugriff verweigert');

  $vname = stripslashes ($K_VName);
  $nname = stripslashes ($K_NName);
  $email = stripslashes ($K_EMail);
  
  echo '  </head>
  <body>';
  
  wartung_ankuendigung ();
  
  echo "    <h2>Forumseinstellungen</h2>
    <table cellspacing=\"0\" cellpadding\"0\">
      <tr>
        <td class=\"reiter_inaktiv\">
          <a href=\"benutzer-konf1.php\">Verhalten</a>
        </td>
        <td class=\"reiter_inaktiv\">
          <a href=\"benutzer-konf2.php\">Aussehen</a>
        </td>
        <td class=\"reiter_inaktiv\">
          <a href=\"benutzer-konf3.php\">Profil</a>
        </td>       
        <td class=\"reiter_aktiv\">
          Pers&ouml;nliches
        </td>
      </tr>
    </table>
    <div class=\"konfiguration\">
    <form name=\"konf\" action=\"benutzer-konf-test4.php\" method=\"post\">
      <table>
        <tr>
          <td>Benutzername</td>
	  <td><input class=\"inaktiv\" name=\"benutzer\" type=\"text\" size=\"30\" maxlength=\"32\" value=\"$K_Alias\" readonly></td>
          <td>Die &Auml;nderung des Alias ist nicht m&ouml;glich</td>
	</tr>
	<tr><td colspan=\"3\"><img src=\"/grafik/dummy.png\" width=\"1\" height=\"20\"></td></tr>
        <tr>
	  <td>Passwort</td>
	  <td><input name=\"passwort1\" type=\"password\" size=\"30\" maxlength=\"32\"></td>
	  <td>Mindestens 6 Zeichen lang</td>
	</tr>
        <tr>
	  <td>Passwort<br><font size=\"-2\">(Kontrolle)</font></td>
	  <td><input name=\"passwort2\" type=\"password\" size=\"30\" maxlength=\"30\"></td>
	  <td>Es sollte damit es nicht zu leicht erraten werden
	    kann aus Buchstaben, Ziffern und Sonderzeichen bestehen</td>
	</tr>
        <tr><td colspan=\"3\"><img src=\"/grafik/dummy.png\" width=\"1\" height=\"20\"></td></tr>
	<tr>
	  <td>E-Post</td>
	  <td><input name=\"email\" type=\"text\" size=\"30\" maxlength=\"255\" value=\"$email\"></td>
	  <td>
            Bitte achte immer darauf, da&szlig; Deine E-Mail Adresse aktuell hinterlegt
	    ist. An diese wird z.B., wenn Du Dein Passwort vergessen hast, ein
	    Ersatz gesandt.
	  </td>
	</tr>
	<tr><td colspan=\"3\"><img src=\"/grafik/dummy.png\" width=\"1\" height=\"20\"></td></tr>
        <tr>
	  <td>Vor- / Nachname</td>
	  <td><input name=\"vname\" type=\"text\" size=\"30\" maxlength=\"32\" value=\"$vname\"><img src=\"/grafik/dummy.png\" width=\"20\" height=\"1\"></td>
	  <td>
	    <input name=\"nname\" type=\"text\" size=\"30\" maxlength=\"32\" value=\"$nname\">
	  </td>
	</tr>
	<tr><td colspan=\"3\"><img src=\"/grafik/dummy.png\" width=\"1\" height=\"30\"></td></tr>
        <tr>
          <td colspan=\"2\">
            <button name=\"speichern\"><img src=\"/grafik/Speichern$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">&Auml;nderungen Speichern</button>
          </td>
          <td align=\"right\">
            <button name=\"zurueck\"><img src=\"/grafik/PfeilLinks$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">Zur&uuml;ck zum Forum</button>
          </td>
        </tr>
      </table>
    </form>
    </div>
  </body>
</html>\n";
?>
