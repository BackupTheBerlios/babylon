<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

// Standart Konfiguration. Man darf absolut nix ;-)
  $K_Admin = 0;
  $K_AdminForen = 0;

  include ('konf/konf.php');
  include ('../gemeinsam/benutzer-daten.php');

  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen,  $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ('Du hast keine Zugriffsrechte auf diese Seite');


  $erg = mysql_query ('SELECT BeitragId
                       FROM Beitraege');
  if (mysql_num_rows ($erg))
    die ('<h1>Es befinden sich bereits Beitrage im Forum</h1><p>Diese Funktion dient der Erstellung des Forumgrundger&uuml;sts und darf nur auf einem leeren Forum angewandt werden.');

  if ($B_version == 0 and $B_subversion == 1)
  {
    for ($x = 0; $x < 32; $x++)
      mysql_query ("INSERT INTO Beitraege VALUES (1, $x, 0, 0, $x+1, 0, 0, 'j', 'forum $x', '', '', 0, 0, 0, 'n', 'forum');")
        or die ('Das Forumger&uuml;st konnte nicht erstellt werden');
  }
  else
    die ('F&uuml;r die bestehende Forumsversion kann dieses Modul nicht angewandt werden');

  die ('Das Forenger&uuml;st wurden erstellt');
