<?PHP;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function antwortmail_titel ()
{
  echo 'Bei Antwort Mail';
}

function antwortmail_beschreibung ()
{
  echo 'Konfigurationsmodul das die Funktion bei Antwort auf einen Beitrag eine Mail an Autor zu schicken erm&ouml;glicht';
}

function antwortmail_wartung ()
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

  include ('../konf/konf.php');
  if ($B_version != 0 or $B_subversion != 4)
    die ('Das Modul zum Hinzufuegen der Antwort-Mail Funktion ben&ouml;tigt ein Forum Version 0.4');
  
  include_once ('../../gemeinsam/benutzer-daten.php');

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ('Zugriff verweigert');
  if (!$B_wartung_start)
    die ('Um dieses Skript laufen zu lassen muss das Forum gesperrt sein');

  mysql_query ("ALTER TABLE `Beitraege`
                ADD `AntwortMail` CHAR( 1 ) DEFAULT 'n' NOT NULL AFTER `AutorLetzter`")
   or die ('<p>Das Felde AntwortMail konnte nicht angelegt werden<p>' . mysql_error());
 
  mysql_query ("UPDATE Konf
                SET WertInt = '5'
                WHERE Schluessel = 'B_subversion'")
    or die ("Die Forumversion konnte nicht aktuallisiert werden");
                                    
  echo '<h2>Das Datenbanfelde AntwortMail wurde angelegt.</h2>
        Spiele jetzt erst die neuen Dateiversionen ein.<p>';
 
  $pfad = $_SERVER['DOCUMENT_ROOT'] . '/forum/wartung/wartung.php';
  echo 'Anschlie&szlig;end geht es hier ';
}
?>
