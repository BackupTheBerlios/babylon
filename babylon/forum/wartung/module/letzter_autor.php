<?PHP;
function letzter_autor_beschreibung ()
{
  echo "Durchsucht alle Beitr&auml;ge und setzt f&uuml;r jedes Thema das Feld
 AutorLetzter auf den Wert des Autors der den letzten Beitrag verfasst hat.<p>

 Dies ist in der Regel nur beim Umstieg von einer alten Version notwendig.<p>

 Ein wiederholter Aufruf verursacht allerdings auch keine Sch&auml;den";
}

// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $K_Egl = FALSE;
  $K_Lesen = 0;
  $K_Schreiben = 0;
  $K_Admin = 0;
  $K_AdminForen = 0;
  $K_Stil = "std";
  $K_Signatur ="";
  $K_SprungSpeichern = 0;
  $K_BaumZeigen = 'j';

  include ("../../gemeinsam/db-verbinden.php");
  include ("../../gemeinsam/benutzer-daten.php");
  include_once ("../konf/konf.php");

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ("Zugriff verweigert");

  if (!$B_wartung_start)
    die ('Um dieses Skript laufen zu lassen muss das Forum &uuml;ber das setzen der
 $B_wartung_start Variable in /forum/konf/konf.php gesperrt sein');

  $tids = mysql_query ("SELECT ThemaId
                        FROM Beitraege
                        WHERE BeitragTyp = 2")
    or die ("Die Themen konnten nicht ermittelt werden");
  $themen = mysql_num_rows ($tids);

  echo "Es wurden $themen Themen ermittelt<p>";

  
  while ($zeile = mysql_fetch_row ($tids))
  {
    $letzter = mysql_query ("SELECT BeitragId, Autor
                             FROM Beitraege
                             WHERE BeitragTyp & 8 = 8 AND ThemaId = $zeile[0]
                             ORDER BY BeitragId DESC
                             LIMIT 1")
       or die ("Die letzten Beitraege konnten nicht ermittelt werden");

    $bid = mysql_fetch_row ($letzter);

    echo "BeitragId $bid[0]; Autor $bid[1] ";

    mysql_query ("UPDATE Beitraege
                  SET AutorLetzter = \"$bid[1]\"
                  WHERE BeitragTyp = 2 AND ThemaId = $zeile[0]")
      or die ("<p>Autor konnte nicht akualisiert werden");
  
    echo "gesetzt.<br>";
  }
?>
