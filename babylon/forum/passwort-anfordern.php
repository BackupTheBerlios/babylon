<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  include ("../gemeinsam/db-verbinden.php");
  $db = db_verbinden ();
  include ("konf/konf.php");

  $email = addslashes ($_POST[email]);
  $benutzer = addslashes ($_POST[benutzer]);

  $erg = mysql_query ("SELECT BenutzerId
                       FROM Benutzer
                       WHERE Email=\"$email\" AND Benutzer=\"$benutzer\"")
    or die ("Benutzerdaten konnten nicht aus der Datenbank abgerufen werden");

  if (mysql_num_rows ($erg) == 0)
  {
    echo "<h2>Der Benutzername oder die E-Mail Adresse exestiert nicht!</h2><p>";
    include ("login.php");
  }
  else
  {
    $neues_passwort = md5 (rand () . microtime ());
    $passwort_db = md5 ($neues_passwort);
    $stempel = time ();
    $zeile = mysql_fetch_row ($erg);

    mysql_query ("UPDATE Benutzer
                  SET PassTmp=\"$passwort_db\", PassTmpStempel=\"$stempel\"
                  WHERE BenutzerId=\"$zeile[0]\"")
      or die ("Informationen zum tempor&auml;ren Passwort konten nicht gespeichert werden");
    mysql_close ($db);
    
    $nachricht = "Dies ist das $B_betreiber Forum\n
Jemand, wahrscheinlich Du hat bantragt, dass Dein Passwort geaendert
wird. Hierzu wurde vom Forum folgendes neues Passwort fuer dich
generiert:\n
\t" . $neues_passwort .
"\n\nUm dieses Passwort zu aktivieren begib dich bitte innerhalb der kommenden
24 Stunden in das $B_betreiber Forum und logge Dich unter diesem Passwort ein. Da
aus Sicherheitsgruenden dieses Passwort maximal 24 Stunden gueltig ist
wirst Du dort dazu aufgefordert dir ein neues Passwort zuzulegen.\n
Solltest Du kein neues Passwort beantragt haben versucht evtl. jemand Deinen
Zugang zu komprometieren. Bitte wende dich in diesem Fall an den Seitenmeister
dieser Seiten.";

    $kopf = "From:$B_betreiber Forum<$B_mail_absender>\n";
    $kopf .= "X-Mailer:Babylon";
    mail($_POST[email], "Dein $B_betreiber Forum Zugang",$nachricht, $kopf);

    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<html>
<head>
  <link href=\"/forum/stil/std.css\" rel=\"stylesheet\" type=\"text/css\">
  <title>$B_betreiber</title>
</head>
<body>
  <h2>Passwort Versand</h2>
  Ein neues Passwort wurde an $_POST[email] versandt!<p>

  Bitte aktiviere es innerhalb der kommenden 24 Stunden indem Du dich mit diesem Passwort einloggst.<p>

  <a href=\"foren.php\">Zur&uuml;ck zu den Foren</a>
</body>
</html>";
  }
;?>

