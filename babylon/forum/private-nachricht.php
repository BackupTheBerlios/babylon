<?PHP;
/* Copyright 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

  include ('konf/konf.php');

// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $K_Egl = FALSE;
  $K_EMail = '';
  $K_Alias = '';
  $K_VName = '';
  $K_NName = '';

  include_once ('../gemeinsam/db-verbinden.php');
  include_once ('../gemeinsam/benutzer-daten.php');
  include ('benutzer-eingaben.php');
  
  $db = db_verbinden ();    
  benutzer_daten_persoenlich ($BenutzerId, $K_Egl, $K_Stil,
                              $K_EMail, $K_Alias,
			      $K_VName, $K_NName);

  if (!$K_Egl)
    die ('Zugriff verweigert');

  $text = strip_tags ($_POST['text']);
  if (!strlen ($text))
    die ('Die Nachricht muss einen Text enthalten');

  $empfaenger_id = intval ($_POST['Empfaenger']);
  $erg = mysql_query ("SELECT EMail, NachrichtAnnehmen, NachrichtAnnehmenAnonym, Benutzer
                       FROM Benutzer
                       WHERE BenutzerId=\"$empfaenger_id\"")
    or die ("Benutzerdaten des Empfaengers konnten nicht aus der Datenbank abgerufen werden");

  if (mysql_num_rows ($erg) == 0)
    die ('Illegale Empfaengererkennung');
  
  $zeile = mysql_fetch_row ($erg);
  mysql_close ($db);

  if ($zeile[1] != 'j')
    die ('Illegaler Zugriffsversuch! Der Empf&auml;nger hat der Zusendung privater Nachrichten
          nicht zugestimmt!');
  
  $empfaenger_email = $zeile[0];
  if (!email_adresse_gueltig ($empfaenger_email))
    die ('Nachricht wurde nicht zugestellt, da der Empf&auml;nger anscheinend keine g&uuml;ltige
          E-Mail Adresse hinterlegt hat.');

  if (isset ($_POST['Anonym']))
    $absender = $K_Alias;
  else
    $absender = "$K_Alias ($K_VName $K_NName <$K_EMail>)";

  $nachricht = "Dies ist das $B_betreiber Forum

$absender

laesst Dir folgend Nachricht zukommen:

-------------------------------------------------------------------------

$text

-------------------------------------------------------------------------

 Du erhaelst diese Nachricht, da Du der Zusendung privater Nachrichten
 in Deinen persoenlichen Einstellungen des $B_betreiber Forums zugestimmt
 hast. Bitte antworte nicht an die Absendeadresse da es sich um eine
 automatisch vom System generierte Nachricht handelt.";

  $kopf = "From:$B_betreiber Forum<$B_mail_absender>\n";
  if (! isset ($_POST['Anonym']))
    $kopf .= "Reply-To: $K_EMail\n";
  $kopf .= 'X-Mailer:Babylon';
  mail($empfaenger_email, "Private Nachricht von $K_Alias", $nachricht, $kopf);

  echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
<html>
<head>
  <link href=\"/forum/stil/std.css\" rel=\"stylesheet\" type=\"text/css\">
  <title>Nachricht Versand</title>
</head>
<body>
  <h2>Private Nachricht versandt</h2>
  Deine Private Nachricht an $zeile[3] wurde versandt.<p>

  <a href=\"foren.php\">Zur&uuml;ck zu den Foren</a>
</body>
</html>";
;?>

