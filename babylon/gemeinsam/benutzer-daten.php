<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

// wir muessen hier auf verschiedene Verzeichnissebenen testen, da diese Datei
// ebenfals aus verschiedenen Ebenen aufgerufen werden kann.
if (file_exists ("konf/konf.php"))
  include_once ("konf/konf.php");
else if (file_exists ("../konf/konf.php"))
  include_once ("../konf/konf.php");
else die ("Forumskonfiguration konnte nicht gelesen werden.");

function benutzer_cookie (&$K_Egl, &$id, &$sw)
{
  global $B_cookie_id, $B_cookie_sw;

  if ((!isset ($_COOKIE[$B_cookie_id])) or (!isset ($_COOKIE[$B_cookie_sw])))
  {
    $K_Egl = FALSE;
    return FALSE;
  }
  // man versucht uns ein gefaelschtes cookie unterzuschieben
  if (! is_int (intval ($_COOKIE[$B_cookie_id])))
    return FALSE;
  $id = $_COOKIE[$B_cookie_id];
  $sw = $_COOKIE[$B_cookie_sw];
  return TRUE;
}

function benutzer_daten_forum (&$BenutzerId, &$Benutzer, &$K_Egl, &$K_Lesen, &$K_Schreiben,
                               &$K_Admin, &$K_AdminForen,
                               &$K_ThemenJeSeite, &$K_BeitraegeJeSeite,
                               &$K_Stil, &$K_Signatur,
                               &$K_SprungSpeichern, &$K_BaumZeigen)
{
  $id = -1;
  $sw = -1;
  if (!benutzer_cookie ($K_Egl, $id, $sw))
    return;

  $erg = mysql_query ("SELECT Benutzer, RechtLesen, RechtSchreiben, RechtAdmin, RechtAdminForen,
                       KonfThemenJeSeite, KonfBeitraegeJeSeite,
                       KonfStil, KonfSignatur, KonfSprungSpeichern, KonfBaumZeigen
                       FROM Benutzer
                       WHERE BenutzerId = \"$id\" AND Cookie = \"$sw\" AND Eingeloggt = 'j'")
    or die ("F0037: Forumdaten des Benutzers konnten nicht aus der Dantenbank gelesen werden");
  if (mysql_num_rows ($erg) == 0)
  {
    $K_Egl = FALSE;
    return;
  }
  $zeile = mysql_fetch_row ($erg);
  
  $BenutzerId = $id;
  $K_Egl = TRUE;
  $Benutzer = $zeile[0];
  $K_Lesen = $zeile[1];
  $K_Schreiben = $zeile[2];
  $K_Admin = $zeile[3];
  $K_AdminForen = $zeile[4];
  $K_ThemenJeSeite = $zeile[5];
  $K_BeitraegeJeSeite = $zeile[6];
  $K_Stil = $zeile[7];
  $K_Signatur = $zeile[8];
  $K_SprungSpeichern = $zeile[9];
  $K_BaumZeigen = $zeile[10];
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
    or die ("F0037: Pers&ouml;nliche Benutzerdaten konnten nicht aus der Dantenbank gelesen werden");
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

function benutzer_daten_profil (&$BenutzerId, &$Benutzer, &$K_Egl, &$K_Stil,
                                &$P_NameZeigen, &$P_Ort,
                                &$P_EMail, &$P_Homepage)
{
  $id = -1;
  $sw = -1;
  if (!benutzer_cookie ($K_Egl, $id, $sw))
    return;
    
  $erg = mysql_query ("SELECT Benutzer, KonfStil, ProfilNameZeigen, ProfilOrt, ProfilEMail, ProfilHomepage
                       FROM Benutzer
                       WHERE BenutzerId = \"$id\" AND Cookie = \"$sw\"  AND Eingeloggt = 'j'")
    or die ("F0037: Profildaten konnten nicht aus der Dantenbank gelesen werden");
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
  $P_Ort = stripslashes ($zeile[3]);
  $P_EMail = stripslashes ($zeile[4]);
  $P_Homepage = stripslashes ($zeile[5]);
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
    or die ("Mitgliedsstatus konnte nicht aus der Dantenbank gelesen werden");
  if (mysql_num_rows ($erg) == 0)
  {
    $K_Egl = FALSE;
    $K_Mitglied = FALSE;
    return;
  }
  $K_Egl = TRUE;
  $K_Mitglied = TRUE;
}
?>

