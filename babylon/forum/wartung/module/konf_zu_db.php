<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function konf_zu_db_titel ()
{
  echo "Konf zu Datenbank";
}

function konf_zu_db_beschreibung ()
{
  echo "&Uuml;bertr&auml;gt die Konfiguration der Datei forum/konf/konf.php
  in eine neue Tabelle der Datenbank.";
}

function konf_zu_db_wartung ()
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

  include_once ("../../gemeinsam/db-verbinden.php");
  include_once ("../../gemeinsam/benutzer-daten.php");
  include ("../konf/konf.php");

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ("Zugriff verweigert");

  if (!$B_wartung_start)
    die ('Um dieses Skript laufen zu lassen muss das Forum &uuml;ber das setzen der
          $B_wartung_start Variable in /forum/konf/konf.php gesperrt sein');

  if ($B_version != 0 or $B_subversion != 1)
    die ('Das Modul "Konf zu Datenbank" darf nur mit einem Forum der Version 0.1
          verwandt werden');

  mysql_query ("CREATE TABLE `Konf` (
                  `KonfId` INT (1) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `Schluessel` VARCHAR (255) DEFAULT NULL,
                  `Gruppe` INT (1) UNSIGNED DEFAULT '0',
                  `WertInt` INT (1) UNSIGNED DEFAULT '0',
                  `WertText` TEXT DEFAULT NULL,
                  `Typ` CHAR (1) DEFAULT 'i',
                  PRIMARY KEY (`KonfId`)
                  ) TYPE=MyISAM")
    or die ("Erstellung der Konf-Tabelle gescheitert");

  $fd = fopen ("../konf/konf.php", "r");

  while ($zeile = fgets ($fd, 1000))
  {
    if (!preg_match ('/^\$B_[a-zA-Z_]+.*/', $zeile))
      continue;
    $varname = chop (preg_replace ('/^\$(B_[a-zA-Z_]+).*/', '\\1', $zeile));
    $var = ${$varname};
    if (is_array ($var))
    {
      reset ($var);
      $stil = '';
      while ($tmp = current ($var))
      {
        $stil = $stil . ',' . $tmp;
        next ($var);
      } 
      mysql_query ("INSERT INTO Konf
                    (Schluessel, WertText, Typ)
                     VALUES (\"$varname\", \"$stil\", 'a')")
        or die ("Die Variable $varname konnte nicht in der Konfigruation angelegt werden"); 
     
    }
    else
    {
      if (is_numeric ($var) ?  intval(0+$var) == $var : false )
      {
        $typ = 'i';
        $typ_bezeichner = 'WertInt';
      }
      else if (is_bool ($var))
      {
        $typ = 'b';
        $typ_bezeichner = 'WertInt';
        $var = $var ? 1 : 0;
      }
       else if (is_float ($var))
      {
        $typ = 'f';
        $typ_bezeichner = 'WertInt';
      }     
      else if  (is_string ($var))
      {
        $typ = 't';
        $typ_bezeichner = 'WertText';
      }
      else
      {
        $typ = gettype ($varname);
        die ("Die Variable $varname der Konfiguration verwendet den nicht unterst&uuml;tzten
              Datentyp $typ");
      }

      mysql_query ("INSERT INTO Konf
                      (Schluessel, $typ_bezeichner, Typ)
                      VALUES (\"$varname\", \"$var\", \"$typ\")")
        or die ("Die Variable $varname konnte nicht in der Konfigruation angelegt werden");
    }
  }
  mysql_query ("UPDATE Konf
                SET WertInt = '2'
                WHERE Schluessel = 'B_subversion'")
   or die ("Die Forumversion konnte nicht aktuallisiert werden");

  echo "<h2>Die Konfiguration ist jetzt Datenbankbasiert.</h2>
        Spiele jetzt erst die neuen Dateiversionen im Verzeichniss forum/konf/ ein.<p>";
   
}
?>
