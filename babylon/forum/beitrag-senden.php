<?PHP;
/* Copyright 2003, 2004, 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

function interner_fehler ($db)
{
  echo '<h2>F001: Entweder bist Du nicht eingeloggt, dann d&uuml;rftest Du aber nicht
        bis hier hin gekommen sein, oder wir haben ein Systemproblem....</h2><p>
        Bitte gehe &uuml;ber die "Zur&uuml;ck"-Schaltfl&auml;che deines Browers
        auf die Beitragsseite und logge dich von dort aus nochmals ein. Falls
        es dann immer noch Probleme geben sollte wende Dich bitte an den
        Seitenmeister. Vor dem Einloggen solltest Du deinen Beitrag mit cut&amp;
        paste kopieren, da er beim einloggen gel&ouml;scht wird.';
  mysql_close_db ($db);
  die ();
}

include_once ('fehler.php');

// Standart Konfiguration. Man darf absolut nix ;-)
$BenutzerId = -1;
$K_Egl = FALSE;
$K_Lesen = 0;
$K_Schreiben = 0;
$K_Admin = 0;
$K_AdminForen = 0;
$K_ThemenJeSeite = 6;
$K_BeitraegeJeSeite = 3;
$K_Stil = 'std';
$K_Signatur = '';
$K_SprungSpeichern = 0;
$K_BaumZeigen = 'j';

$fid = 0;
$tid = 0;
$sid = 0;
$bid = 0;
$zid = 0;
$neu = 0;
include ('get-post.php');

if (id_von_get_post ($fid, $tid, $sid, $bid, $zid, $neu))
  fehler (NULL, 0, 1, 'Illegaler Zugriffsversuch!');

include ('beitrag-pharsen.php');
include ('../gemeinsam/db-verbinden.php');
include ('../gemeinsam/benutzer-daten.php');

$db = db_verbinden ();
benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                      $K_AdminForen,  $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                      $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

$K_Grafik = 'n';
$K_Links = 'n';
benutzer_daten_recht ($BenutzerId, $K_Grafik, $K_Links);
$erlaubte_tags = $K_Grafik == 'j' ? '<img>' : '';
$erlaubte_tags .= $K_Links == 'j' ? '<a>' : '';

if (isset ($_POST['eltern']))
  $e_tmp = $_POST['eltern'];
else if (isset ($_GET['eltern']))
   $e_tmp = $_GET['eltern'];
if (is_int (intval ($e_tmp)))
  $eltern = $e_tmp;
else
  fehler (__FILE__, __LINE__, 1, '&UUml;bergebenen Daten sind ung&uuml;ltig');

if ((isset ($_POST['vorschau']) and $_POST['vorschau'] == 'j') or (isset ($_POST['codearea']) and strlen ($_POST['codearea'])))
{
  include ('vorschau.php');
  vorschau ($K_Egl, $BenutzerId, $fid, $tid, $sid, $bid, $zid, $neu, '');
}

if (isset ($_POST['zid']))
{
  $zid = $_POST['zid'];
  include ('gz-beitraege.php');
}

if ($K_Egl)
{
  $stempel = time ();
  // allen Muell raus
  $text = addslashes (beitrag_pharsen (trim ($_POST['text']), $erlaubte_tags));
  if (!strlen ($text))
  {
    include ('vorschau.php');
    vorschau ($K_Egl, $BenutzerId, $fid, $tid, $sid, $bid, $zid, $neu, 'Bitte den Beitrag ausf&uuml;llen');
  }
  

// VIEL ZU VIEL DATENBANKZUGRIFFE.....

  if ($neu)
  {
    // Thema anlegen
    $titel = addslashes (substr (strip_tags (trim ($_POST['titel'])), 0, 50));
    if (!strlen ($titel))
    {
      include ('vorschau.php');
      vorschau ($K_Egl, $BenutzerId, $fid, $tid, $sid, $bid, $zid, $neu, 'Bitte einen Titel angeben');
    }
    
    mysql_query ("INSERT INTO Beitraege (BeitragTyp, ForumId, Titel,
                                         NumBeitraege, Autor, StempelStart,
                                         StempelLetzter)
                                 VALUES ('2', '$fid', '$titel',
                                         '0', '$Benutzer', '$stempel',
                                         '$stempel')")
      or fehler (__FILE__, __LINE__, 0, 'Thema konnte nicht angelegt werden');
    $tid = mysql_insert_id ();
    mysql_query ("UPDATE Beitraege
                  SET ThemaId = $tid
                  WHERE BeitragId = $tid")
      or fehler (__FILE__, __LINE__, 0, 'ThemenId konnte nicht aktuallisiert werden');
    // Strang  mit Beitrag anlegen
    $antwort_mail = isset ($_POST['antwort_mail']) ? 'j' : 'n';
    
    mysql_query ("INSERT INTO Beitraege (BeitragTyp, ForumId, ThemaId,
                                         NumBeitraege, Titel, Autor,
                                         AntwortMail, StempelStart, StempelLetzter,
                                         Inhalt)
                                 VALUES ('12', '$fid', '$tid',
                                         '1', '$titel', '$Benutzer',
                                         '$antwort_mail', '$stempel', '$stempel',
                                         '$text')")
      or fehler (__FILE__, __LINE__, 0, 'Strang konnte nicht angelegt werden');
    $sid = mysql_insert_id ();
    mysql_query ("UPDATE Beitraege
                  SET StrangId = $sid
                  WHERE BeitragId = $sid")
      or fehler (__FILE__, __LINE__, 0, 'Strangzaehler konnte nicht aktuallisiert werden');
    mysql_query ("UPDATE Beitraege
                  SET NumBeitraege=NumBeitraege+1
                  WHERE BeitragTyp = 1
                    AND ForumId = '$fid'")
      or fehler (__FILE__, __LINE__, 0, 'Forum Beitragsz&auml;hler konnten nicht aktualisiert werden');
    mysql_query ("UPDATE Benutzer
                  SET Themen = Themen+1
                  WHERE BenutzerId = '$BenutzerId'")
    or fehler (__FILE__, __LINE__, 0, 'Themenzaehler konnte nicht aktuallisiert werden');
      
  }
    // und hier einen neuen Beitrag
  else if (isset ($eltern))
  {
    $erg = mysql_query ("SELECT WeitereKinder, Titel, ForumId, ThemaId, StrangId, Autor, AntwortMail
                         FROM Beitraege
                         WHERE BeitragId = $eltern")
      or fehler (__FILE__, __LINE__, 0, 'Elterndatensatz konnte nicht bestimmt werden');
    $zeile = mysql_fetch_row ($erg)
      or fehler (__FILE__, __LINE__, 0, 'Elterndatensatz konnte nicht ermittelt werden');
   // wenn wir in der "nicht Strang" Ansicht sind bekommen wir keine reale sid
    $fid = $zeile[2];
    $tid = $zeile[3];
    $sid = $zeile[4];
    $autor = $zeile[5];
    $titel = addslashes ($zeile[1]);
    $antwort_verschicken = $zeile[6];
    $ret = strpos ($zeile[1], 'Re: ');
    if ($ret === FALSE or $ret > 0)
      $titel = 'Re: ' . $titel;
    else
      $titel = $titel;
    
    $antwort_mail = isset ($_POST['antwort_mail']) ? 'j' : 'n';
    
    // fuer diesen Beitrag gab es nocht keine Antwort
    if ($zeile[0] == 'n')
    {
      mysql_query ("INSERT INTO Beitraege (BeitragTyp, ForumId, ThemaId,
                                           StrangId, Autor, AntwortMail,
                                           Titel, StempelStart, StempelLetzter,
                                           Eltern, Inhalt)
                                   VALUES ('8', '$fid', '$tid',
                                           '$sid', '$Benutzer', '$antwort_mail',
                                           '$titel', '$stempel', '$stempel',
                                           '$eltern', '$text')")
        or fehler (__FILE__, __LINE__, 0, 'Beitrag konnte nicht angelegt werden');
      $bid = mysql_insert_id ();
      mysql_query ("UPDATE Beitraege
                    SET NumBeitraege=NumBeitraege+1
                    WHERE StrangId = '$sid'
                      AND BeitragTyp & 4 = 4")
        or fehler (__FILE__, __LINE__, 0, 'Z&auml;hler konnten nicht aktualisiert werden');
      mysql_query ("UPDATE Beitraege
                    SET WeitereKinder = 'v'
                    WHERE BeitragId = '$eltern'")
        or fehler (__FILE__, __LINE__, 0, 'Z&auml;hler konnten nicht aktualisiert werden');
    }
    // es exestier bereits ein kind zu dem Eltern Satz
    else
    {
      mysql_query ("INSERT INTO Beitraege (BeitragTyp, ForumId, ThemaId,
                                           NumBeitraege, Autor, AntwortMail,
                                           Titel, StempelStart, StempelLetzter,
                                           Eltern, Inhalt)
                                   VALUES ('12', '$fid', '$tid',
                                           '1', '$Benutzer', '$antwort_mail',
                                           '$titel', '$stempel', '$stempel',
                                           '$eltern', '$text')")
        or fehler (__FILE__, __LINE__, 0, 'Beitrag konnte nicht angelegt werden');
      $bid = mysql_insert_id ();
       mysql_query ("UPDATE Beitraege
                    SET StrangId = '$bid'
                    WHERE BeitragId = '$bid'")
        or fehler (__FILE__, __LINE__, 0, 'Z&auml;hler konnten nicht aktualisiert werden');
      mysql_query ("UPDATE Beitraege
                    SET WeitereKinder = 'j'
                    WHERE BeitragId = '$eltern'")
        or fehler (__FILE__, __LINE__, 0, 'Z&auml;hler konnten nicht aktualisiert werden');
    }

    // Bei Antowort Mail Funktion
    if ($antwort_verschicken == 'j')
    {
      include ('antwort-mail.php');
      antwort_mail ($B_betreiber, $B_mail_absender, $Benutzer, $autor, $titel, $text);
    }
  }
  else
    fehler (__FILE__, __LINE__, 1, 'Der Beitrag konnte keinem Forum, keinem Thema, keinem Beitragsstrang oder keinem Beitrag als Antwort zugeordnet werden');
  mysql_query ("UPDATE Benutzer
                SET Beitraege = Beitraege+1,
                  LetzterBeitrag = '$stempel'
                WHERE BenutzerId = '$BenutzerId'")
    or fehler (__FILE__, __LINE__, 0, 'Beitragszaehler konnte nicht aktuallisiert werden');
  mysql_query ("UPDATE Beitraege
                SET StempelLetzter = '$stempel'
                WHERE BeitragTyp = 1
                  AND ForumId = '$fid'")
    or fehler (__FILE__, __LINE__, 0, 'Forum, Datum des letzten Beitrags konnten nicht aktualisiert werden');
  mysql_query ("UPDATE Beitraege
                SET NumBeitraege = NumBeitraege+1,
                  StempelLetzter = '$stempel',
                  AutorLetzter = '$Benutzer'
                WHERE ThemaId = '$tid' AND BeitragTyp = 2")
    or fehler (__FILE__, __LINE__, 0, 'Z&auml;hler konnten nicht aktualisiert werden');

mysql_close ($db);

$sprung = Array ('foren', 'themen', 'baum', 'strang', 'beitraege');
$gehe_zu = 'gz-' . $sprung["$K_SprungSpeichern"] . '.php';
include($gehe_zu);

}
?>
