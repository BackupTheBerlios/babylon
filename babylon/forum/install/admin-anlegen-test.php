<?PHP;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

include ('../benutzer-eingaben.php');
include ('../../gemeinsam/db-verbinden.php');

if (benutzer_eingabe_test ('benutzer', 'Alias', 3, 32, 'Der Benutzername ist zu kurz (min. 3 Zeichen)', false))
  include ('admin-anlegen.php');
else if (benutzer_eingabe_test ('passwort1', 'Passwort 1', 6, 32, 'Das Passwort ist zu kurz (min. 6 Zeichen)', false))
  include ('admin-anlegen.php');
else if (benutzer_eingabe_test ('passwort2', 'Passwort 2', 6, 32, 'Das Passwort ist zu kurz (min. 6 Zeichen)', false))
  include ('admin-anlegen.php');
else if (benutzer_eingabe_test ('vname', 'Vorname', 2, 32, 'Kompletten Vornamen angeben', false))
  include ('admin-anlegen.php');
else if (benutzer_eingabe_test ('nname', 'Nachname', 2, 32, 'Kompletten Nachnamen angeben', false))
  include ('admin-anlegen.php');
else if (strcmp ($_POST['passwort1'], $_POST['passwort2']))
{
  echo '<h2>Die Passw&ouml;rter stimmen nicht &uuml;berein</h2><p>';
  include ('admin-anlegen.php');
}
else if (!email_adresse_gueltig ($_POST['email']))
{
  echo '<h2>G&uuml;ltige E-Mail Adresse angeben</h2>';
  include ('admin-anlegen.php');
}
else
{
  $db = db_verbinden();
  $benutzer = addslashes ($_POST['benutzer']);
  $vname = addslashes ($_POST['vname']);
  $nname = addslashes ($_POST['nname']);
  $email = addslashes ($_POST['email']);
    
  $erg = mysql_query ("SELECT Benutzer
                       FROM Benutzer")
           or die ('Es konnte nicht ermittelt werden ob schon Benutzer bestehen.');
  if (mysql_num_rows ($erg) > 0)
  {
    mysql_close ($db);
    die ('Es exestieren schon Benutzer in der Datenbank. Abbruch.');
  }
  else
  {
    $pass = md5($_POST['passwort1']);
    $stempel = time ();
    $sw =  md5 (rand () . microtime ());
   
    mysql_query ("INSERT INTO Benutzer (Benutzer, VName, NName, Cookie, Eingeloggt, Passwort,
                                        EMail, Anmeldung, Gruppe)
                  VALUES (\"$benutzer\", \"$vname\", \"$nname\", \"$sw\", \"j\",
                          \"$pass\", \"$email\", \"$stempel\",
                          \"Admin\")")
      or die ("Forenadministrator konnte nicht angelegt werden.");
 
    $erg = mysql_query ("SELECT BenutzerId FROM Benutzer
                       WHERE Benutzer=\"$benutzer\"")
    or die ('BenutzerID des Forenadministrators konnten nicht aus der Datenbank abgerufen werden');
  
    $zeile = mysql_fetch_row ($erg);
    $id = $zeile[0];
   
    if (! setcookie('babylon_id', $id, time() + 86400, '/'))
      die ('Cookie konnte nicht gesetzt werden');
    setcookie('babylon_sw', $sw, time() + 86400, '/');

    mysql_close ($db);
 
    echo '<h2>Forenadmistrator angelegt</h2>

    Der Zugang f&uuml;r den Forenadministrator wurde erfolgreich angelegt.<p>
 
    <form action="konfiguration.php" method="post">
            <button >Weiter</button>
          </form>';
  }
}
;?>
