<?PHP;
/* Copyright 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

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

  include_once ('../../../gemeinsam/db-verbinden.php');
  include_once ('../../../gemeinsam/benutzer-daten.php');
  include ('../../konf/konf.php');
  include_once ('../../fehler.php');

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ('Zugriff verweigert');

  if (!$B_wartung_start)
    die ('Um dieses Skript laufen zu lassen muss das Forum &uuml;ber das setzen der
          $B_wartung_start Variable in /forum/konf/konf.php gesperrt sein');

  if ($B_version != 0 or $B_subversion != 9)
    die ('Das Modul "RSS-Variablen eintragen" darf nur mit einem Forum der Version 0.9x
          verwandt werden');

  if ((!isset ($_POST['titel'])) or (isset($_POST['titel']) and (!strlen (trim ($_POST['titel'])))))
    $titel = $B_betreiber . '-Forum';
  else
    $titel = trim ($_POST['titel']);
  $titel = addslashes ($titel);

  if ((!isset ($_POST['beschreibung'])) or (isset($_POST['beschreibung']) and (!strlen (trim ($_POST['beschreibung'])))))
    $inhalt = 'Neue Themen und Beitr&auml;ge des ' . $B_betreiber . '-Forums';
  else
    $inhalt = trim ($_POST['beschreibung']);
  $inhalt = addslashes ($inhalt);

  $seite = 'http://' . $_SERVER['SERVER_NAME'] . '/forum/foren.php';

  mysql_query ("INSERT
                INTO Rss (Titel, Autor, Link, Inhalt, Stempel)
                VALUES ('$titel', '', '$seite', '$inhalt', 0)")
    or fehler (__FILE__, __LINE__, 0, 'die Tabelle f&uuml;r die Rss-Funktionalit&auml;t konnte nicht mit dem Tabellenkopf gef&uuml;llt werden.');

  for ($x = 1; $x < 17; $x++)
    mysql_query ("INSERT
                INTO Rss (Titel, Autor, Link, Inhalt, Stempel)
                VALUES ('', '', '', '', $x)")
    or fehler (__FILE__, __LINE__, 0, 'die Tabelle f&uuml;r die Rss-Funktionalit&auml;t konnte nicht mit dem Tabellenkopf gef&uuml;llt werden.');


  echo '<h2>Die RSS-Daten wurden erfolgreich gespeichert.</h2>';
  
  echo '<a href="../wartung.php">Zur&uuml;ck zur Wartungshauptseite</a>
    </body>
  </html>';
?>
