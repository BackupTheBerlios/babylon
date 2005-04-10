<?php
/* Copyright 2003, 2004, 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  $titel = 'Babylon - Einstellungen / Profil';
  include ('kopf.php');

// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $Benutzer = '';
  $K_Egl = FALSE;
  $K_Stil = '';
  $P_NameZeigen = 'n';
  $K_VName = '';
  $K_NName = '';
  $P_Ort = '';
  $P_EMail = '';
  $P_Homepage = '';

  benutzer_daten_profil ($BenutzerId, $Benutzer, $K_Egl,
                         $K_Stil, $P_NameZeigen, $P_Nachricht,
                         $P_NachrichtAnonym, $P_Ort, $P_EMail,
                         $P_Homepage, $P_Atavar);


  if (!$K_Egl)
    fehler (NULL, 0, 1, 'Zugriff verweigert');

  $name_zeigen = $P_NameZeigen == 'j' ? 'checked' : '';
  $nachricht = $P_Nachricht == 'j' ? 'checked' : '';
  $nachricht_anonym = $P_NachrichtAnonym == 'j' ? 'checked' : '';
  $ort = htmlspecialchars ($P_Ort);
  $homepage = htmlspecialchars ($P_Homepage);
  $email = htmlspecialchars ($P_EMail);
  $atavar_max_bytes = $B_atavar_max_kb * 1024;
  
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
        <td class=\"reiter_aktiv\">
          Profil
        </td>       
        <td class=\"reiter_inaktiv\">
          <a href=\"benutzer-konf4.php\">Pers&ouml;nliches<a>
        </td>
      </tr>
    </table>
    <div class=\"konfiguration\">
    <form name=\"konf\" action=\"benutzer-konf-test3.php\" method=\"post\" enctype=\"multipart/form-data\">
      <table>
        <tr>
          <td colspan=\"2\">
            <input type=\"checkbox\" name=\"name_zeigen\" $name_zeigen>Name ver&ouml;ffentlichen</input>
          </td>
          <td>
            Soll Dein realer Name, wie Du ihn in den pers&ouml;nlichen Daten eingetragen hast,
            f&uuml;r die anderen Forenbesucher sichtbar sein?
          </td>
        </tr>
        <tr>
          <td colspan=\"2\">
            <input type=\"checkbox\" name=\"nachricht\" $nachricht>Private Nachrichten annehmen</input>
          </td>
          <td>
            Soll es m&ouml;glich sein Dir private Nachrichten per E-Mail zukommen zu lassen? Diese
            Nachrichten werden vom Forum aus versand, so dass der Absender deine E-Mail Adresse
            nicht erf&auml;hrt.
          </td>
        </tr>
        <tr>
          <td colspan=\"2\">
            <input type=\"checkbox\" name=\"nachricht_anonym\" $nachricht_anonym>Anonyme Nachrichten annehmen</input>
          </td>
          <td>
            Willst Du auch private Nachrichten annehmen, bei denen Du nicht den realen Namen und die
            E-Mail Adresse des Absenders erf&auml;hrst (setzt voraus, dass Du private Nachrichten
            annimmst)?
          </td>
        </tr>
        <tr>
          <td>Wohnort</td>
	  <td><input name=\"ort\" type=\"text\" size=\"30\" maxlength=\"255\" value=\"$ort\"></td>
          <td>Home sweet home</td>
	</tr>
        <tr>
          <td>Homepage</td>
	  <td><input name=\"homepage\" type=\"text\" size=\"30\" maxlength=\"255\" value=\"$homepage\"></td>
          <td>Dein virtuelles Zuhause</td>
	</tr>
        <tr>
	  <td>E-Post</td>
	  <td><input name=\"email\" type=\"text\" size=\"30\" maxlength=\"255\" value=\"$email\"></td>
	  <td>Deine &ouml;ffentliche E-Post Adresse. Dies kann Dir sehr viel Spam einbringen...</td>
	</tr>
        <tr>
          <td>Atavar</td>
          <td><input name=\"atavar\" type=\"file\" size=\"30\" maxlength=\"$atavar_max_bytes\" accept=\"image/jpeg\"></td>
          <td>Ein Bild von Dir. Bitte beachte keine Bilder hoch zu laden, von denen Du nicht die
              Nutzungsrechte hast.</td>
        </tr>
        <tr>
          <td></td>
          <td colspan=\"2\">";
          if (isset ($_GET['atavar_format']))
            echo 'Das gew&auml;hlte Dateiformat wird nicht unterst&uuml;tzt.<br>
                  Nur Bilder im Jpeg-, PNG-, und Gif-Format sind erlaubt.';
          else if (isset ($_GET['atavar_groesse']))
            echo "Das gew&auml;hlte Atavar ist zu gro&szlig;<br>
                  maximal:<br>
                  kByte: $B_atavar_max_kb<br>
                  Breite: $B_atavar_max_breite Pixel<br>
                  H&ouml;he: $B_atavar_max_hoehe Pixel";
          else
            if ($P_Atavar == 'j')
              echo "    <img src=\"atavar-ausgeben.php?atavar=$BenutzerId\"><button name=\"atavar_loeschen\">Atavar l&ouml;schen</button>";
          echo "           </td>
        </tr>
        <tr><td colspan=\"3\"><img src=\"/grafik/dummy.png\" width=\"1\" height=\"20\"></td></tr>
        <tr>
	  <td colspan=\"3\">
	    <h3>
              Diese Daten &uuml;ber Dich sind f&uuml;r alle anderen Forumsbesucher sichtbar. Was
              Du nicht von Dir preisgeben magst la&szlig; einfach leer.<p>
              Angaben, die Du nicht &auml;ndern willst, kannst Du leer bzw.
              auf den vorgegebenen Werten belassen.
	    </h3>
	  </td>
	</tr>
	<tr><td colspan=\"3\"><img src=\"/grafik/dummy.png\" width=\"1\" height=\"30\"></td></tr>
        <tr>
          <td colspan=\"3\">
            <button name=\"speichern\"><img src=\"/grafik/Speichern$msiepng.png\" width=\"24\" height=\"24\" alt=\"\">&Auml;nderungen Speichern</button>
          </td>
       </tr>
      </table>
    </form>
    </div>\n";

  include ('leiste-unten.php');
  leiste_unten (NULL, $B_version, $B_subversion);
  echo '  </body>
</html>';

?>
