<?PHP;
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
  include ("konf-schreiben.php");
  
  $db = db_verbinden ();    
  benutzer_daten_forum ($BenutzerId, $Benutzer, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ("Zugriff verweigert");

  $start = intval ($_POST[start]) * 60;
  $dauer = intval ($_POST[dauer]) * 60;

  if ($dauer <= 2)
    $dauer *= 60;

  $stempel = time ();
  $start += $stempel;
  $ende = $start + $dauer;

  konf_schreiben ('B_wartung_start', $start);
  konf_schreiben ('B_wartung_ende', $ende);
  $zu = 'wartung';
  include ("gehe-zu.php");
?>
