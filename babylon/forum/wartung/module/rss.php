<?PHP;
/* Copyright 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function rss_titel ()
{
  echo 'Bereistellung der RSS-Funktionalit&auml;t';
}

function rss_beschreibung ()
{
  echo 'Erm&ouml;glicht es mit RSS-Aggregatoren &Auml;nderungen im Forum zu verfolgen.';
}

function rss_min_version ()
{
  return array (0, 8);
}

function rss_max_version ()
{
  return array (0, 8);
}


function rss_wartung ()
{
// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $K_Egl = FALSE;
  $K_Lesen = 0;
  $K_Schreiben = 0;
  $K_Admin = 0;
  $K_AdminForen = 0;
  $K_Stil = 'std';
  $K_Signatur = '';
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  include_once ('../../gemeinsam/db-verbinden.php');
  include_once ('../../gemeinsam/benutzer-daten.php');
  include ('../konf/konf.php');
  include_once ('../fehler.php');

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ('Zugriff verweigert');

  if (!$B_wartung_start)
    die ('Um dieses Skript laufen zu lassen muss das Forum &uuml;ber das setzen der
          $B_wartung_start Variable in /forum/konf/konf.php gesperrt sein');

  if ($B_version != 0 or $B_subversion != 8)
    die ('Das Modul "RSS-Funktionalit&auml;t bereitstellen" darf nur mit einem Forum der Version 0.8x
          verwandt werden');

  mysql_query ("CREATE TABLE `Rss` (
                 `RssId` INT (1) UNSIGNED NOT NULL AUTO_INCREMENT,
                 `Titel` VARCHAR(255) NOT NULL,
                 `Autor` VARCHAR(255) NOT NULL,
                 `Link` TEXT NOT NULL,
                 `Inhalt` TEXT NOT NULL,
                 `Stempel` INT(1) UNSIGNED NULL,
                 PRIMARY KEY (`RssId`)
               ) TYPE=MyISAM")
    or fehler (__FILE__, __LINE__, 0, 'die Tabelle f&uuml;r die Rss-Funktionalit&auml;t konnte nicht angelegt werden.');

  mysql_query ("UPDATE Konf
                SET WertInt = '9'
                WHERE Schluessel = 'B_subversion'")
    or fehler (__FILE__, __LINE__, 0, 'Die Forumversion konnte nicht aktuallisiert werden');

  echo '<h2>Die RSS-Funitionalit&auml;t wurden erfolgreich eingerichtet.</h2>';
  
  echo "Bitte gib jetzt einen Titel und eine Beschreibung f&uuml;r den Rss-Feed ein
    <form action=\"module/.rss.php\" method=\"post\">
      <table>
        <tr>
          <td><b>Titel</b></td><td><input name=\"titel\" type\"text\" size=\"50\" maxlength=\"255\" value=\"$B_betreiber-Forum\"></input></td>
        </tr>
        <tr>
          <td><b>Beschreibung</b></td><td><input name=\"beschreibung\" type\"text\" size=\"50\" maxlength=\"255\" value=\"Neue Themen und Beitr&auml;ge des $B_betreiber-Forums\"></input></td>
        </tr>
        <tr>
          <td colspan=\"2\">Wenn Du die Felder leer l&auml;sst werden die Vorgabewerte eingetragen</td>
        </tr>
        <tr>
          <td colspan=\"2\">
            <p>&nbsp;<p>
            <button>&Auml;nderungen speichern</button>
          </td>
        </tr>
      </table>
    </form>
  </body>
</html>";
  exit(); 
}
?>
