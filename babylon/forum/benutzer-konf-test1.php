<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

if (isset ($_POST['speichern']))
{

// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $Benutzer = '';
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
  $K_BaumZeigen = 0;

  include ('../gemeinsam/db-verbinden.php');
  include ('../gemeinsam/benutzer-daten.php');
  include ('beitrag-pharsen.php');
  include_once ('fehler.php');

  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil, $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  $K_Grafik = 'n';
  $K_Links = 'n';
  benutzer_daten_recht ($BenutzerId, $K_Grafik, $K_Links);
  $erlaubte_tags = $K_Grafik == 'j' ? '<img>' : '';
  $erlaubte_tags .= $K_Links == 'j' ? '<a>' : '';


  if (!$K_Egl)
    fehler (NULL, 0, 1, 'Zugriff verweigert');
      
  // ##### Signatur #####
  if (isset ($_POST['signatur']))
    $SIGNATUR = addslashes (beitrag_pharsen_ohne_smilies ($_POST['signatur'], $erlaubte_tags));
  else
    $SIGNATUR = '';
  // ##### Themen / Beitraege Je Seite #####
  $je_seite = array ('3', '6', '10', '20');

  if (isset ($_POST['themen_je_seite']))
  {
    foreach ($je_seite as $je)
    {
      if ($je == $_POST['themen_je_seite'])
      {
        $TJS = $je;
        break;
      }
    }
  }
  if (! isset ($TJS))
    $TJS = $K_ThemenJeSeite;

  if (isset ($_POST['beitraege_je_seite']))
  {
    foreach ($je_seite as $je)
    {
      if ($je == $_POST['beitraege_je_seite'])
      {
        $BJS = $je;
        break;
      }
    }
  }
  if (! isset ($BJS))
    $BJS = $K_BeitraegeJeSeite;

  // ##### Sprungziel #####
  $sprung_wert = array ('forum', 'thema', 'baum', 'strang', 'beitrag');
  $i = 0;
  foreach ($sprung_wert as $sprung)
  {
    if (strcmp ($sprung, $_POST['sprung_speichern']) == 0)
      break;
    $i++;
  }
  $SPRUNG = $i;

  // #### Baum anzeigen ####
  $BAUM = (isset ($_POST['baum_zeigen'])) ? 'j' : 'n';

  mysql_query ("UPDATE Benutzer
                SET KonfThemenJeSeite=\"$TJS\", KonfBeitraegeJeSeite=\"$BJS\",
                KonfSignatur=\"$SIGNATUR\", KonfSprungSpeichern=\"$SPRUNG\",
                KonfBaumZeigen=\"$BAUM\"
                WHERE BenutzerId=\"$BenutzerId\"")
    or fehler (__FILE__, __LINE__, 0, 'Die Benutzereinstellungen konnten nicht aktuallisiert werden.');
  mysql_close ($db);
}
$zu = isset ($_POST['speichern']) ? 'benutzer-konf1' : 'foren';
include ("gehe-zu.php");
?>
