<?PHP;
/* Copyright 2003, 2004 Detlef Reichl <detlef!reichl()gmx!org>
   Diese Datei gehoert zum Babylon-Forum (babylon.berlios.de).
   
   Babylon ist Freie Software. Du bist berechtigt sie nach Vorgabe der
   GNU-GPL Version 2 zu nutzen und/oder modifizieren und/oder weiterzugeben.
   Lies die Datei COPYING fuer weitere Informationen hierzu. */

if (isset ($_POST[speichern]))
{
// Standart Konfiguration. Man darf absolut nix ;-)
  $BenutzerId = -1;
  $Benutzer = "";
  $K_Egl = FALSE;
  $K_Stil = "";
  $P_NameZeigen = 'n';
  $K_VName = "";
  $K_NName = "";
  $P_Ort = "";
  $P_EMail = "";
  $P_Homepage = "";


  include_once ("../gemeinsam/db-verbinden.php");
  include_once ("../gemeinsam/benutzer-daten.php");
  include ("./benutzer-eingaben.php");
  include_once ("konf/konf.php");
  
  $db = db_verbinden ();    
  benutzer_daten_profil ($BenutzerId, $Benutzer, $K_Egl, $K_Stil,
                         $P_NameZeigen, $P_Ort,
                         $P_EMail, $P_Homepage);

  if (!$K_Egl)
    die ("Zugriff verweigert");

  if (isset ($_FILES[atavar]))
  {
    $datei = $_FILES[atavar][tmp_name];
    $groesse = $_FILES[atavar][size];
    $info = getimagesize ($datei);
    $breite = $info[0];
    $hoehe = $info[1];
    $typ = $info[2];
  }
  if ($groesse > ($B_atavar_max_kb * 1024) or $breite > $B_atavar_max_breite or $hoehe > $B_atavar_max_hoehe)
    $zu_arg = "atavar_groesse=";
  else if ($typ > 2)
    $zu_arg = "atavar_format=";
  else
  {
    $ATAVAR = 'n';
    if (isset ($_FILES[atavar]))
    { 
       $datei = $_FILES[atavar][tmp_name];
       if (!move_uploaded_file ($datei, "atavar/$BenutzerId.jpg"))
         die ("Beim Hochladen der Datei ist ein Fehler aufgetreten. Versuchs nochmal...");
       $ATAVAR = 'j';
    }

    $NAME_ZEIGEN = (isset ($_POST[name_zeigen])) ? "j" : "n";
    $ORT = (strlen ($_POST[ort])) ? addslashes ($_POST[ort]) : "";
    $HOMEPAGE = (strlen ($_POST[homepage])) ? addslashes ($_POST[homepage]) : "";
    $EMAIL = (strlen ($_POST[email])) ? addslashes ($_POST[email]) : "";

    mysql_query ("UPDATE Benutzer
                  SET Atavar=\"$ATAVAR\",
                      ProfilNameZeigen=\"$NAME_ZEIGEN\",
                      ProfilOrt=\"$ORT\",
                      ProfilEMail=\"$EMAIL\",
                      ProfilHomepage=\"$HOMEPAGE\"
                  WHERE BenutzerId=\"$BenutzerId\"")
      or die ("Profildaten konnte nicht aktuallisiert werden.");
  }
}
$zu = isset ($_POST[speichern]) ? "benutzer-konf3" : "foren";
include ("gehe-zu.php");
?>
