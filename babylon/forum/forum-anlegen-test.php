<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

// Standart Konfiguration. Man darf absolut nix ;-)
  $K_Admin = 0;
  $K_AdminForen = 0;

  include ('../gemeinsam/db-verbinden.php');
  include ('../gemeinsam/benutzer-daten.php');

  $db = db_verbinden ();
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ('Du hast keine Zugriffsrechte auf diese Seite');
  if (!isset ($_POST['forum_id']))
    die ('Du musst das anzulegende Forum auswaehlen');

  if ((!strlen ($_POST['titel'])) or (!strlen ($_POST['inhalt'])))
    die ('Forumtitel und Forumbeschreibung m&uuml;ssen gesetzt sein');
    
  $stempel = time ();
  $forum_id = intval ($_POST['forum_id']);
  $titel = addslashes ($_POST['titel']);
  $inhalt = addslashes ($_POST['inhalt']);
 
  if ($forum_id < 0 or $forum_id > 30)
    die ('Illegaler Forumindex')
 
  mysql_query ("UPDATE Beitraege
                SET Gesperrt='n', Titel=\"$titel\", Inhalt=\"$inhalt\"
                WHERE BeitragTyp = '1' AND ForumId=\"$forum_id\"")
    or die ('F0044: Forum konnte nicht angelegt werden');

  mysql_close ($db);
  include('gz-foren.php');
?>      
