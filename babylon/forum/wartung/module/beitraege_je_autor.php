<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function beitraege_je_autor_titel ()
{
  echo 'Beitr&auml;ge je Autor';
}

function beitraege_je_autor_beschreibung ()
{
  echo 'Aktualisiert die Anzahl Beitr&auml;ge die ein Autor verfasst hat
 Dies ist in der Regel nur beim Umstieg von einer alten Version notwendig.
 Ein wiederholter Aufruf verursacht allerdings auch keine Sch&auml;den';
}

function beitraege_je_autor_wartung ()
{
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

  include_once ('../../gemeinsam/db-verbinden.php');
  include_once ('../../gemeinsam/benutzer-daten.php');
  include ('../konf/konf.php');

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ('Zugriff verweigert');

  if (!$B_wartung_start)
    die ('Um dieses Skript laufen zu lassen muss das Forum &uuml;ber das setzen der
 $B_wartung_start Variable in /forum/konf/konf.php gesperrt sein');

  $benutzer = mysql_query ("SELECT Benutzer
                            FROM Benutzer")
    or die ('Die Benutzer konnten nicht ermittelt werden');
  $num = mysql_num_rows ($benutzer);

  echo "Es wurden $num Benutzer ermittelt<p>";

  
  while ($zeile = mysql_fetch_row ($benutzer))
  {
    $erg = mysql_query ("SELECT BeitragId
                         FROM Beitraege
                         WHERE BeitragTyp & 8 = 8 AND Autor = \"$zeile[0]\"")
       or die ('Die Anzahl der Beitraege konnten nicht ermittelt werden');

    $beitraege = mysql_num_rows ($erg);

    echo "Autor $zeile[0]; Beitr&auml;ge $beitraege ";

    mysql_query ("UPDATE Benutzer
                  SET Beitraege = \"$beitraege\"
                  WHERE Benutzer = \"$zeile[0]\"")
      or die ('<p>Beitragszahl konnte nicht akualisiert werden');
  
    echo 'gesetzt.<br>';
  }
}
?>
