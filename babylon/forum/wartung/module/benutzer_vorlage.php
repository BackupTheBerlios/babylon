<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function benutzer_vorlage_titel ()
{
  echo 'Benutzervorlage';
}

function benutzer_vorlage_beschreibung ()
{
  echo 'Erstellt das Grundger&uuml;st f&uuml;r Benutzervorlagen';
}

function benutzer_vorlage_wartung ()
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

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ('Zugriff verweigert');

  if (!$B_wartung_start)
    die ('Um dieses Skript laufen zu lassen muss das Forum &uuml;ber das setzen der
          $B_wartung_start Variable in /forum/konf/konf.php gesperrt sein');

  if ($B_version != 0 or $B_subversion != 7)
    die ('Das Modul "Benutzervorlagen" ben&ouml;tigt ein Forum der Version 0.7');

// Tabelle "BenutzerVorlage anlegen
  mysql_query ("CREATE TABLE `BenutzerVorlage` (
                  `VorlageId` INT (1) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `Name` VARCHAR (255) DEFAULT NULL,
                  `RechtLesen` INT (1) UNSIGNED NULL DEFAULT 4294967295,
                  `RechtSchreiben` INT (1) UNSIGNED NULL DEFAULT 4294967295,
                  `RechtAdmin` INT (1) UNSIGNED NULL DEFAULT 0,
                  `RechtAdminForen` INT (1) UNSIGNED NULL DEFAULT 0,
                  `RechtLinks` CHAR (1) DEFAULT 'n',
                  `RechtGrafik` CHAR (1) DEFAULT 'n',
                  PRIMARY KEY (`VorlageId`)
                  ) TYPE=MyISAM")
    or die ('Erstellung der BenutzerVorlage-Tabelle gescheitert<br>' . mysql_error ());

// Die Standart- und Admin-Vorlage anlegen
      mysql_query ("INSERT INTO BenutzerVorlage
                      (Name)
                      VALUES ('Standart')")
        or die ("Die Standartvorlage konnte nicht angelegt werden<br>" . mysql_error ());
      mysql_query ("INSERT INTO BenutzerVorlage
                      (Name, RechtAdmin, RechtAdminForen)
                      VALUES ('Admin', 4294967295, 1)")
        or die ("Die Administratorenvorlage konnte nicht angelegt werden<br>" . mysql_error ());

// Die Benutzergruppen in den Benutzern anlegen 
  mysql_query ("ALTER TABLE `Benutzer`
                ADD `Gruppe` VARCHAR (255)
                DEFAULT 'Standart' NOT NULL
                AFTER `RechtAdminForen`")
    or die ('Das Feld Gruppe konnte in der Tabelle Benutzer nicht angelegt werden<br>' . mysql_error ());

// Den aktuellen Benutzer zum Admin machen
  mysql_query ("UPDATE Benutzer
                SET Gruppe = 'Admin'
                WHERE BenutzerId = '$BenutzerId'")
    or die ('Es konnte kein Administrator angelegt werden');

// Wir loeschen die alte Rechteverwaltung
  $cols = array ('RechtLesen', 'RechtSchreiben', 'RechtAdmin', 'RechtAdminForen');
  while ($col = current ($cols))
  {
    mysql_query ("ALTER TABLE `Benutzer`
                  DROP $col")
      or die ('Die alte Rechteverwaltung konnte nicht geloescht werden<br>' . mysql_error ());
    next ($cols);
  }
  
  mysql_query ("UPDATE Konf
                SET WertInt = '8'
                WHERE Schluessel = 'B_subversion'")
   or die ("Die Forumversion konnte nicht aktuallisiert werden<br>" . mysql_error ());
   
  echo '<h2>Das Benutzervorlagensystem wurden installiert</h2>
        Eine Standart- und eine Administratoren-Benutzervorlage wurde installiert.
        Du wurdest in die Gruppe der Administratoren aufgenommen.<p>
        Mit Aufruf von <tt>/forum/benutzer-vorlage.php</tt> kannst Du die Vorlagen
        verwalten und Neue erstellen<p>
        Im Mitgliederprofil der einzelnen Forenteilnehmer kannst Du diese den
        verschiedenen Benutzergruppen zuordnen.<p>
        Spiele jeztzt die neuen Dateiversionen ein<p>';
 
  $pfad = $_SERVER['DOCUMENT_ROOT'] . '/forum/wartung/wartung.php';
  echo 'Anschlie&szlig;end geht es hier ';
}
?>
