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
  $K_Stil = '';
  $P_NameZeigen = 'n';
  $K_VName = '';
  $K_NName = '';
  $P_Ort = '';
  $P_EMail = '';
  $P_Homepage = '';

  include_once ('konf/konf.php');
  include_once ('../gemeinsam/benutzer-daten.php');
  include ('./benutzer-eingaben.php');
  include_once ('fehler.php');
  
  benutzer_daten_profil ($BenutzerId, $Benutzer, $K_Egl,
                         $K_Stil, $P_NameZeigen, $P_Nachricht,
                         $P_NachrichtAnonym, $P_Ort, $P_EMail,
                         $P_Homepage);

  if (!$K_Egl)
    fehler (NULL, 0, 1, 'Zugriff verweigert');

  if (!empty ($_FILES['atavar']['tmp_name']))
  {
    $atavar_datei = $_FILES['atavar']['tmp_name'];
    $atavar_groesse = $_FILES['atavar']['size'];
    $info = getimagesize ($datei);
    $breite = $info[0];
    $hoehe = $info[1];
    $typ = $info[2];
  }
  if ($atavar_groesse > ($B_atavar_max_kb * 1024) or $breite > $B_atavar_max_breite or $hoehe > $B_atavar_max_hoehe)
    $zu_arg = 'atavar_groesse=';
  else if ($typ > 2)
    $zu_arg = 'atavar_format=';
  else
  {
    $atavar = 'n';
    $bild = NULL;
    if (!empty ($_FILES['atavar']['tmp_name']))
    { 
       $bild = addslashes(fread(fopen($atavar_datei, 'r'), $atavar_groesse));
       $atavar = 'j';
    }

    $name_zeigen = (isset ($_POST['name_zeigen'])) ? 'j' : 'n';
    $nachricht = (isset ($_POST['nachricht'])) ? 'j' : 'n';
    $nachricht_anonym = (isset ($_POST['nachricht_anonym'])) ? 'j' : 'n';
    if ($nachricht_anonym == 'j')
      $nachricht = 'j';
    $ort = (strlen ($_POST['ort'])) ? addslashes ($_POST['ort']) : '';
    $homepage = (strlen ($_POST['homepage'])) ? addslashes ($_POST['homepage']) : '';
    $email = (strlen ($_POST['email'])) ? addslashes ($_POST['email']) : '';

    mysql_query ("UPDATE Benutzer
                  SET Atavar = '$atavar',
                      AtavarData= '$bild',
                      ProfilNameZeigen = '$name_zeigen',
                      NachrichtAnnehmen = '$nachricht',
                      NachrichtAnnehmenAnonym = '$nachricht_anonym',
                      ProfilOrt = '$ort',
                      ProfilEMail = '$email',
                      ProfilHomepage = '$homepage'
                  WHERE BenutzerId = '$BenutzerId'")
      or fehler (__FILE__, __LINE__, 0, 'Profildaten konnte nicht aktuallisiert werden.');
  }
}
$zu = isset ($_POST['speichern']) ? 'benutzer-konf3' : 'foren';
include ('gehe-zu.php');
?>
