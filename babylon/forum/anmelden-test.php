<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

include ("./benutzer-eingaben.php");
include ("../gemeinsam/db-verbinden.php");

if (benutzer_eingabe_test ("benutzer", "Alias", 3, 32, "Der Benutzername ist zu kurz (min. 3 Zeichen)", false))
  include("anmelden.php");
else if (benutzer_eingabe_test ("passwort1", "Passwort 1", 6, 32, "Das Passwort ist zu kurz (min. 6 Zeichen)", false))
  include("anmelden.php");
else if (benutzer_eingabe_test ("passwort2", "Passwort 2", 6, 32, "Das Passwort ist zu kurz (min. 6 Zeichen)", false))
  include("anmelden.php");
else if (benutzer_eingabe_test ("vname", "Vorname", 2, 32, "Kompletten Vornamen angeben", false))
  include("anmelden.php");
else if (benutzer_eingabe_test ("nname", "Nachname", 2, 32, "Kompletten Nachnamen angeben", false))
  include("anmelden.php");
else if (strcmp ($_POST[passwort1], $_POST[passwort2]))
  {
    echo "<h2>Die Passw&ouml;rter stimmen nicht &uuml;berein</h2><p>";
    include("/forum/anmelden.php");
  }
// FIXME hier muss noch der email test kommen  
  else
  {
#    $db = mysql_connect ("localhost", "det", "zottel")
#      or die ("<b>F0032: Es konnte keine Verbindung zur Datenbank hergestellt werden</b>");
#    mysql_select_db ("pflug_forum")
#      or die ("Datenbank konnte nicht ausgew&auml;hlt werden");
    $db = db_verbinden();
    $benutzer = addslashes ($_POST[benutzer]);
    $vname = addslashes ($_POST[vname]);
    $nname = addslashes ($_POST[nname]);
    $email = addslashes ($_POST[email]);
    
    $erg = mysql_query ("SELECT Benutzer FROM Benutzer WHERE Benutzer = \"$benutzer\"")
             or die ("F0033: Benutzerdaten konnten nicht abgeglichen werden");
    if (mysql_num_rows ($erg) > 0)
    {
      mysql_close ($db);
      echo "<h2>Benutzername \"$_POST[benutzer]\" ist bereits vergeben</h2><p>";
      include("anmelden.php");
    }
    else
    {
      $pass = md5($_POST[passwort1]);
      $stempel = time ();
      
      mysql_query ("INSERT INTO Benutzer (Benutzer, VName, NName, Passwort, EMail, Anmeldung)
                    VALUES (\"$benutzer\", \"$vname\", \"$nname\", \"$pass\", \"$email\", \"$stempel\")")
        or die ("F0034: Benutzerdatensatz konnte nicht angelegt werden.");
      mysql_close ($db);

      include("gz-foren.php");
    }
  }
;?>
