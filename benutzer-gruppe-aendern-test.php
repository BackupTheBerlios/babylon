<?PHP;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $Benutzer = '';
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
  $K_BaumZeigen = 0;

  include ('../gemeinsam/db-verbinden.php');
  include ('../gemeinsam/benutzer-daten.php');

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ('Zugriff verweigert');

  if (isset ($_POST['alias']))
  {
    $zu_arg = 'alias=' . $_POST['alias'];
  }

  $benutzer_id = intval ($_POST['benutzerid']);
  $vid = intval ($_POST['vorlage']);

  $erg = mysql_query ("SELECT Name
                       FROM BenutzerVorlage
                       WHERE VorlageId = '$vid'")
    or die ('Die Benutzervorlage konnte nicht ermittelt werden.<br>' . mysql_error ());
  $zeile = mysql_fetch_row ($erg);

  mysql_query ("UPDATE Benutzer
                SET Gruppe = '$zeile[0]'
                WHERE BenutzerId = '$benutzer_id'")
    or die ('Die Benutzergruppe konnte nicht aktuallisiert werden<br>' . mysql_error ());


$zu = 'mitglieder-profil';
include ("gehe-zu.php");
?>
