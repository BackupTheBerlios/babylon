<?php
/* Copyright 2003, 2004, 2005 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

// wir muessen hier auf verschiedene Verzeichnissebenen testen, da diese Datei
// ebenfals aus verschiedenen Ebenen aufgerufen werden kann.

$konf = $_SERVER['DOCUMENT_ROOT'] . '/forum/konf/konf.php';
include_once ($konf);
$fehler = $_SERVER['DOCUMENT_ROOT'] . '/forum/fehler.php';
include_once ($fehler);

function benutzer_cookie (&$K_Egl, &$id, &$sw)
{
  global $B_cookie_id, $B_cookie_sw;

  if ((!isset ($_COOKIE["$B_cookie_id"])) or (!isset ($_COOKIE["$B_cookie_sw"])))
  {
    $K_Egl = FALSE;
    return FALSE;
  }
  // man versucht uns ein gefaelschtes cookie unterzuschieben
  if (! is_int (intval ($_COOKIE["$B_cookie_id"])))
    return FALSE;
  $id = $_COOKIE["$B_cookie_id"];
  $sw = $_COOKIE["$B_cookie_sw"];
  return TRUE;
}

function benutzer_daten_forum (&$BenutzerId, &$Benutzer, &$K_Egl,
                               &$K_Lesen, &$K_Schreiben, &$K_Admin,
                               &$K_AdminForen, &$K_ThemenJeSeite, &$K_BeitraegeJeSeite,
                               &$K_Stil, &$K_Signatur, &$K_SprungSpeichern,
                               &$K_BaumZeigen)
{
  $id = -1;
  $sw = -1;
  if (!benutzer_cookie ($K_Egl, $id, $sw))
    return;

  $erg = mysql_query ("SELECT Benutzer, Gruppe, KonfThemenJeSeite,
                              KonfBeitraegeJeSeite, KonfStil, KonfSignatur,
                              KonfSprungSpeichern, KonfBaumZeigen
                       FROM Benutzer
                       WHERE BenutzerId = \"$id\" AND Cookie = \"$sw\" AND Eingeloggt = 'j'")
    or fehler (__FILE__, __LINE__, 0, 'Forumdaten des Benutzers konnten nicht aus der Dantenbank gelesen werden');

  if (mysql_num_rows ($erg) == 0)
  {
    $K_Egl = FALSE;
    return;
  }
  $zeile = mysql_fetch_row ($erg);
  
  $BenutzerId = $id;
  $K_Egl = TRUE;
  $Benutzer = $zeile[0];
  $K_ThemenJeSeite = $zeile[2];
  $K_BeitraegeJeSeite = $zeile[3];
  $K_Stil = $zeile[4];
  $K_Signatur = $zeile[5];
  $K_SprungSpeichern = $zeile[6];
  $K_BaumZeigen = $zeile[7];

  $erg = mysql_query ("SELECT RechtLesen, RechtSchreiben, RechtAdmin,
                              RechtAdminForen
                       FROM BenutzerVorlage
                       WHERE Name = '$zeile[1]'")
    or fehler (__FILE__, __LINE__, 0, 'Die Gruppendanten des Benutzers konnten nicht ermittelt werden');
  $zeile = mysql_fetch_row ($erg);

  $K_Lesen = $zeile[0];
  $K_Schreiben = $zeile[1];
  $K_Admin = $zeile[2];
  $K_AdminForen = $zeile[3];
}


function benutzer_daten_persoenlich (&$BenutzerId, &$K_Egl, &$K_Stil,
                                     &$K_EMail, &$K_Alias,
                                     &$K_VName, &$K_NName)
{
  $id = -1;
  $sw = -1;
  if (!benutzer_cookie ($K_Egl, $id, $sw))
    return;
    
  $erg = mysql_query ("SELECT KonfStil, EMail, Benutzer, VName, NName
                       FROM Benutzer
                       WHERE BenutzerId = \"$id\" AND Cookie = \"$sw\"  AND Eingeloggt = 'j'")
    or fehler (__FILE__, __LINE__, 0, 'Pers&ouml;nliche Benutzerdaten konnten nicht aus der Dantenbank gelesen werden');
    
  if (mysql_num_rows ($erg) == 0)
  {
    $K_Egl = FALSE;
    return;
  }
  $zeile = mysql_fetch_row ($erg);

  $BenutzerId = $id;
  $K_Egl = TRUE;
  $K_Stil = $zeile[0];
  $K_EMail = $zeile[1];
  $K_Alias = $zeile[2];
  $K_VName = $zeile[3];
  $K_NName = $zeile[4];
}

function benutzer_daten_profil (&$BenutzerId, &$Benutzer, &$K_Egl,
                                &$K_Stil, &$P_NameZeigen, &$P_Nachricht,
                                &$P_NachrichtAnonym, &$P_Ort, &$P_EMail,
                                &$P_Homepage, &$P_Atavar)
{
  $id = -1;
  $sw = -1;
  if (!benutzer_cookie ($K_Egl, $id, $sw))
    return;
    
  $erg = mysql_query ("SELECT Benutzer, KonfStil, ProfilNameZeigen,
                              NachrichtAnnehmen, NachrichtAnnehmenAnonym, ProfilOrt,
                              ProfilEMail, ProfilHomepage, Atavar
                       FROM Benutzer
                       WHERE BenutzerId = \"$id\" AND Cookie = \"$sw\"  AND Eingeloggt = 'j'")
    or fehler (__FILE__, __LINE__, 0, 'Profildaten konnten nicht aus der Dantenbank gelesen werden');
  if (mysql_num_rows ($erg) == 0)
  {
    $K_Egl = FALSE;
    return;
  }
  $zeile = mysql_fetch_row ($erg);

  $BenutzerId = $id;
  $K_Egl = TRUE;
  $Benutzer = stripslashes ($zeile[0]);
  $K_Stil = $zeile[1];
  $P_NameZeigen = $zeile[2];
  $P_Nachricht = $zeile[3];
  $P_NachrichtAnonym = $zeile[4];
  $P_Ort = stripslashes ($zeile[5]);
  $P_EMail = stripslashes ($zeile[6]);
  $P_Homepage = stripslashes ($zeile[7]);
  $P_Atavar = $zeile[8];
}

function benutzer_daten_mitglied (&$K_Mitglied)
{
  $id = -1;
  $sw = -1;
  $K_Egl;
  if (!benutzer_cookie ($K_Egl, $id, $sw))
    return;
    
  $erg = mysql_query ("SELECT BenutzerId
                       FROM Benutzer
                       WHERE BenutzerId = \"$id\" and Mitglied = 'j' and Eingeloggt = 'j'")
    or fehler (__FILE__, __LINE__, 0, 'Mitgliedsstatus konnte nicht aus der Dantenbank gelesen werden');
  if (mysql_num_rows ($erg) == 0)
  {
    $K_Egl = FALSE;
    $K_Mitglied = FALSE;
    return;
  }
  $K_Egl = TRUE;
  $K_Mitglied = TRUE;
}

function benutzer_daten_recht (&$BenutzerId, &$K_Grafik, &$K_Links)
{
  $id = -1;
  $sw = -1;
  if (!benutzer_cookie ($K_Egl, $id, $sw))
    return;
    
  $erg = mysql_query ("SELECT Gruppe
                       FROM Benutzer
                       WHERE BenutzerId = \"$id\"")
    or fehler (__FILE__, __LINE__, 0, 'Benutzergruppe konnte nicht ermittelt werden');
    
  if (mysql_num_rows($erg) != 1)
    fehler (__FILE__, __LINE__, 1, 'Zu der BenutzerId ist kein oder mehrere Benutzer vorhanden');
    
  $zeile = mysql_fetch_row ($erg);
  $erg = mysql_query ("SELECT RechtLinks, RechtGrafik
                       FROM BenutzerVorlage
                       WHERE Name = \"$zeile[0]\"")
    or fehler (__FILE__, __LINE__, 0, 'Die Zugriffsrechte konnten nicht ermittelt werden');
  $zeile = mysql_fetch_row ($erg);

  $K_Links = $zeile[0];
  $K_Grafik = $zeile[1];
}
?>
