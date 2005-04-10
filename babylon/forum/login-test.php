<?php
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

include ('konf/konf.php');
include ('./benutzer-eingaben.php');

function id_von_benutzer ($db, $benutzer)
{
  $erg = mysql_query ("SELECT BenutzerId FROM Benutzer
                       WHERE Benutzer=\"$benutzer\"")
    or die ('F0047: BenutzerID konnten nicht aus der Datenbank abgerufen werden');
  
  if (mysql_num_rows ($erg) > 1)
  {
    mysql_close ($db);
    die ('F0048: Interner Datenbankfehler bei der Anmeldung<br>bitte wende dich an den Seitenmeister');
  }
  if (mysql_num_rows ($erg) == 0)
    return -1;
  else
  {
    $zeile = mysql_fetch_row ($erg);
    return $zeile[0];
  }
}

function passwort_test ($benutzer, $passwort, $passtype)
{
  $pass = md5($passwort);
  $erg = mysql_query ("SELECT BenutzerId FROM Benutzer
                       WHERE Benutzer=\"$benutzer\" AND $passtype=\"$pass\"")
    or die ('F0047: Benutzerdaten konnten nicht aus der Datenbank abgerufen werden');
  
  if (mysql_num_rows ($erg) > 1)
  {
    mysql_close ($db);
    die ('F0048: Interner Datenbankfehler bei der Anmeldung<br>bitte wende dich an den Seitenmeister');
  }
  return mysql_num_rows ($erg);
}

function passwort_ok ($db, $benutzer)
{
  $sw =  md5 (rand () . microtime ());
  $id = id_von_benutzer ($db, $benutzer);
    
  global $B_cookie_id, $B_cookie_sw;

  if (! setcookie($B_cookie_id, $id, time() + 86400, '/'))
    die ('Cookie konnte nicht gesetzt werden');
  setcookie($B_cookie_sw, $sw, time() + 86400, '/');

  mysql_query ("UPDATE Benutzer
                SET Eingeloggt=\"j\", Cookie=\"$sw\"
                WHERE BenutzerId=\"$id\"")
    or die ('F0049: Es ist nicht m&ouml;glich die Datenbank informationen zu aktuallisieren');
  mysql_close ($db);

  include('gz-foren.php');
}


$benutzer = addslashes ($_POST['benutzer']);
$passwort = $_POST['passwort'];

if (benutzer_eingabe_test ('benutzer', 'Alias', 3, 32, '', false))
  include('login.php');
else if (benutzer_eingabe_test ('passwort', 'Passwort', 6, 32, '', false))
  include('login.php');
else if (passwort_test ($benutzer, $passwort, 'Passwort') == 1)
  passwort_ok ($db, $benutzer);
else if (passwort_test ($benutzer, $passwort, 'PassTmp') == 1)
{
  $stempel = time ();
  
  $erg = mysql_query ("SELECT PassTmpStempel FROM Benutzer
                       WHERE Benutzer=\"$benutzer\"")
    or die ('F0047: Benutzerdaten konnten nicht aus der Datenbank abgerufen werden');
  $zeile = mysql_fetch_row ($erg);
  if ($stempel - $zeile[0] > 86400)
  {    
    mysql_close ($db);
    echo '<h2>Der G&uuml;ltigkeitszeitraum des Passworts ist abgelaufen.</h2>';
    include ('login.php');
  }
  else
    passwort_ok ($db, $benutzer);
}
else
{
  mysql_close ($db);
  echo "<h2>Benutzer \"$benutzer\" nicht bekannt!<br>Oder falsches Passwort</h2><p>";
  include ('login.php');
}
?>
