<?php
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function antwort_mail ($B_betreiber, $B_mail_absender, $absender, $empfaenger, $titel, $text)
{
  $erg = mysql_query ("SELECT VName, NName, EMail
                       FROM Benutzer
                       WHERE Benutzer = \"$empfaenger\"")
    or die ('Der Empf&auml;nger f&uuml;r die "Bei Antwort-Mail"-Funktion konnte nicht ermittelt werden<br>'
            . mysql_error ());
  if (mysql_num_rows ($erg) != 1)
    die ('Interner Fehler: Es sind mehrere Benutzer mit dem selben Alias vorhanden!');
  $zeile = mysql_fetch_row ($erg);

  $vname = $zeile[0];
  $nname = $zeile[1];
  $email = $zeile[2];

  $mail_text = strip_tags ($text);

  $nachricht = "Dies ist das $B_betreiber Forum

$absender hat auf Deinen Beitrag
$titel
geantwortet:
-------------------------------------------------------------------------

$mail_text

-------------------------------------------------------------------------

 Du erhaelst diese Mitteilung, da Du um Zusendung von Antworten auf
 Deinen Beitrag im $B_betreiber Forum gebeten hast.Bitte antworte nicht
 an die Absendeadresse da es sich um eine automatisch vom System
 generierte Nachricht handelt.";

      $kopf = "From:$B_betreiber Forum<$B_mail_absender>\n";
      $kopf .= 'X-Mailer:Babylon';
      mail("$vname $nname <$email>", "Antwort auf Deinen Beitrag im $B_betreiber Forum", $nachricht, $kopf);
}
;?>
