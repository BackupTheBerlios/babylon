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
  benutzer_daten_forum ($BenutzerId, $K_Egl, $K_Lesen, $K_Schreiben, $K_Admin,
                        $K_AdminForen, $K_ThemenJeSeite, $K_BeitraegeJeSeite,
                        $K_Stil,  $K_Signatur, $K_SprungSpeichern, $K_BaumZeigen);

  if (!$K_AdminForen)
    die ("Zugriff verweigert");

  $start = intval ($_POST[start]);
  $dauer = intval ($_POST[dauer]);
  
  if ($dauer <= 2)
    $dauer *= 60;

  $stempel = time ();
  $start += $stempel;
  $ende += ($stempel + $dauer);

  konf_schreiben ('$B_wartung_ende', $start);
  konf_schreiben ('$B_wartung_start', $ende);
?>
