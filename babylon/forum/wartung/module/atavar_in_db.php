<?PHP;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function atavar_in_db_titel ()
{
  echo 'Atavar in DB';
}

function atavar_in_db_beschreibung ()
{
  echo 'Konfigurationsmodul das die Atavare in die Datenbank verschiebt';
}

function atavar_in_db_wartung ()
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
  if ($B_version != 0 or $B_subversion != 6)
    die ('Das Modul zum verschieben der Atavare in die Datenbank ben&ouml;tigt ein Forum Version 0.6');
  
  include_once ('../../gemeinsam/benutzer-daten.php');

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ('Zugriff verweigert');
  if (!$B_wartung_start)
    die ('Um dieses Skript laufen zu lassen muss das Forum gesperrt sein');

  mysql_query ("ALTER TABLE `Benutzer`
                ADD `AtavarData`
                MEDIUMBLOB AFTER `Atavar`")
   or die ('<p>Das Atavardatenfeld konnte nicht angelegt werden<p>' . mysql_error());

  $erg = mysql_query ("SELECT BenutzerId
                       FROM Benutzer
                       WHERE Atavar = 'j'");

  while ($zeile = mysql_fetch_row ($erg))
  {
    $atavar_datei = "../atavar/$zeile[0].jpg";
    $atavar_groesse = filesize($atavar_datei);
    $bild = addslashes(fread(fopen($atavar_datei, 'r'), $atavar_groesse));
    mysql_query ("UPDATE Benutzer
                  SET AtavarData = '$bild'
                  WHERE BenutzerId = '$zeile[0]'")
      or die ('Atavar konnte nicht in die Datenbank geschrieben werden.');
  }

  mysql_query ("UPDATE Konf
                SET WertInt = '7'
                WHERE Schluessel = 'B_subversion'")
    or die ("Die Forumversion konnte nicht aktuallisiert werden");
                                    
  echo '<h2>Die Atavare wurde in die Datenbank &uuml;berf&uuml;hrt.</h2>
        Spiele jetzt erst die neuen Dateiversionen ein.<p>
        Danach l&ouml;sche das <tt>atavar</tt>-Verzeichniss inkl. Inhalt.<p>';
 
  $pfad = $_SERVER['DOCUMENT_ROOT'] . '/forum/wartung/wartung.php';
  echo 'Anschlie&szlig;end geht es hier ';
}
?>
