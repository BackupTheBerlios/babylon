<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $Benutzer = "";
  $K_Egl = FALSE;
  $K_Lesen = 0;
  $K_Schreiben = 0;
  $K_Admin = 0;
  $K_AdminForen = 0;
  $K_ThemenJeSeite = 6;
  $K_BeitraegeJeSeite = 3;
  $K_Stil = "std";
  $K_Signatur ="";
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 0;

  include ("../gemeinsam/db-verbinden.php");
  include ("../gemeinsam/benutzer-daten.php");
  include ("beitrag-pharsen.php");
  

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_Egl)
    die ("Zugriff verweigert");
      

  // ##### Stil #####
  foreach ($B_stile as $stil)
  {
    if (strcmp ($stil, $_POST[stil]) == 0)
    {
      $STIL = $stil;
      break;
    }
  }
  if (!isset ($STIL))
    $STIL = "std";

  mysql_query ("UPDATE Benutzer
                SET KonfStil=\"$STIL\"
                WHERE BenutzerId=\"$BenutzerId\"")
    or die ("F0038: Die Benutzereinstellungen konnten nicht aktuallisiert werden.");
  mysql_close ($db);
  $zu = isset ($_POST[speichern]) ? "benutzer-konf2" : "foren";
  include ("gehe-zu.php");
?>
