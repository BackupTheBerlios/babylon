<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $K_Egl = FALSE;
  $K_EMail = "";
  $K_Alias = "";
  $K_VName = "";
  $K_NName = "";

  include_once ("../gemeinsam/db-verbinden.php");
  include_once ("../gemeinsam/benutzer-daten.php");
#  include ("./leiste-oben.php");
  include ("./benutzer-eingaben.php");
  
  $db = db_verbinden ();    
  benutzer_daten_persoenlich ($BenutzerId, $K_Egl, $K_Stil,
                              $K_EMail, $K_Alias,
			      $K_VName, $K_NName);

  if (!$K_Egl)
    die ("Zugriff verweigert");

  if (benutzer_eingabe_test ("benutzer", "Alias", 3, 32, "Der Benutzername ist zu kurz (min. 3 Zeichen)", true))
    include("benutzer-konf4.php");
  else if (benutzer_eingabe_test ("passwort1", "Passwort 1", 6, 32, "Das Passwort ist zu kurz (min. 6 Zeichen)", true))
    include("benutzer-konf4.php");
  else if (benutzer_eingabe_test ("passwort2", "Passwort 2", 6, 32, "Das Passwort ist zu kurz (min. 6 Zeichen)", true))
    include("benutzer-konf4.php");
  else if (benutzer_eingabe_test ("vname", "Vorname", 2, 32, "Kompletten Vornamen angeben", true))
    include("benutzer-konf4.php");
  else if (benutzer_eingabe_test ("nname", "Nachname", 2, 32, "Kompletten Nachnamen angeben", true))
    include("benutzer-konf4.php");
  else if (strcmp ($_POST[passwort1], $_POST[passwort2]))
  {
    echo "<h2>Die Passw&ouml;rter stimmen nicht &uuml;berein</h2><p>";
    include("benutzer-konf4.php");
  }
  else
  {
  /* Das sollte man alles nochmals in einem UPDATE zusammen fassen ... */

  /* momentan ist die alias-Aenderung verboten */
/*    if (strlen ($_POST[benutzer]) and strcmp ($_POST[benutzer], $K_Alias))
    {
      $benutzer = $_POST[benutzer];
      mysql_query ("UPDATE Benutzer
                    SET Benutzer=\"$benutzer\"
                    WHERE BenutzerId=\"$BenutzerId\"")
        or die ("Der Alias konnte nicht aktuallisiert werden.");
    }*/
    if (strlen ($_POST[passwort1]))
    {
      $passwort = md5 ($_POST[passwort1]);
       mysql_query ("UPDATE Benutzer
                    SET Passwort=\"$passwort\"
                    WHERE BenutzerId=\"$BenutzerId\"")
        or die ("Das Passwort konnte nicht aktuallisiert werden.");
    }     
    if (strlen ($_POST[email]) and strcmp ($_POST[email], $K_EMail))
    {
      $email = addslashes ($_POST[email]);
      mysql_query ("UPDATE Benutzer
                    SET EMail=\"$email\"
                    WHERE BenutzerId=\"$BenutzerId\"")
        or die ("Die E-Post Adresse konnte nicht aktuallisiert werden.");
    }   
    if (strlen ($_POST[vname]) and strcmp ($_POST[vname], $K_VName))
    {
      $vname = addslashes ($_POST[vname]);
      mysql_query ("UPDATE Benutzer
                    SET VName=\"$vname\"
                    WHERE BenutzerId=\"$BenutzerId\"")
        or die ("Der Vorname konnte nicht aktuallisiert werden.");
    }   
    if (strlen ($_POST[nname]) and strcmp ($_POST[nname], $K_NName))
    {
      $nname = addslashes ($_POST[nname]);
      mysql_query ("UPDATE Benutzer
                    SET NName=\"$nname\"
                    WHERE BenutzerId=\"$BenutzerId\"")
        or die ("Der Nachname konnte nicht aktuallisiert werden.");
    }
    $zu = isset ($_POST[speichern]) ? "benutzer-konf4" : "foren";
    include ("gehe-zu.php");
  }
?>
