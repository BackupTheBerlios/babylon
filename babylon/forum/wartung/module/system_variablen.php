<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function system_variablen_titel ()
{
  echo "Systemvariablen";
}

function system_variablen_beschreibung ()
{
  echo "Konfigurationsmodul zur Anpassung der Grundkonfiguration des Forums. Hiermit
        k&ouml;nnen grundlegende Verhaltensweisen angepasst werden";
}

function system_variablen_wartung ()
{
// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $K_Egl = FALSE;
  $K_Lesen = 0;
  $K_Schreiben = 0;
  $K_Admin = 0;
  $K_AdminForen = 0;
  $K_Stil = "std";
  $K_Signatur ="";
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  include ("../konf/konf.php");
  if ($B_version != 0 or $B_subversion < 2)
    die ('Das Modul zur Konfiguration der Systemvariablen steht erst ab Version 0.2
          des Forums zur Verf&uuml;gung');
  
  include_once ("../../gemeinsam/benutzer-daten.php");

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ("Zugriff verweigert");

  if (!$B_wartung_start)
    die ('Um dieses Skript laufen zu lassen muss das Forum gesperrt sein');

// es ist nicht noetig, dass wir die maximallaenge der eintraege so niedirg setzen. doch sehe ich
// in laengeren keinen sinn (diese sind eigentlich schon zu lang) zum anderen geht es sonst nur zu
// lasten der performance.

  $betreiber = htmlentities (stripslashes ($B_betreiber));
  $seitentitel_start = htmlentities (stripslashes ($B_seitentitel_start));
  $startseite_link = htmlentities (stripslashes ($B_startseite_link));
  $mail_absender = htmlentities (stripslashes ($B_mail_absender));
  $cookie_id = htmlentities (stripslashes ($B_cookie_id));
  $cookie_sw = htmlentities (stripslashes ($B_cookie_sw));

  echo "<h2>Systemweite Konfigurationsvariablen</h2>
        <form action=\"module/.system_variablen.php\" method=\"post\">
          <table>
             <tr>
              <td colspan=\"2\">
                <h3>Bezeichner</h3>
              </td>
            </tr>         
            <tr>
              <td>
                <input name=\"betreiber\" type=\"text\" size=\"30\" maxlength=\"1024\" value=\"$betreiber\"></input>
              </td>
              <td>
                Der Name des Forum-Betreibers (Projekt, Firma, ...)
              </td>
            </tr>
            <tr>
              <td>
                <input name=\"seitentitel_start\" type=\"text\" size=\"30\" maxlength=\"1024\" value=\"$seitentitel_start\"></input>
              </td>
              <td>
                Der Start der meisten Seitentitel, wie sie in der Titelleiste des Browsers dargestellt
                werden
              </td>
            </tr>
            <tr>
              <td> 
                <input name=\"startseite_link\" type=\"text\" size=\"30\" maxlength=\"1024\" value=\"$startseite_link\"></input>
              </td>
              <td>
                Der Hinweistext des Forumlogos in der linken oberen Ecke, der beim &uuml;berfahren
                mit der Maus angezeigt wird
               </td>
            </tr>
            <tr>
              <td>              
                <input name=\"mail_absender\" type=\"text\" size=\"30\" maxlength=\"1024\" value=\"$mail_absender\"></input>
              </td>
              <td>
                Die Adresse unter der das Forum, z.B. bei Anforderung eines neuen Passworts, E-Mails
                verschickt
              </td>
            </tr>


            <tr>
              <td colspan=\"2\">
                <h3>Cookies</h3>
              </td>
            </tr>
            <tr>
              <td>
                <input name=\"cookie_id\" type=\"text\" size=\"30\" maxlength=\"1024\" value=\"$cookie_id\"></input>
              </td>
              <td>
                Name unter dem der Id-Cookie beim Empf&auml;nger abgelegt wird
              </td>
            </tr>
            <tr>
              <td>
                <input name=\"cookie_sw\" type=\"text\" size=\"30\" maxlength=\"1024\" value=\"$cookie_sw\"></input>
              </td>
              <td>
                Name unter dem der Schl&uuml;ssel-Cookie beim Empf&auml;nger abgelegt wird
              </td>
            </tr>
          </table>
          <table>
            <tr>
              <td colspan=\"2\">
                <h3>Atavare</h3>
              </td>
            </tr>
            <tr>
              <td>
                <input name=\"atavar_max_kb\" type=\"text\" size=\"4\" maxlength=\"4\" value=\"$B_atavar_max_kb\"></input>
              </td>
              <td>
                Die Maximalgr&ouml;&szlig;e von Atavaren in KByte die ein Benutzer hochladen darf
              </td>
            </tr>
            <tr>
              <td>
                <input name=\"atavar_max_breite\" type=\"text\" size=\"4\" maxlength=\"4\" value=\"$B_atavar_max_breite\"></input>
              </td>
              <td>
                Die Maximale Breite von Atavaren in Pixel die ein Benutzer hochladen darf
              </td>
            </tr>
            <tr>
              <td>
                <input name=\"atavar_max_hoehe\" type=\"text\" size=\"4\" maxlength=\"4\" value=\"$B_atavar_max_hoehe\"></input>
              </td>
              <td>
                Die Maximale H&ouml;he von Atavaren in Pixel die ein Benutzer hochladen darf
              </td>
            </tr>
        

            <tr>
              <td colspan=\"2\">
                <h3>Sonstiges</h3>
              </td>
            </tr>
            <tr>
              <td>
                <input name=\"themen_je_seite\" type=\"text\" size=\"4\" maxlength=\"4\" value=\"$B_themen_je_seite\"></input>
              </td>
              <td>
                Die Anzahl Themen je Seite, die f&uuml;r nicht eingeloggte Besucher angezeigt werden
              </td>
            </tr>
            <tr>
              <td>
                <input name=\"beitraege_je_seite\" type=\"text\" size=\"4\" maxlength=\"4\" value=\"$B_beitraege_je_seite\"></input>
              </td>
              <td>
                Die Anzahl Beitr&auml;ge je Seite, die f&uuml;r nicht eingeloggte Besucher angezeigt werden
              </td>
            </tr>
            <tr>
              <td>
                <input name=\"profil_links\" type=\"checkbox\" ";
  if ($B_profil_links)
    echo "checked";
  echo "            ></input>
              </td>
              <td>
                Ob in den Benutzerprofilen die Hompage- und die E-Mail-Adresse als Links angezeigt werden
              </td>
            </tr>

        
            <tr>
              <td colspan=\"2\">
                <p>&nbsp;<p>
                <button>&Auml;nderungen speichern</button>
              </td>
            </td>
          </table>
        </form>
        <p>";

}
?>
